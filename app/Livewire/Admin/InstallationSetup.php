<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\ClientLicense;
use App\Services\LicenseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.modern')]
class InstallationSetup extends Component
{
    public string $clientName = '';
    public string $plan = 'hospital';
    public array $modules = [];
    public string $expiresAt = '';
    public bool $active = true;
    public string $savedMessage = '';

    public function boot(): void
    {
        abort_unless(auth()->user()?->is_installation_admin, 403);
    }

    public function mount(LicenseService $license): void
    {
        $current = $license->currentLicense();
        $this->clientName = $current?->client_name ?? config('app.title', config('app.name'));
        $this->plan = $license->currentPlan() ?? 'hospital';
        $this->modules = $license->configuredModules();
        $this->expiresAt = $current?->expires_at?->format('Y-m-d') ?? '';
        $this->active = $current?->is_active ?? true;
    }

    public function updatedPlan(): void
    {
        $this->validateOnly('plan', ['plan' => ['required', Rule::in(array_keys(config('mediflow_modules.plans')))]]);
        $this->modules = app(LicenseService::class)->planModules($this->plan);
        $this->savedMessage = '';
    }

    public function save(): void
    {
        abort_unless(auth()->user()?->is_installation_admin, 403);
        $this->validate([
            'clientName' => ['required', 'string', 'max:255'],
            'plan' => ['required', Rule::in(array_keys(config('mediflow_modules.plans')))],
            'modules' => ['required', 'array'],
            'modules.*' => ['string', 'distinct', Rule::in(array_keys(config('mediflow_modules.features')))],
            'expiresAt' => ['nullable', 'date_format:Y-m-d'],
            'active' => ['boolean'],
        ]);
        $licenseService = app(LicenseService::class);
        $required = config('mediflow_modules.required', []);
        $selected = array_values(array_unique(array_merge($required, $this->modules)));
        foreach ($selected as $module) {
            foreach (config("mediflow_modules.dependencies.{$module}", []) as $dependency) {
                if (! in_array($dependency, $selected, true)) {
                    $this->addError('modules', config("mediflow_modules.features.{$module}").' requires '.config("mediflow_modules.features.{$dependency}").'.');
                    return;
                }
            }
        }
        if ($this->plan !== 'enterprise_hospital' && in_array('branch_management', $selected, true)) {
            $this->addError('modules', 'Branch Management requires the Enterprise Hospital package.');
            return;
        }

        DB::transaction(function () use ($selected, $licenseService) {
            $license = ClientLicense::query()->latest('id')->lockForUpdate()->first() ?? new ClientLicense;
            $before = ['plan' => $license->plan, 'modules' => $licenseService->configuredModules(), 'is_active' => $license->is_active];
            $license->fill([
                'client_name' => $this->clientName,
                'plan' => $this->plan,
                'expires_at' => $this->expiresAt ? \Carbon\Carbon::parse($this->expiresAt)->endOfDay() : null,
                'starts_at' => $license->starts_at ?? now(),
                'is_active' => $this->active,
            ])->save();
            $license->enabledModules()->delete();
            foreach (array_keys(config('mediflow_modules.features')) as $module) {
                $license->enabledModules()->create(['module_name' => $module, 'is_enabled' => in_array($module, $selected, true)]);
            }
            AuditLog::create([
                'actor_id' => auth()->id(), 'action' => 'installation.configured',
                'model_type' => ClientLicense::class, 'model_id' => $license->id,
                'before' => $before,
                'after' => ['client_name' => $this->clientName, 'plan' => $this->plan, 'modules' => $selected, 'is_active' => $this->active, 'expires_at' => $this->expiresAt],
                'ip' => request()->ip(), 'user_agent' => request()->userAgent(),
            ]);
        });
        $this->modules = $selected;
        $this->savedMessage = 'Installation saved. Package restrictions now apply to all users. Existing records are retained.';
    }

    public function render()
    {
        return view('components.admin.installation-setup', [
            'plans' => config('mediflow_modules.plans'),
            'features' => config('mediflow_modules.features'),
            'required' => config('mediflow_modules.required'),
            'dependencies' => config('mediflow_modules.dependencies'),
        ]);
    }
}
