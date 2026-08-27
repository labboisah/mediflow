<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\Client;
use App\Models\ClientEnabledModule;
use App\Models\ClientLicense;
use App\Models\Module;
use App\Services\LicenseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.modern')]
class LicenseManagement extends Component
{
    public ?int $licenseId = null;
    public int|string|null $clientId = null;
    public string $clientName = '';
    public string $plan = 'hospital';
    public string $licenseKey = '';
    public ?string $startsAt = null;
    public ?string $expiresAt = null;
    public ?int $branchLimit = null;
    public bool $isActive = true;
    public array $selectedModules = [];

    public function mount(LicenseService $licenseService): void
    {
        $license = $licenseService->currentLicense();

        if (! $license) {
            $this->clientName = config('app.name', 'Mediflow Client');
            $this->applyPlanPreset($this->plan);
            return;
        }

        $this->licenseId = $license->id;
        $this->clientId = $license->client_id;
        $this->clientName = $license->client_name;
        $this->plan = config("mediflow_modules.legacy_plan_aliases.{$license->plan}", $license->plan);
        $this->licenseKey = $license->license_key ?? '';
        $this->startsAt = $license->starts_at?->format('Y-m-d');
        $this->expiresAt = $license->expires_at?->format('Y-m-d');
        $this->branchLimit = $license->branch_limit;
        $this->isActive = $license->is_active;

        $savedModules = $license->enabledModules()
            ->where('is_enabled', true)
            ->pluck('module_name')
            ->all();

        $this->selectedModules = $savedModules !== []
            ? collect($savedModules)->intersect($this->availableFeatureKeys())->values()->all()
            : $this->presetModules($this->plan);
    }

    public function render()
    {
        return view('components.admin.license-management', [
            'pageTitle' => 'License Management',
            'pageSubtitle' => 'Select a client license group and enable the feature modules included in the plan.',
            'plans' => $this->plans(),
            'features' => $this->features(),
            'clients' => $this->clients(),
            'currentLicense' => $this->licenseId ? ClientLicense::with('enabledModules')->find($this->licenseId) : null,
            'enabledModules' => app(LicenseService::class)->enabledModules(),
        ]);
    }

    public function updatedClientId($clientId): void
    {
        if (! $clientId || ! Schema::hasTable('clients')) {
            return;
        }

        $client = Client::find($clientId);

        if ($client) {
            $this->clientName = $client->name;
            $this->plan = $client->sector ?: $this->plan;
            $this->applyPlanPreset($this->plan);
        }
    }

    public function updatedPlan(string $plan): void
    {
        $this->applyPlanPreset($plan);
    }

    public function applyPlanPreset(?string $plan = null): void
    {
        $this->plan = $plan ?: $this->plan;
        $this->selectedModules = $this->presetModules($this->plan);

        if ($this->plan === 'enterprise_hospital' && ! $this->branchLimit) {
            $this->branchLimit = 2;
        }

        if ($this->plan !== 'enterprise_hospital') {
            $this->branchLimit = null;
        }
    }

    public function selectAllModules(): void
    {
        $this->selectedModules = $this->availableFeatureKeys()->values()->all();
    }

    public function clearModules(): void
    {
        $this->selectedModules = [];
    }

    public function generateLicenseKey(): void
    {
        $prefix = strtoupper(str_replace('_', '-', $this->plan));
        $this->licenseKey = 'MEDIFLOW-' . $prefix . '-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
    }

    public function save(): void
    {
        $availableModules = $this->availableFeatureKeys()->all();

        $validated = $this->validate([
            'clientName' => ['required', 'string', 'max:255'],
            'clientId' => ['nullable', 'exists:clients,id'],
            'plan' => ['required', Rule::in(array_keys($this->plans()))],
            'licenseKey' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('client_licenses', 'license_key')->ignore($this->licenseId),
            ],
            'startsAt' => ['nullable', 'date'],
            'expiresAt' => ['nullable', 'date', 'after_or_equal:startsAt'],
            'branchLimit' => ['nullable', 'integer', 'min:1'],
            'isActive' => ['boolean'],
            'selectedModules' => ['array', 'min:1'],
            'selectedModules.*' => [Rule::in($availableModules)],
        ]);

