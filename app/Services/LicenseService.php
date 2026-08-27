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
        return $this->currentLicense()?->plan ?? config('mediflow_modules.default_plan');
    }

    public function enabledModules(): array
    {
        $plan = $this->currentPlan();
        $modules = config("mediflow_modules.plans.{$plan}", []);

        if (in_array('*', $modules, true)) {
            return ['*'];
        }

        $license = $this->currentLicense();

        if ($license && Schema::hasTable('client_enabled_modules')) {
            $addons = $license->enabledModules()
                ->where('is_enabled', true)
                ->pluck('module_name')
                ->all();

            $modules = array_merge($modules, $addons);
        }

        return collect($modules)->filter()->unique()->values()->all();
    }

    public function moduleEnabled(?string $module): bool
    {
        if ($module === null || $module === '') {
            return true;
        }

        $enabledModules = $this->enabledModules();

        return in_array('*', $enabledModules, true) || in_array($module, $enabledModules, true);
    }

    public function userHasModuleAccess(User $user, ?string $module): bool
    {
        if ($module === null || $module === '') {
            return true;
        }

        if (! $this->moduleEnabled($module)) {
            return false;
        }

        if ($user->isSuperAdmin() || $user->hasRole('administrator')) {
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
