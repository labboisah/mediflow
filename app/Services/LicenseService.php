<?php

namespace App\Services;

use App\Models\ClientLicense;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class LicenseService
{
    public function currentLicense(): ?ClientLicense
    {
        if (! Schema::hasTable('client_licenses')) {
            return null;
        }

        return ClientLicense::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->latest('id')
            ->first();
    }

    public function currentPlan(): ?string
    {
        $plan = $this->currentLicense()?->plan ?? config('mediflow_modules.default_plan');

        return config("mediflow_modules.legacy_plan_aliases.{$plan}", $plan);
    }

    public function enabledModules(): array
    {
        $plan = $this->currentPlan();
        $modules = config("mediflow_modules.plans.{$plan}.modules", []);

        if (in_array('*', $modules, true)) {
            $modules = $this->allKnownModules($modules);
        }

        $license = $this->currentLicense();

        if ($license && Schema::hasTable('client_enabled_modules')) {
            $savedModules = $license->enabledModules()
                ->where('is_enabled', true)
                ->pluck('module_name')
                ->all();

            if ($savedModules !== []) {
                return collect($savedModules)->filter()->unique()->values()->all();
            }
        }

        return collect($modules)->filter()->unique()->values()->all();
    }

    public function moduleEnabled(?string $module): bool
    {
        if ($module === null || $module === '') {
            return true;
        }

        $enabledModules = $this->enabledModules();

        return in_array($module, $enabledModules, true);
    }

    private function allKnownModules(array $extraModules = []): array
    {
        $configuredModules = collect(config('mediflow_modules.features', []))->keys();

        if (Schema::hasTable('modules')) {
            $configuredModules = $configuredModules
                ->merge(\App\Models\Module::query()
                    ->whereNotNull('license_module')
                    ->distinct()
                    ->pluck('license_module'));
        }

        return $configuredModules
            ->merge(collect($extraModules)->reject(fn (string $module) => $module === '*'))
            ->reject(fn (string $module) => $module === 'platform')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function userHasModuleAccess(User $user, ?string $module): bool
    {
        if ($module === null || $module === '') {
            return true;
        }

        if (! $this->moduleEnabled($module)) {
            return false;
        }

        if ($user->hasRole('administrator')) {
            return true;
        }

        if (! Schema::hasTable('module_user_access')) {
            return true;
        }

        return $user->moduleAccess()
            ->where('license_module', $module)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->exists();
    }

}
