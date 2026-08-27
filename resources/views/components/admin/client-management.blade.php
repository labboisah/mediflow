<div>
    <x-ui.page :title="$pageTitle" :subtitle="$pageSubtitle">
        <x-slot:actions>
            <x-ui.button type="button" wire:click="createClient">
                <i class="bi bi-plus-circle"></i>
                New Client
            </x-ui.button>
        </x-slot:actions>

        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <x-ui.card>
                <p class="text-base font-medium text-med-muted">Clients</p>
                <p class="mt-3 text-4xl font-semibold leading-tight text-med-ink">{{ number_format($summary['clients']) }}</p>
                <p class="mt-2 text-base text-med-muted">Total records</p>
            </x-ui.card>

            <x-ui.card>
                <p class="text-base font-medium text-med-muted">Active</p>
                <p class="mt-3 text-4xl font-semibold leading-tight text-med-ink">{{ number_format($summary['active']) }}</p>
                <p class="mt-2 text-base text-med-muted">Live customers</p>
            </x-ui.card>

            <x-ui.card>
                <p class="text-base font-medium text-med-muted">Prospects</p>
                <p class="mt-3 text-4xl font-semibold leading-tight text-med-ink">{{ number_format($summary['prospects']) }}</p>
                <p class="mt-2 text-base text-med-muted">Sales pipeline</p>
            </x-ui.card>

            <x-ui.card>
                <p class="text-base font-medium text-med-muted">Licensed</p>
                <p class="mt-3 text-4xl font-semibold leading-tight text-med-ink">{{ number_format($summary['licensed']) }}</p>
                <p class="mt-2 text-base text-med-muted">With license records</p>
            </x-ui.card>
        </div>

        <div class="grid gap-6 xl:grid-cols-12">
            <x-ui.card class="xl:col-span-5" title="Clients">
                <x-slot:actions>
                    <div class="w-64 max-w-full">
                        <x-ui.input placeholder="Search clients..." wire:model.live.debounce.300ms="search" />
                    </div>
                </x-slot:actions>

                <div class="max-h-[620px] divide-y divide-med-line overflow-y-auto">
                    @forelse($clients as $client)
                        @php($latestLicense = $client->licenses->first())
                        <button type="button"
                                wire:click="selectClient({{ $client->id }})"
                                class="block w-full px-1 py-4 text-left transition hover:bg-med-canvas {{ $clientId === $client->id ? 'bg-green-50' : '' }}">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-med-ink">{{ $client->name }}</p>
                                    <p class="mt-1 text-sm text-med-muted">
                                        {{ $sectors[$client->sector] ?? 'No sector' }}
                                        @if($client->city || $client->state)
                                            - {{ collect([$client->city, $client->state])->filter()->implode(', ') }}
                                        @endif
                                    </p>
                                    <p class="mt-1 text-sm text-med-muted">{{ $client->contact_person ?: $client->email ?: 'No contact yet' }}</p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <x-ui.badge :variant="$client->status === 'active' ? 'success' : ($client->status === 'suspended' ? 'danger' : 'neutral')">
                                        {{ $statuses[$client->status] ?? str($client->status)->headline() }}
                                    </x-ui.badge>
                                    <p class="mt-2 text-xs text-med-muted">{{ $client->licenses_count }} {{ \Illuminate\Support\Str::plural('license', $client->licenses_count) }}</p>
                                </div>
                            </div>

                            @if($latestLicense)
                                <div class="mt-3 rounded-md border border-med-line bg-white px-3 py-2 text-xs text-med-muted">
                                    Latest: {{ str($latestLicense->plan)->replace('_', ' ')->headline() }}
                                    @if($latestLicense->expires_at)
                                        expires {{ $latestLicense->expires_at->format('M j, Y') }}
                                    @else
                                        with no expiry
                                    @endif
                                </div>
                            @endif
                        </button>
                    @empty
                        <x-ui.empty-state title="No clients found" message="Create the first client profile to start tracking licenses and activations." />
                    @endforelse
                </div>
            </x-ui.card>

            <x-ui.card class="xl:col-span-7" title="{{ $clientId ? 'Client Profile' : 'New Client' }}" subtitle="Client records describe the organization. License records control activated modules.">
                <form wire:submit.prevent="save" class="space-y-5">
                    <div class="grid gap-4 md:grid-cols-2">
                        <x-ui.input label="Client Name" wire:model.defer="name" />

                        <x-ui.select label="Sector" wire:model.defer="sector">
                            <option value="">Select sector</option>
                            @foreach($sectors as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </x-ui.select>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <x-ui.input label="Contact Person" wire:model.defer="contactPerson" />
                        <x-ui.input label="Email" type="email" wire:model.defer="email" />
                    </div>

                    <div class="rounded-md border border-med-line bg-med-canvas p-4">
                        <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-med-ink">Default Administrator Login</h3>
                                <p class="text-sm text-med-muted">These credentials can be copied during activation for the client's first hospital admin user.</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <x-ui.button type="button" variant="secondary" wire:click="generateAdministratorPassword">
                                    <i class="bi bi-magic"></i>
                                    Generate
                                </x-ui.button>
                                <button type="button"
                                        class="mf-focus inline-flex items-center justify-center gap-2 rounded-md border border-med-line bg-white px-3 py-2 text-sm font-semibold text-med-ink transition hover:bg-med-primary/5 hover:text-med-primary"
                                        x-data
                                        @click="navigator.clipboard?.writeText(`Email: ${$wire.administratorEmail || ''}\nPassword: ${$wire.administratorPassword || ''}`)"
                                        title="Copy login"
                                        aria-label="Copy login">
                                    <i class="bi bi-clipboard-check"></i>
                                    Copy Login
                                </button>
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <x-ui.input label="Administrator Email" type="email" wire:model.defer="administratorEmail" />

                            <div>
                                <label class="mb-1 block text-sm font-medium text-med-ink">Administrator Password</label>
                                <div class="flex gap-2">
                                    <input type="text"
                                           class="mf-focus block w-full rounded-md border border-med-line px-3 py-2.5 text-sm text-med-ink shadow-sm"
                                           wire:model.defer="administratorPassword"
                                           placeholder="Default login password">
                                    <button type="button"
                                            class="mf-focus inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-md border border-med-line bg-white text-med-muted transition hover:bg-med-primary/5 hover:text-med-primary"
                                            x-data
                                            @click="navigator.clipboard?.writeText($wire.administratorPassword || '')"
                                            title="Copy password"
                                            aria-label="Copy password">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        <x-ui.input label="Phone" wire:model.defer="phone" />
                        <x-ui.input label="City" wire:model.defer="city" />
                        <x-ui.input label="State" wire:model.defer="state" />
                    </div>

                    <x-ui.select label="Status" wire:model.defer="status">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.textarea label="Notes" rows="5" wire:model.defer="notes" />

                    <div class="flex flex-wrap gap-3 border-t border-med-line pt-5">
                        <x-ui.button type="submit">
                            <i class="bi bi-check-circle"></i>
                            Save Client
                        </x-ui.button>

                        @if($clientId)
                            <x-ui.button type="button" variant="secondary" wire:click="setStatus({{ $clientId }}, 'active')">Mark Active</x-ui.button>
                            <x-ui.button type="button" variant="ghost" wire:click="setStatus({{ $clientId }}, 'suspended')">Suspend</x-ui.button>
                        @endif
                    </div>
                </form>

                @if($selectedClient)
                    <div class="mt-8 border-t border-med-line pt-6">
                        <div class="mb-4 flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-semibold text-med-ink">License Records</h3>
                                <p class="text-sm text-med-muted">Recent activations connected to this client.</p>
                            </div>
                            <a href="{{ route('admin.license-management') }}" class="mf-btn mf-btn-secondary">
                                <i class="bi bi-patch-check"></i>
                                Manage License
                            </a>
                        </div>

                        <div class="divide-y divide-med-line rounded-md border border-med-line">
                            @forelse($selectedClient->licenses->sortByDesc('updated_at') as $license)
                                <div class="grid gap-4 p-4 md:grid-cols-4">
                                    <div>
                                        <p class="text-xs font-medium uppercase text-med-muted">Plan</p>
                                        <p class="mt-1 font-semibold text-med-ink">{{ str($license->plan)->replace('_', ' ')->headline() }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium uppercase text-med-muted">Status</p>
                                        <p class="mt-1">
                                            <x-ui.badge :variant="$license->is_active ? 'success' : 'neutral'">{{ $license->is_active ? 'Active' : 'Inactive' }}</x-ui.badge>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium uppercase text-med-muted">Features</p>
                                        <p class="mt-1 font-semibold text-med-ink">{{ $license->enabledModules->where('is_enabled', true)->count() }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium uppercase text-med-muted">Expiry</p>
                                        <p class="mt-1 font-semibold text-med-ink">{{ $license->expires_at?->format('M j, Y') ?? 'No expiry' }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6">
                                    <x-ui.empty-state title="No license records" message="Use License Management to create an activation for this client." />
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </x-ui.card>
        </div>
    </x-ui.page>
</div>
