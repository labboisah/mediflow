<div>
    <x-ui.page :title="$pageTitle" :subtitle="$pageSubtitle">
        <x-slot:actions>
            <x-ui.button type="button" wire:click="createAgent">
                <i class="bi bi-plus-circle"></i>
                New Agent
            </x-ui.button>
        </x-slot:actions>

        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <x-ui.card>
                <p class="text-base font-medium text-med-muted">Agents</p>
                <p class="mt-3 text-4xl font-semibold leading-tight text-med-ink">{{ number_format($summary['agents']) }}</p>
                <p class="mt-2 text-base text-med-muted">Total records</p>
            </x-ui.card>

            <x-ui.card>
                <p class="text-base font-medium text-med-muted">Active</p>
                <p class="mt-3 text-4xl font-semibold leading-tight text-med-ink">{{ number_format($summary['active']) }}</p>
                <p class="mt-2 text-base text-med-muted">Currently selling</p>
            </x-ui.card>

            <x-ui.card>
                <p class="text-base font-medium text-med-muted">Partners</p>
                <p class="mt-3 text-4xl font-semibold leading-tight text-med-ink">{{ number_format($summary['partners']) }}</p>
                <p class="mt-2 text-base text-med-muted">Reseller and implementation</p>
            </x-ui.card>

            <x-ui.card>
                <p class="text-base font-medium text-med-muted">Suspended</p>
                <p class="mt-3 text-4xl font-semibold leading-tight text-med-ink">{{ number_format($summary['suspended']) }}</p>
                <p class="mt-2 text-base text-med-muted">Needs review</p>
            </x-ui.card>
        </div>

        <div class="grid gap-6 xl:grid-cols-12">
            <x-ui.card class="xl:col-span-5" title="Agents">
                <x-slot:actions>
                    <div class="w-64 max-w-full">
                        <x-ui.input placeholder="Search agents..." wire:model.live.debounce.300ms="search" />
                    </div>
                </x-slot:actions>

                <div class="max-h-[620px] divide-y divide-med-line overflow-y-auto">
                    @forelse($agents as $agent)
                        <button type="button"
                                wire:click="selectAgent({{ $agent->id }})"
                                class="block w-full px-1 py-4 text-left transition hover:bg-med-canvas {{ $agentId === $agent->id ? 'bg-green-50' : '' }}">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-med-ink">{{ $agent->name }}</p>
                                    <p class="mt-1 text-sm text-med-muted">{{ $types[$agent->type] ?? str($agent->type)->headline() }}</p>
                                    <p class="mt-1 text-sm text-med-muted">{{ $agent->organization ?: $agent->email ?: 'No organization yet' }}</p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <x-ui.badge :variant="$agent->status === 'active' ? 'success' : ($agent->status === 'suspended' ? 'danger' : 'neutral')">
                                        {{ $statuses[$agent->status] ?? str($agent->status)->headline() }}
                                    </x-ui.badge>
                                    @if($agent->commission_rate !== null)
                                        <p class="mt-2 text-xs text-med-muted">{{ number_format((float) $agent->commission_rate, 2) }}%</p>
                                    @endif
                                </div>
                            </div>
                        </button>
                    @empty
                        <x-ui.empty-state title="No agents found" message="Create the first sales agent or partner record." />
                    @endforelse
                </div>
            </x-ui.card>

            <x-ui.card class="xl:col-span-7" title="{{ $agentId ? 'Agent Profile' : 'New Agent' }}" subtitle="Agent records help track who sells, implements, or supports client activations.">
                <form wire:submit.prevent="save" class="space-y-5">
                    <div class="grid gap-4 md:grid-cols-2">
                        <x-ui.input label="Agent Name" wire:model.defer="name" />

                        <x-ui.select label="Type" wire:model.defer="type">
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </x-ui.select>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <x-ui.input label="Email" type="email" wire:model.defer="email" />
                        <x-ui.input label="Phone" wire:model.defer="phone" />
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <x-ui.input label="Organization" wire:model.defer="organization" />
                        <x-ui.input label="Commission Rate (%)" type="number" min="0" max="100" step="0.01" wire:model.defer="commissionRate" />
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
                            Save Agent
                        </x-ui.button>

                        @if($agentId)
                            <x-ui.button type="button" variant="secondary" wire:click="setStatus({{ $agentId }}, 'active')">Mark Active</x-ui.button>
                            <x-ui.button type="button" variant="ghost" wire:click="setStatus({{ $agentId }}, 'suspended')">Suspend</x-ui.button>
                        @endif
                    </div>
                </form>
            </x-ui.card>
        </div>
    </x-ui.page>
</div>
