<?php

namespace App\Services;

use KernelBridge\LicensingClient\Services\LicenseCacheService;

class CentralLicenseService extends LicenseService
{
    public function __construct(private readonly LicenseCacheService $cache) {}

    public function currentPlan(): ?string
    {
        return $this->payload()['subscription']['primary_package'] ?? null;
    }

    public function selectedPackages(): array
    {
        return $this->payload()['subscription']['selected_packages'] ?? [];
    }

    public function configuredModules(): array
    {
        if (! $this->cache->hasUsableLicense()) {
            return config('mediflow_modules.required', ['core', 'access_control']);
        }

        return array_values(array_intersect(
            array_keys(config('mediflow_modules.features')),
            array_keys(array_filter($this->payload()['features'] ?? [], fn ($feature) => (bool) ($feature['enabled'] ?? false))),
        ));
    }

    public function enabledModules(): array
    {
        return $this->configuredModules();
    }

    private function payload(): array
    {
        try {
            return $this->cache->state()->entitlement_payload ?? [];
        } catch (\Throwable) {
            return [];
        }
    }
}