        if ($validated['plan'] === 'enterprise_hospital' && empty($validated['branchLimit'])) {
            $this->addError('branchLimit', 'Enterprise Hospital licenses require a branch limit.');
            return;
        }

        DB::transaction(function () use ($validated) {
            if ($this->isActive) {
                ClientLicense::query()
                    ->when($this->licenseId, fn ($query) => $query->where('id', '!=', $this->licenseId))
                    ->update(['is_active' => false]);
            }

            $license = ClientLicense::updateOrCreate(
                ['id' => $this->licenseId],
                [
                    'client_name' => $validated['clientName'],
                    'client_id' => $validated['clientId'] ?: null,
                    'plan' => $validated['plan'],
                    'license_key' => $validated['licenseKey'] ?: null,
                    'branch_limit' => $validated['plan'] === 'enterprise_hospital' ? $validated['branchLimit'] : null,
                    'starts_at' => $validated['startsAt'] ?: null,
                    'expires_at' => $validated['expiresAt'] ?: null,
                    'is_active' => $validated['isActive'],
                ]
            );

            $selectedModules = collect($this->selectedModules)->unique()->values();

            foreach ($this->availableFeatureKeys() as $moduleName) {
                ClientEnabledModule::updateOrCreate(
                    [
                        'client_license_id' => $license->id,
                        'module_name' => $moduleName,
                    ],
                    ['is_enabled' => $selectedModules->contains($moduleName)]
                );
            }

            AuditLog::record(auth()->user(), 'license.save', $license, null, [
                'plan' => $license->plan,
                'modules' => $selectedModules->all(),
            ]);

            $this->licenseId = $license->id;
        });

        $this->dispatch('toast', message: 'License updated successfully.', type: 'success');
    }

    private function plans(): array
    {
        return config('mediflow_modules.plans', []);
    }

    private function features(): Collection
    {
        $configuredLabels = collect(config('mediflow_modules.features', []));

        return $this->availableFeatureKeys()
            ->map(fn (string $module) => [
                'key' => $module,
                'label' => $configuredLabels->get($module, str($module)->replace('_', ' ')->headline()->toString()),
                'routes' => $this->moduleRouteCount($module),
                'included' => in_array($module, $this->presetModules($this->plan), true),
                'enabled' => in_array($module, $this->selectedModules, true),
            ])
            ->values();
    }

    private function clients(): Collection
    {
        if (! Schema::hasTable('clients')) {
            return collect();
        }

        return Client::query()
            ->orderBy('name')
            ->get(['id', 'name', 'sector', 'status']);
    }

    private function presetModules(string $plan): array
    {
        $modules = config("mediflow_modules.plans.{$plan}.modules", []);

        if (in_array('*', $modules, true)) {
            return $this->availableFeatureKeys()
                ->merge(collect($modules)->reject(fn (string $module) => $module === '*'))
                ->unique()
                ->values()
                ->all();
        }

        return collect($modules)
            ->intersect($this->availableFeatureKeys())
            ->values()
            ->all();
    }

    private function availableFeatureKeys(): Collection
    {
        $modules = collect(config('mediflow_modules.features', []))->keys();

        if (Schema::hasTable('modules')) {
            $modules = $modules->merge(
                Module::query()
                    ->whereNotNull('license_module')
                    ->distinct()
                    ->pluck('license_module')
            );
        }

        return $modules
            ->filter()
            ->reject(fn (string $module) => $module === '*')
            ->reject(fn (string $module) => in_array($module, config('mediflow_modules.platform_modules', ['platform']), true))
            ->unique()
            ->sort()
            ->values();
    }

    private function moduleRouteCount(string $module): int
    {
        if (! Schema::hasTable('modules')) {
            return 0;
        }

        return Module::query()
            ->where('license_module', $module)
            ->where('is_active', true)
            ->count();
    }
}
