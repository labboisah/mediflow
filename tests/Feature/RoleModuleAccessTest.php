<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\ModuleUserAccess;
use App\Models\Role;
use App\Models\User;
use App\Services\LicenseService;
use App\Services\RoleModuleAccess;
use App\Services\SidebarService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RoleModuleAccessTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        Schema::create('users', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('email'); $t->string('password'); $t->softDeletes(); $t->timestamps();
        });
        Schema::create('audit_logs', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('actor_id')->nullable(); $t->string('action'); $t->string('model_type')->nullable(); $t->unsignedBigInteger('model_id')->nullable();
            foreach (['before', 'after', 'meta', 'ip', 'user_agent'] as $column) $t->text($column)->nullable();
            $t->timestamps();
        });
        Schema::create('roles', function (Blueprint $t) { $t->id(); $t->string('name'); $t->timestamps(); });
        Schema::create('role_user', function (Blueprint $t) { $t->unsignedBigInteger('user_id'); $t->unsignedBigInteger('role_id'); });
        Schema::create('modules', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('route'); $t->string('license_module'); $t->boolean('is_active')->default(true); $t->timestamps();
        });
        Schema::create('module_role', function (Blueprint $t) { $t->unsignedBigInteger('module_id'); $t->unsignedBigInteger('role_id'); });
        (require database_path('migrations/2026_08_27_000004_create_module_user_access_table.php'))->up();
        $this->partialMock(LicenseService::class, function ($mock) {
            $mock->shouldReceive('moduleEnabled')->andReturnUsing(fn ($module) => $module === 'pharmacy');
            $mock->shouldReceive('routeEnabled')->andReturn(true);
        });
    }

    private function technician(): User
    {
        $role = Role::create(['name' => 'pharmacy_technician']);
        $module = Module::create(['name' => 'pharmacy_transactions', 'route' => 'pharmacy.transactions.index', 'license_module' => 'pharmacy', 'is_active' => true]);
        $module->roles()->attach($role);
        $user = User::create(['name' => 'Technician', 'email' => 'tech@example.test', 'password' => 'password']);
        $user->syncRoles([$role->id]);
        return $user;
    }

    public function test_role_grant_restores_technician_workspace_without_admin_access(): void
    {
        $user = $this->technician();
        $license = app(LicenseService::class);
        $module = Module::with('roles')->firstOrFail()->setRelation('permissions', collect());
        $this->assertFalse(app(SidebarService::class)->canShowItem($user, $module));
        app(RoleModuleAccess::class)->grantMissing($user);
        $this->assertTrue($license->userHasModuleAccess($user, 'pharmacy'));
        $this->assertTrue(app(SidebarService::class)->canShowItem($user, $module));
        $this->assertFalse($user->hasRole('administrator'));
        $this->assertFalse($license->userHasModuleAccess($user, 'finance'));
        app(RoleModuleAccess::class)->grantMissing($user);
        $this->assertSame(1, $user->moduleAccess()->count());
    }

    public function test_technician_does_not_see_manager_only_pharmacy_links(): void
    {
        $user = $this->technician();
        app(RoleModuleAccess::class)->grantMissing($user);
        foreach (['pharmacy_medicines', 'pharmacy_stock', 'pharmacy_stock_reconciliation', 'pharmacy_batches', 'pharmacy_expiry_alerts', 'pharmacy_finance_report'] as $name) {
            $module = new Module(['name' => $name, 'route' => 'pharmacy.finance.report', 'license_module' => 'pharmacy']);
            $this->assertFalse(app(SidebarService::class)->canShowItem($user, $module));
        }
    }

    public function test_explicit_revocation_is_not_reactivated(): void
    {
        $user = $this->technician();
        ModuleUserAccess::create(['user_id' => $user->id, 'license_module' => 'pharmacy', 'is_active' => false]);
        app(RoleModuleAccess::class)->grantMissing($user);
        $this->assertFalse(app(LicenseService::class)->userHasModuleAccess($user, 'pharmacy'));
    }

    public function test_expired_or_future_grants_are_not_extended(): void
    {
        $user = $this->technician();
        $access = ModuleUserAccess::create(['user_id' => $user->id, 'license_module' => 'pharmacy', 'is_active' => true, 'expires_at' => now()->subDay()]);
        app(RoleModuleAccess::class)->grantMissing($user);
        $this->assertFalse(app(LicenseService::class)->userHasModuleAccess($user, 'pharmacy'));
        $access->update(['expires_at' => null, 'starts_at' => now()->addDay()]);
        app(RoleModuleAccess::class)->grantMissing($user);
        $this->assertFalse(app(LicenseService::class)->userHasModuleAccess($user, 'pharmacy'));
    }

    public function test_unlicensed_and_unrelated_modules_are_not_granted(): void
    {
        $user = $this->technician();
        $module = Module::create(['name' => 'lab', 'route' => 'lab.index', 'license_module' => 'laboratory', 'is_active' => true]);
        $module->roles()->attach($user->roles()->first());
        Module::create(['name' => 'unrelated', 'route' => 'test.index', 'license_module' => 'reports', 'is_active' => true]);
        app(RoleModuleAccess::class)->grantMissing($user);
        $this->assertSame(['pharmacy'], $user->moduleAccess()->pluck('license_module')->all());
    }
}
