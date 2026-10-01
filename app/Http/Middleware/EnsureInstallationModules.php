<?php

namespace App\Http\Middleware;

use App\Services\LicenseService;
use App\Services\PackageFinanceAccess;
use Closure;
use Illuminate\Http\Request;

class EnsureInstallationModules
{
    public function handle(Request $request, Closure $next)
    {
        $finance = app(PackageFinanceAccess::class);
        if ($finance->matches($request->route()?->getName())) {
            abort_unless($finance->allows($request->user()), 403, 'Only the package administrator can manage expenses and revenue.');
        }
        // Resolve the original route again on Livewire updates through persistent middleware.
        $license = app(LicenseService::class);
        abort_unless($license->routeEnabled($request->route()?->getName(), $request->path()), 403,
            'This feature is not included in this installation.');

        return $next($request);
    }
}
