<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Route;
use App\Observers\AuditModelObserver;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\TemporaryPermission;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register audit observer for core models that use the Auditable trait
        User::observe(AuditModelObserver::class);
        Role::observe(AuditModelObserver::class);
        Permission::observe(AuditModelObserver::class);
        TemporaryPermission::observe(AuditModelObserver::class);
    }
}
