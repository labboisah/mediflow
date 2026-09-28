<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Schema;

class SystemBranding
{
    public function settings(): ?SystemSetting
    {
        return Schema::hasTable('system_settings') ? SystemSetting::find(1) : null;
    }

    public function logoUrl(): string
    {
        $settings = $this->settings();
        return $settings?->logo_path
            ? route('branding.logo', ['v' => substr(hash('sha256', $settings->logo_path), 0, 12)])
            : asset('images/logo.png');
    }

    public function template(string $choice = 'auto', ?string $plan = null): array
    {
        $key = $choice === 'auto' ? ($plan ?? app(LicenseService::class)->currentPlan()) : $choice;
        $templates = config('welcome_templates');
        $key = isset($templates[$key]) ? $key : 'hospital';
        return ['key' => $key] + $templates[$key];
    }

    public function apply(): void
    {
        if ($settings = $this->settings()) {
            config(['app.name' => $settings->brand_name, 'app.title' => $settings->brand_name, 'app.address' => $settings->address ?? '']);
        }
    }
}
