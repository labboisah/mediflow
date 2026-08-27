<div>
    <x-ui.page :title="$pageTitle" :subtitle="$pageSubtitle">
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach($plans as $planKey => $planConfig)
                <label class="cursor-pointer rounded-md border bg-white p-5 shadow-sm transition {{ $plan === $planKey ? 'border-med-primary bg-green-50' : 'border-med-line hover:bg-med-canvas' }}">
                    <div class="flex items-start gap-3">
                        <input type="radio"
                               class="mt-1 h-4 w-4 border-med-line text-med-primary"
                               value="{{ $planKey }}"
                               wire:model.live="plan">
                        <span>
                            <span class="block text-lg font-semibold text-med-ink">{{ $planConfig['label'] }}</span>
                            <span class="mt-2 block text-sm leading-6 text-med-muted">{{ $planConfig['description'] }}</span>
                        </span>
                    </div>
                </label>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-12">
            <x-ui.card class="xl:col-span-4" title="License Details" subtitle="The active license controls which feature modules are available in the system.">
                <form wire:submit.prevent="save" class="space-y-5">
                    @if($clients->isNotEmpty())
                        <x-ui.select label="Client" wire:model.live="clientId">
                            <option value="">Unlinked client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }} - {{ str($client->status)->headline() }}</option>
                            @endforeach
                        </x-ui.select>
                    @endif

                    <x-ui.input label="Client Name" wire:model.defer="clientName" />

                    <x-ui.select label="License Group" wire:model.live="plan">
                        @foreach($plans as $planKey => $planConfig)
                            <option value="{{ $planKey }}">{{ $planConfig['label'] }}</option>
                        @endforeach
                    </x-ui.select>

                    <div class="grid gap-3 md:grid-cols-2">
                        <x-ui.input label="Start Date" type="date" wire:model.defer="startsAt" />
                        <x-ui.input label="Expiry Date" type="date" wire:model.defer="expiresAt" />
                    </div>

                    @if($plan === 'enterprise_hospital')
                        <x-ui.input label="Branch Limit" type="number" min="1" wire:model.defer="branchLimit" />
                    @endif

                    <div>
                        <label class="mb-1 block text-sm font-medium text-med-ink">License Key</label>
                        <div class="flex gap-2">
                            <input type="text"
                                   class="mf-focus block w-full rounded-md border border-med-line px-3 py-2.5 text-sm text-med-ink shadow-sm"
                                   wire:model.defer="licenseKey"
                                   placeholder="Optional license key">
                            <x-ui.button type="button" variant="secondary" wire:click="generateLicenseKey">
                                <i class="bi bi-magic"></i>
                            </x-ui.button>
                        </div>
                        @error('licenseKey')
                            <span class="mt-1 block text-sm text-med-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <label class="flex items-center gap-3 rounded-md border border-med-line bg-white px-4 py-3 text-sm font-medium text-med-muted">
                        <input class="h-4 w-4 rounded border-med-line text-med-primary" type="checkbox" wire:model.live="isActive">
                        Active license
                    </label>

                    <div class="flex flex-wrap gap-3 border-t border-med-line pt-5">
                        <x-ui.button type="submit">
                            <i class="bi bi-check-circle"></i>
                            Save License
                        </x-ui.button>
                        <x-ui.button type="button" variant="secondary" wire:click="applyPlanPreset">
                            Use Preset
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>

            <x-ui.card class="xl:col-span-8" title="Feature Modules" subtitle="Modules checked here become available to users, then user module access and permissions decide who can see them.">
                <x-slot:actions>
                    <div class="flex gap-2">
                        <x-ui.button type="button" variant="secondary" wire:click="selectAllModules">Select All</x-ui.button>
                        <x-ui.button type="button" variant="ghost" wire:click="clearModules">Clear</x-ui.button>
                    </div>
                </x-slot:actions>

                @error('selectedModules')
                    <div class="mb-4 rounded-md border border-med-danger bg-white px-4 py-3 text-sm text-med-danger">{{ $message }}</div>
                @enderror

                <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($features as $feature)
                        <label class="flex cursor-pointer items-start gap-3 rounded-md border p-4 transition {{ $feature['enabled'] ? 'border-med-primary bg-green-50' : 'border-med-line bg-white hover:bg-med-canvas' }}">
                            <input class="mt-1 h-4 w-4 rounded border-med-line text-med-primary"
                                   type="checkbox"
                                   value="{{ $feature['key'] }}"
                                   wire:model.live="selectedModules">
                            <span>
                                <span class="block font-semibold text-med-ink">{{ $feature['label'] }}</span>
                                <span class="mt-1 block text-sm text-med-muted">
                                    {{ $feature['routes'] }} linked {{ \Illuminate\Support\Str::plural('screen', $feature['routes']) }}
                                </span>
                                @if($feature['included'])
                                    <span class="mt-2 inline-flex rounded-md bg-white px-2 py-1 text-xs font-semibold text-med-primary">Preset</span>
                                @endif
                            </span>
                        </label>
                    @endforeach
                </div>
            </x-ui.card>
        </div>

        <x-ui.card title="Current Active License">
            @if($currentLicense)
                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                    <div>
                        <p class="text-sm font-medium text-med-muted">Client</p>
                        <p class="mt-2 text-lg font-semibold text-med-ink">{{ $currentLicense->client_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-med-muted">Group</p>
                        <p class="mt-2 text-lg font-semibold text-med-ink">{{ $plans[$plan]['label'] ?? ucfirst(str_replace('_', ' ', $currentLicense->plan)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-med-muted">Validity</p>
                        <p class="mt-2 text-lg font-semibold text-med-ink">
                            {{ $currentLicense->starts_at?->format('M j, Y') ?? 'Open' }}
                            -
                            {{ $currentLicense->expires_at?->format('M j, Y') ?? 'No expiry' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-med-muted">Enabled Features</p>
                        <p class="mt-2 text-lg font-semibold text-med-ink">{{ count($enabledModules) }}</p>
                    </div>
                </div>

                @if($currentLicense->branch_limit)
                    <div class="mt-5 rounded-md border border-med-line bg-med-canvas px-4 py-3 text-sm text-med-muted">
                        Enterprise branch limit: <strong class="text-med-ink">{{ number_format($currentLicense->branch_limit) }}</strong>
                    </div>
                @endif
            @else
                <x-ui.empty-state title="No active license found" message="Save this form to create the first client license." />
            @endif
        </x-ui.card>
    </x-ui.page>
</div>
