<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureInstallationModules;
use App\Livewire\Admin\InstallationSetup;
use App\Models\ClientLicense;
use App\Models\User;
use App\Services\LicenseService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class InstallationSetupTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'session.driver' => 'array']);
        Schema::create('users', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('email')->unique();
            $table->string('password'); $table->boolean('is_installation_admin')->default(false);
            $table->timestamp('email_verified_at')->nullable(); $table->softDeletes(); $table->timestamps();
        });
        Schema::create('roles', function (Blueprint $table) { $table->id(); $table->string('name'); });
        Schema::create('role_user', function (Blueprint $table) { $table->unsignedBigInteger('user_id'); $table->unsignedBigInteger('role_id'); });
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('actor_id')->nullable(); $table->string('action');
            $table->string('model_type')->nullable(); $table->unsignedBigInteger('model_id')->nullable();
            foreach (['before', 'after', 'meta', 'ip', 'user_agent'] as $column) { $table->text($column)->nullable(); }
            $table->timestamps();
        });
        (require database_path('migrations/2026_08_27_000002_create_client_licenses_table.php'))->up();
        (require database_path('migrations/2026_08_27_000003_create_client_enabled_modules_table.php'))->up();
    }

    private function admin(bool $installation = true): User
    {
        return User::withoutEvents(function () use ($installation) {
            $user = new User(['name' => 'Admin', 'email' => 'admin@example.test', 'password' => 'test-password']);
            $user->is_installation_admin = $installation;
            $user->save();
            return $user;
        });
    }

    private function license(array $attributes = []): ClientLicense
    {
        return ClientLicense::create(array_merge(['client_name' => 'Test', 'plan' => 'pharmacy', 'is_active' => true], $attributes));
    }

    public function test_ordinary_administrator_cannot_open_setup(): void
    {
        $user = $this->admin(false);
        \Illuminate\Support\Facades\DB::table('roles')->insert(['id' => 1, 'name' => 'administrator']);
        $user->roles()->attach(1);
        $this->actingAs($user)->get('/admin/installation')->assertForbidden();
        Livewire::actingAs($user)->test(InstallationSetup::class)->assertForbidden();
    }

    public function test_super_admin_can_save_a_package_and_explicit_module_selection(): void
    {
        Livewire::actingAs($this->admin())->test(InstallationSetup::class)
            ->set('clientName', 'Test Pharmacy')->set('plan', 'pharmacy')
            ->set('modules', ['core', 'access_control', 'pharmacy', 'billing'])
            ->call('save')->assertHasNoErrors()->assertSee('Installation saved');
        $service = app(LicenseService::class);
        $this->assertTrue($service->moduleEnabled('pharmacy'));
        $this->assertFalse($service->moduleEnabled('finance'));
        $this->assertFalse($service->moduleEnabled('maternity'));
        $this->assertDatabaseHas('audit_logs', ['action' => 'installation.configured']);
        $this->assertDatabaseHas('client_enabled_modules', ['module_name' => 'finance', 'is_enabled' => false]);
    }

    public function test_dependencies_and_unknown_modules_are_rejected(): void
    {
        Livewire::actingAs($this->admin())->test(InstallationSetup::class)
            ->set('modules', ['core', 'access_control', 'doctor'])->call('save')->assertHasErrors('modules')
            ->set('modules', ['core', 'unknown'])->call('save')->assertHasErrors('modules.1');
        $this->assertDatabaseCount('client_licenses', 0);
    }

    public function test_branch_management_requires_enterprise(): void
    {
        Livewire::actingAs($this->admin())->test(InstallationSetup::class)
            ->set('plan', 'hospital')->set('modules', ['core', 'access_control', 'branch_management'])
            ->call('save')->assertHasErrors('modules');
    }

    public function test_expired_inactive_and_future_licenses_never_restore_hospital_access(): void
    {
        $license = $this->license(['plan' => 'hospital', 'expires_at' => now()->subDay()]);
        $service = app(LicenseService::class);
        $this->assertFalse($service->moduleEnabled('pharmacy'));
        $license->update(['expires_at' => null, 'is_active' => false]);
        $this->assertFalse($service->moduleEnabled('pharmacy'));
        $license->update(['is_active' => true, 'starts_at' => now()->addDay()]);
        $this->assertFalse($service->moduleEnabled('pharmacy'));
        $this->assertTrue($service->moduleEnabled('core'));
    }

    public function test_explicit_all_disabled_does_not_restore_plan(): void
    {
        $this->license()->enabledModules()->create(['module_name' => 'pharmacy', 'is_enabled' => false]);
        $this->assertFalse(app(LicenseService::class)->moduleEnabled('pharmacy'));
    }

    public function test_latest_inactive_license_does_not_reactivate_an_older_license(): void
    {
        $this->license(['plan' => 'enterprise_hospital']);
        $this->license(['is_active' => false]);
        $this->assertFalse(app(LicenseService::class)->moduleEnabled('maternity'));
    }

    public function test_direct_get_and_post_are_blocked_even_for_super_admin(): void
    {
        $this->license();
        Route::match(['GET', 'POST'], '/installation-test-lab', fn () => 'allowed')
            ->middleware(EnsureInstallationModules::class)->name('lab.installation-test');
        $this->actingAs($this->admin())->get('/installation-test-lab')->assertForbidden();
        $this->post('/installation-test-lab')->assertForbidden();
    }

    public function test_routes_and_livewire_updates_have_package_middleware(): void
    {
        foreach (['admin.installation', 'lab.requests.index', 'pharmacy.medicines.index', 'patient.prescription.create'] as $name) {
            $this->assertContains(EnsureInstallationModules::class, Route::getRoutes()->getByName($name)->gatherMiddleware());
        }
        $this->assertContains(EnsureInstallationModules::class, app(\Livewire\Mechanisms\PersistentMiddleware\PersistentMiddleware::class)->getPersistentMiddleware());
    }

    public function test_revoked_super_admin_cannot_save_an_open_screen(): void
    {
        $user = $this->admin();
        $screen = Livewire::actingAs($user)->test(InstallationSetup::class);
        $user->is_installation_admin = false;
        $user->saveQuietly();
        $screen->call('save')->assertForbidden();
        $this->assertDatabaseCount('client_licenses', 0);
    }

    public function test_report_datasets_respect_package(): void
    {
        $license = $this->license();
        $service = app(LicenseService::class);
        $this->assertFalse($service->reportTableEnabled('deliveries'));
        $this->assertFalse($service->reportTableEnabled('vital_signs'));
        $license->update(['plan' => 'maternity_clinic']);
        $this->assertTrue($service->reportTableEnabled('deliveries'));
        $this->assertFalse($service->reportTableEnabled('investigation_requests'));
    }

    public function test_six_presets_and_shared_routes_are_consistent(): void
    {
        $service = app(LicenseService::class);
        $this->assertCount(6, config('mediflow_modules.plans'));
        foreach (array_keys(config('mediflow_modules.plans')) as $plan) {
            $modules = $service->planModules($plan);
            foreach ($modules as $module) {
                foreach (config("mediflow_modules.dependencies.$module", []) as $dependency) {
                    $this->assertContains($dependency, $modules, "$plan: $module requires $dependency");
                }
            }
        }
        $this->assertNotContains('branch_management', $service->planModules('hospital'));
        $this->assertContains('branch_management', $service->planModules('enterprise_hospital'));
        $this->license(['plan' => 'diagnostic_center']);
        $this->assertTrue($service->routeEnabled('patient.investigation.create'));
        $this->assertFalse($service->routeEnabled('patient.prescription.create'));
        $this->assertFalse($service->routeEnabled('patient.drugchart.record'));
        $this->assertFalse($service->routeEnabled(null, 'api/v1/sync/records'));
    }
}
