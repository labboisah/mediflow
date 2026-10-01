<?php

namespace App\Http\Middleware;

use App\Services\InstallationOnboarding;
use Closure;
use Illuminate\Http\Request;
use KernelBridge\LicensingClient\Exceptions\KernelBridgeApiException;
use KernelBridge\LicensingClient\Exceptions\KernelBridgeUnavailableException;
use KernelBridge\LicensingClient\Services\LicenseCacheService;
use KernelBridge\LicensingClient\Services\LicenseVerificationService;

class EnsureCentralLicense
{
    public function handle(Request $request, Closure $next)
    {
        if (! config('central_licensing.enabled')
            || $request->is('license', 'license/*', 'login', 'logout', 'forgot-password', 'reset-password/*', 'up', 'installation/setup', 'branding/*')) {
            return $next($request);
        }
        $cache = app(LicenseCacheService::class);
        try {
            app(LicenseVerificationService::class)->verify();
        } catch (KernelBridgeApiException|KernelBridgeUnavailableException $exception) {
            // The cache service only allows signed, device-bound, unexpired offline grace.
        }
        if (! $cache->hasUsableLicense()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                abort(402, 'Activate this computer through the installation administrator.');
            }

            return redirect()->route('kernelbridge.license.show');
        }

        if (! app(InstallationOnboarding::class)->completed()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                abort(409, 'Complete installation setup before using the system.');
            }

            return redirect()->route('installation.setup');
        }

        return $next($request);
    }
}
