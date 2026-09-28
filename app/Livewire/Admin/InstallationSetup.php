<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\ClientLicense;
use App\Models\SystemSetting;
use App\Services\SystemBranding;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use App\Services\LicenseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.modern')]
class InstallationSetup extends Component
{
    use WithFileUploads;

    public string $brandName = '';
    public string $brandAddress = '';
    public string $welcomeHeading = '';
    public string $welcomeStatement = '';
    public string $welcomeTemplate = 'auto';
    public $logo;
    public bool $removeLogo = false;
    public string $brandingMessage = '';

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
        $settings = app(SystemBranding::class)->settings();
        $this->brandName = $settings?->brand_name ?? config('app.name');
        $this->brandAddress = $settings?->address ?? config('app.address', '');
        $this->welcomeHeading = $settings?->welcome_heading ?? '';
        $this->welcomeStatement = $settings?->welcome_statement ?? '';
        $this->welcomeTemplate = $settings?->welcome_template ?? 'auto';

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

    private function brandingRules(): array
    {
        return [
            'brandName' => ['required', 'string', 'max:120'],
            'brandAddress' => ['nullable', 'string', 'max:255'],
            'welcomeHeading' => ['nullable', 'string', 'max:180'],
            'welcomeStatement' => ['nullable', 'string', 'max:1500'],
            'welcomeTemplate' => ['required', Rule::in(array_merge(['auto'], array_keys(config('welcome_templates'))))],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048', 'dimensions:max_width=2048,max_height=2048'],
            'removeLogo' => ['boolean'],
        ];
    }

    public function updatedLogo(): void
    {
        $this->validateOnly('logo', $this->brandingRules());
        $this->removeLogo = false;
    }

    public function saveBranding(): void
    {
        abort_unless(auth()->user()?->is_installation_admin, 403);
        $this->validate($this->brandingRules());
        $newPath = null;
        $oldPath = null;
        try {
            if ($this->logo && ! $this->removeLogo) {
                $newPath = $this->logo->store('branding', 'local');
                if (! $newPath) {
                    $this->addError('logo', 'The logo could not be stored. Please try again.');
                    return;
                }
            }
            DB::transaction(function () use ($newPath, &$oldPath) {
                $settings = SystemSetting::query()->lockForUpdate()->find(1) ?? new SystemSetting;
                $before = $settings->only(['brand_name', 'address', 'welcome_heading', 'welcome_statement', 'welcome_template', 'logo_path']);
                $oldPath = $settings->logo_path;
                $settings->id = 1;
                $settings->fill([
                    'brand_name' => trim($this->brandName),
                    'address' => trim($this->brandAddress),
                    'welcome_heading' => trim($this->welcomeHeading) ?: null,
                    'welcome_statement' => trim($this->welcomeStatement) ?: null,
                    'welcome_template' => $this->welcomeTemplate,
                    'logo_path' => $newPath ?: ($this->removeLogo ? null : $oldPath),
                ])->save();
                AuditLog::create([
                    'actor_id' => auth()->id(), 'action' => 'system.branding_updated',
                    'model_type' => SystemSetting::class, 'model_id' => 1,
                    'before' => $before, 'after' => $settings->only(array_keys($before)),
                    'ip' => request()->ip(), 'user_agent' => request()->userAgent(),
                ]);
            });
        } catch (\Throwable $exception) {
            if ($newPath) {
                Storage::disk('local')->delete($newPath);
            }
            throw $exception;
        }
        if ($oldPath && ($newPath || $this->removeLogo) && str_starts_with($oldPath, 'branding/')) {
            Storage::disk('local')->delete($oldPath);
        }
        $this->reset('logo', 'removeLogo');
        app(SystemBranding::class)->apply();
        $this->brandingMessage = 'System branding saved. The welcome page, login, and application branding are updated.';
    }

    public function render()
    {
        return view('components.admin.installation-setup', [
            'welcomeTemplates' => config('welcome_templates'),
            'welcomePreview' => app(SystemBranding::class)->template($this->welcomeTemplate, $this->plan),
            'currentLogoUrl' => app(SystemBranding::class)->logoUrl(),
            'plans' => config('mediflow_modules.plans'),
            'features' => config('mediflow_modules.features'),
            'required' => config('mediflow_modules.required'),
            'dependencies' => config('mediflow_modules.dependencies'),
        ]);
    }
}
