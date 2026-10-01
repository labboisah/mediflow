<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureCentralLicense;
use App\Http\Middleware\EnsureInstallationModules;
use App\Livewire\Dashboard;
use App\Models\User;
use App\Providers\CentralLicensingServiceProvider;
use App\Services\CentralLicenseService;
use App\Services\InstallationOnboarding;
use App\Services\LicenseService;
use App\Services\SidebarService;
use Dotenv\Repository\Adapter\PutenvAdapter;
use Dotenv\Repository\RepositoryBuilder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use KernelBridge\LicensingClient\Services\DeviceIdentity;
use KernelBridge\LicensingClient\Services\LicenseCacheService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CentralLicensingTest extends TestCase
{
    private LicenseCacheService $cache;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        config([
            'database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:',
            'central_licensing.enabled' => true, 'session.driver' => 'array',
            'kernelbridge-licensing.product_code' => 'MEDIFLOW',
            'kernelbridge-licensing.api_url' => 'https://licensing.test/api/v1',
            'kernelbridge-licensing.api_token' => 'test-token',
            'kernelbridge-licensing.signature_key' => 'test-cache-signing-secret',
        ]);
        foreach (glob(base_path('vendor/kernelbridge/licensing-client-laravel/database/migrations/*.php')) as $path) {
            (require $path)->up();
        }
        $this->mock(DeviceIdentity::class, function ($mock): void {
            $mock->shouldReceive('fingerprint')->andReturn(str_repeat('a', 64));
            $mock->shouldReceive('information')->andReturn(['hostname' => 'CLINIC-01']);
        });
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_installation_admin')->default(false);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
        (require database_path('migrations/2026_10_01_000001_create_installation_onboarding.php'))->up();
        (require database_path('migrations/2026_09_28_000001_create_system_settings_table.php'))->up();
        (require database_path('migrations/2026_09_30_000002_link_installation_administrator.php'))->up();
        (require database_path('migrations/2026_09_30_000003_add_welcome_appearance_to_system_settings.php'))->up();
        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->timestamps();
        });
        Schema::create('role_user', function (Blueprint $table): void {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
        });
        $this->cache = app(LicenseCacheService::class);
    }

    public function test_client_mode_requires_activation_even_when_optional_flag_is_false(): void
    {
        $environment = RepositoryBuilder::createWithDefaultAdapters()
            ->addAdapter(PutenvAdapter::class)->make();
        $oldMode = $environment->get('APP_MODE');
        $oldFlag = $environment->get('MEDIFLOW_CENTRAL_LICENSING');
        try {
            $environment->set('APP_MODE', 'client');
            $environment->set('MEDIFLOW_CENTRAL_LICENSING', 'false');
            config(['central_licensing' => require config_path('central_licensing.php')]);
            $this->assertTrue(config('central_licensing.enabled'));
            (new CentralLicensingServiceProvider($this->app))->register();
            $this->assertInstanceOf(CentralLicenseService::class, app(LicenseService::class));
            $this->get('/')->assertRedirect(route('kernelbridge.license.show'));
            $this->get('/license')->assertOk();
            $environment->set('APP_MODE', 'standalone');
            $this->assertFalse((require config_path('central_licensing.php'))['enabled']);
        } finally {
            $oldMode === null ? $environment->clear('APP_MODE') : $environment->set('APP_MODE', $oldMode);
            $oldFlag === null ? $environment->clear('MEDIFLOW_CENTRAL_LICENSING') : $environment->set('MEDIFLOW_CENTRAL_LICENSING', $oldFlag);
        }
    }

    public function test_server_url_is_normalized_saved_and_untrusted_address_is_rejected(): void
    {
        Http::fake();
        $this->post('/license', ['license_key' => 'test', 'server_url' => 'https://untrusted.example'])->assertSessionHasErrors('server_url');
        Http::assertNothingSent();
        Http::swap(new Factory);
        $this->fakeActivation();
        $this->post('/license', ['license_key' => 'KBT-TEST', 'server_url' => 'https://licensing.test'])->assertRedirect(route('installation.setup'));
        $this->assertSame('https://licensing.test/api/v1', Storage::disk('local')->get('licensing/server-url.txt'));
    }

    public function test_central_activation_uses_package_dashboard_without_legacy_license_record(): void
    {
        $this->activate();
        $this->app->bind(LicenseService::class, CentralLicenseService::class);
        $this->mock(SidebarService::class, function ($mock): void {
            $mock->shouldReceive('groupsFor')->twice()->andReturn([]);
        });
        // No legacy licence or hospital data tables exist in this fixture.
        $this->assertSame('components.installation-dashboard', (new \App\Livewire\Admin\Dashboard)->render()->name());
        $this->assertSame('components.installation-dashboard', (new Dashboard)->render()->name());
    }

    public function test_admin_routes_still_require_activated_features(): void
    {
        $this->activate();
        $service = new CentralLicenseService($this->cache);
        $this->assertFalse($service->routeEnabled('admin.wards.index'));
        $this->assertFalse($service->routeEnabled('admin.investigations.index'));
        $this->assertFalse($service->routeEnabled('admin.bills.investigations.store'));
        $this->assertFalse($service->routeEnabled('admin.sync.index'));
        $this->assertTrue($service->routeEnabled('admin.users.index'));
        $this->assertTrue($service->routeEnabled('admin.installation'));
        $this->app->instance(LicenseService::class, $service);
        $request = Request::create('/admin/wards');
        $route = new Route('GET', 'admin/wards', fn () => null);
        $route->name('admin.wards.index');
        $request->setRouteResolver(fn () => $route);
        $this->expectException(HttpException::class);
        (new EnsureInstallationModules)->handle($request, fn () => new Response('must not run'));
    }

    public function test_purchased_packages_and_modules_replace_unlicensed_local_presets(): void
    {
        $this->activate();
        $service = new CentralLicenseService($this->cache);
        $this->assertSame('pharmacy', $service->currentPlan());
        $this->assertSame(['pharmacy', 'general_clinic'], $service->selectedPackages());
        $this->assertTrue($service->moduleEnabled('pharmacy'));
        $this->assertTrue($service->moduleEnabled('doctor'));
        $this->assertFalse($service->moduleEnabled('maternity'));
    }

    public function test_expired_or_copied_cache_cannot_unlock_modules(): void
    {
        $this->activate();
        $this->mock(DeviceIdentity::class, function ($mock): void {
            $mock->shouldReceive('fingerprint')->andReturn(str_repeat('b', 64));
        });
        $this->assertFalse((new CentralLicenseService($this->cache))->moduleEnabled('pharmacy'));
        $this->assertFalse($this->cache->hasUsableLicense());
    }

    public function test_unlicensed_api_request_is_blocked_and_activation_is_reachable(): void
    {
        $middleware = new EnsureCentralLicense;
        $response = $middleware->handle(Request::create('/license'), fn () => new Response('activate'));
        $this->assertSame('activate', $response->getContent());
        $this->expectException(HttpException::class);
        try {
            $middleware->handle(Request::create('/api/v1/sync'), fn () => new Response('should not run'));
        } catch (HttpException $exception) {
            $this->assertSame(402, $exception->getStatusCode());
            throw $exception;
        }
    }

    public function test_outage_respects_offline_grace_and_expired_grace_is_rejected(): void
    {
        $this->activate();
        DB::table('installation_onboarding')->update(['completed_at' => now()]);
        Http::fake(['*' => Http::response([], 503)]);
        config(['kernelbridge-licensing.verification_interval_minutes' => 0]);
        $middleware = new EnsureCentralLicense;
        $response = $middleware->handle(Request::create('/dashboard'), fn () => new Response('allowed'));
        $this->assertSame('allowed', $response->getContent());
        $this->travel(73)->hours();
        $this->assertFalse($this->cache->hasUsableLicense());
        $this->assertSame(302, $middleware->handle(Request::create('/dashboard'), fn () => new Response('blocked'))->getStatusCode());
    }

    public function test_public_activation_and_protected_refresh(): void
    {
        $this->get('/license')->assertOk();
        $this->post('/license/verify')->assertRedirect(route('login'));
        $user = new User(['name' => 'Ordinary', 'email' => 'ordinary@example.test']);
        $user->id = 1;
        $this->actingAs($user)->post('/license/verify')->assertForbidden();
    }

    public function test_first_run_activation_setup_and_completion(): void
    {
        $this->get('/')->assertRedirect(route('kernelbridge.license.show'));
        $this->fakeActivation();
        $this->post('/license', ['license_key' => 'KBT-TEST'])->assertRedirect(route('installation.setup'));
        $this->get('/installation/setup')->assertOk()->assertSee('Pharmacy')->assertSee('Dispense safely');
        $this->post('/installation/setup', $this->setupData())->assertRedirect('/');
        $admin = User::firstOrFail();
        $this->assertTrue($admin->is_installation_admin);
        $this->assertTrue(Hash::check('very-strong-password', $admin->password));
        $this->assertAuthenticatedAs($admin);
        $this->assertDatabaseHas('system_settings', ['id' => 1, 'brand_name' => 'Example Clinic']);
        $this->assertTrue(app(InstallationOnboarding::class)->completed());
        $response = (new EnsureCentralLicense)->handle(Request::create('/dashboard'), fn () => new Response('ready'));
        $this->assertSame('ready', $response->getContent());
        $this->post('/installation/setup', $this->setupData())->assertStatus(409);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_cache_without_browser_grant_cannot_create_first_admin(): void
    {
        $this->activate();
        $this->get('/installation/setup')->assertForbidden();
        $this->post('/installation/setup', $this->setupData())->assertForbidden();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_expired_bootstrap_grant_cannot_create_admin_and_can_reopen_activation(): void
    {
        $this->fakeActivation();
        $this->post('/license', ['license_key' => 'KBT-TEST'])->assertRedirect(route('installation.setup'));
        $this->travel(31)->minutes();
        $this->get('/license')->assertOk();
        $this->post('/installation/setup', $this->setupData())->assertForbidden();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_existing_admin_prevents_public_reactivation_takeover(): void
    {
        User::withoutEvents(function (): void {
            $user = new User(['name' => 'Owner', 'email' => 'owner@example.test', 'password' => 'old-password']);
            $user->is_installation_admin = true;
            $user->save();
        });
        Http::fake();
        $this->post('/license', ['license_key' => 'KBT-TEST'])->assertRedirect(route('login'));
        Http::assertNothingSent();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_refresh_replaces_signed_package_configuration_without_recreating_setup(): void
    {
        $this->fakeActivation();
        $this->post('/license', ['license_key' => 'KBT-TEST']);
        $this->post('/installation/setup', $this->setupData())->assertRedirect('/');
        Http::swap(new Factory);
        Http::fake(['*' => Http::response(['success' => true, 'data' => [
            'license_uuid' => 'license-test', 'subscription_uuid' => 'subscription-test',
            'activation_uuid' => 'activation-test', 'expires_at' => now()->addYear()->toISOString(),
            'configuration' => ['revision' => 2, 'packages' => [['key' => 'general_clinic', 'name' => 'General Clinic', 'features' => []]]],
            'subscription' => ['status' => 'active', 'ends_at' => now()->addYear()->toISOString(),
                'primary_package' => 'general_clinic', 'selected_packages' => ['general_clinic']],
            'entitlements' => ['entitlements' => [['key' => 'core', 'enabled' => true], ['key' => 'doctor', 'enabled' => true]]],
        ]])]);
        $this->post('/license/verify')->assertRedirect(route('admin.installation'));
        $this->assertSame(2, $this->cache->state()->entitlement_payload['configuration']['revision']);
        $service = new CentralLicenseService($this->cache);
        $this->assertTrue($service->moduleEnabled('doctor'));
        $this->assertFalse($service->moduleEnabled('pharmacy'));
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('system_settings', ['brand_name' => 'Example Clinic']);
    }

    private function setupData(): array
    {
        return ['brand_name' => 'Example Clinic', 'welcome_template' => 'auto',
            'admin_name' => 'Owner', 'admin_email' => 'owner@example.test',
            'password' => 'very-strong-password', 'password_confirmation' => 'very-strong-password'];
    }

    private function fakeActivation(): void
    {
        Http::fake(['*' => Http::response(['success' => true, 'data' => [
            'license_uuid' => 'license-test', 'subscription_uuid' => 'subscription-test',
            'activation_uuid' => 'activation-test', 'expires_at' => now()->addYear()->toISOString(),
            'configuration' => ['revision' => 1, 'packages' => [[
                'key' => 'pharmacy', 'name' => 'Pharmacy', 'description' => 'Dispense safely',
                'features' => [['key' => 'pharmacy', 'name' => 'Pharmacy', 'description' => 'Manage medicines', 'required' => false]],
            ]]],
            'subscription' => ['status' => 'active', 'ends_at' => now()->addYear()->toISOString(),
                'primary_package' => 'pharmacy', 'selected_packages' => ['pharmacy']],
            'entitlements' => ['entitlements' => [['key' => 'core', 'enabled' => true], ['key' => 'pharmacy', 'enabled' => true]]],
        ]])]);
    }

    private function activate(): void
    {
        $this->cache->storeVerified('KBT-TEST', [
            'license_uuid' => 'license-test', 'subscription_uuid' => 'subscription-test',
            'activation_uuid' => 'activation-test', 'expires_at' => now()->addYear()->toISOString(),
        ], [
            'status' => 'active', 'ends_at' => now()->addYear()->toISOString(),
            'primary_package' => 'pharmacy', 'selected_packages' => ['pharmacy', 'general_clinic'],
        ], [
            'entitlements' => [
                ['key' => 'core', 'enabled' => true], ['key' => 'access_control', 'enabled' => true],
                ['key' => 'pharmacy', 'enabled' => true], ['key' => 'doctor', 'enabled' => true],
                ['key' => 'maternity', 'enabled' => false],
            ],
        ]);
    }
}
