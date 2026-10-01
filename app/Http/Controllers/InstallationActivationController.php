<?php

namespace App\Http\Controllers;

use App\Services\InstallationOnboarding;
use App\Services\LicensingConnection;
use Illuminate\Http\Request;
use KernelBridge\LicensingClient\Exceptions\KernelBridgeApiException;
use KernelBridge\LicensingClient\Exceptions\KernelBridgeUnavailableException;
use KernelBridge\LicensingClient\Services\LicenseActivationService;
use KernelBridge\LicensingClient\Services\LicenseCacheService;
use KernelBridge\LicensingClient\Services\LicenseVerificationService;

class InstallationActivationController extends Controller
{
    public function show(Request $request, LicenseCacheService $cache, InstallationOnboarding $onboarding)
    {
        if ($cache->hasUsableLicense() && ($onboarding->completed() || $onboarding->hasAdministrator() || $onboarding->hasGrant($request))) {
            return redirect($onboarding->completed() ? '/' : route('installation.setup'));
        }

        return view('installation.activation', [
            'needsLogin' => $onboarding->hasAdministrator() && ! $request->user()?->is_installation_admin,
        ]);
    }

    public function activate(Request $request, LicenseActivationService $activation, LicenseCacheService $cache, InstallationOnboarding $onboarding)
    {
        if ($onboarding->hasAdministrator()) {
            if (! $request->user()) {
                return redirect()->guest(route('login'));
            }
            abort_unless($request->user()->is_installation_admin, 403);
        }
        $data = $request->validate(['license_key' => ['required', 'string', 'max:255'], 'device_name' => ['nullable', 'string', 'max:255'], 'server_url' => ['sometimes', 'required', 'url:http,https', 'max:2048']]);
        $connection = app(LicensingConnection::class);
        $serverUrl = $connection->select($data['server_url'] ?? null);
        try {
            $activation->activate($data['license_key'], $data['device_name'] ?? config('app.name'));
            if (! $cache->hasUsableLicense()) {
                return back()->withErrors(['license_key' => 'The subscription is not active. Settle its invoice and try again.']);
            }
        } catch (KernelBridgeUnavailableException $exception) {
            return back()->withInput($request->only('server_url', 'device_name'))->withErrors(['server_url' => 'The licensing service at '.$serverUrl.' could not complete the request. Check the address and that KernelBridge is running. The server may also be returning an internal error.']);
        } catch (KernelBridgeApiException $exception) {
            return back()->withErrors(['license_key' => $exception->getMessage()]);
        } catch (\LogicException $exception) {
            return back()->withErrors(['license_key' => 'The installation licensing connection is not configured. Ask the deployment administrator to configure the KernelBridge API credential.']);
        }
        $connection->save($serverUrl);
        $onboarding->grant($request);

        return redirect($onboarding->completed() ? '/' : route('installation.setup'));
    }

    public function refresh(Request $request, LicenseVerificationService $verification)
    {
        abort_unless($request->user()?->is_installation_admin, 403);
        try {
            $state = $verification->verify(true);
        } catch (KernelBridgeApiException|KernelBridgeUnavailableException $exception) {
            return back()->withErrors(['license_key' => $exception->getMessage()]);
        }

        return redirect()->route('admin.installation')->with('kernelbridge_license_status', 'Subscription checked. Last verified: '.$state->last_successful_verification_at?->toDateTimeString().'. Cached access is retained during a temporary connection outage.');
    }

    public function deactivate(Request $request, LicenseVerificationService $verification)
    {
        abort_unless($request->user()?->is_installation_admin, 403);
        $verification->deactivate();

        return redirect()->route('kernelbridge.license.show');
    }
}
