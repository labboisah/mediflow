<div>
    <x-ui.page title="Investigation Request" subtitle="{{ $patient->name() }} | {{ $patient->hospital_number }}">
        <x-slot:actions>
            <a href="{{ route('patient.show', $patient) }}" class="mf-focus inline-flex items-center justify-center rounded-md border border-med-line bg-white px-3 py-2 text-sm font-semibold text-med-ink transition hover:bg-med-canvas">Back</a>
        </x-slot:actions>

        @include('components.clinical._feedback')

        <div class="grid gap-5 xl:grid-cols-[minmax(0,1.15fr)_minmax(360px,0.85fr)]">
            <x-ui.card :title="$editingRequestId ? 'Update Investigation Request' : 'Register Investigation Request'" subtitle="Create investigation requests and generate one accumulated bill.">
                <form wire:submit.prevent="save" class="space-y-5">
                    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_160px]">
                        <div>
                            <x-ui.textarea label="Clinical Diagnosis" rows="4" wire:model="clinicalDiagnoses" />
                            @error('clinicalDiagnoses')<p class="mt-1 text-sm text-med-danger">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <x-ui.input label="Discount (%)" type="number" min="0" max="100" wire:model="discount" />
                            @error('discount')<p class="mt-1 text-sm text-med-danger">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="space-y-3">
                        @foreach($rows as $index => $row)
                            <div class="rounded-md border border-med-line p-4" wire:key="investigation-row-{{ $index }}">
                                <div class="grid gap-4 lg:grid-cols-[minmax(160px,0.9fr)_minmax(220px,1.2fr)_minmax(140px,0.8fr)_44px]">
                                    <x-ui.select label="Type" wire:model.live="rows.{{ $index }}.type_id">
                                        <option value="">Select type</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </x-ui.select>
                                    <x-ui.select label="Investigation" wire:model="rows.{{ $index }}.investigation_id">
                                        <option value="">Select investigation</option>
                                        @foreach($investigations->where('investigation_type_id', $row['type_id']) as $investigation)
                                            <option value="{{ $investigation->id }}">{{ $investigation->name }} - &#8358;{{ number_format((float) $investigation->price, 2) }}</option>
                                        @endforeach
                                    </x-ui.select>
                                    <x-ui.input label="Specimen" wire:model="rows.{{ $index }}.specimen" />
                                    <div class="flex items-end">
                                        <button type="button" class="mf-focus inline-flex h-10 w-10 items-center justify-center rounded-md border border-red-200 bg-white text-med-danger transition hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-50" wire:click="removeRow({{ $index }})" @disabled($editingRequestId) aria-label="Remove investigation row">&times;</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <x-ui.button type="button" variant="secondary" wire:click="addRow" :disabled="$editingRequestId">Add Investigation</x-ui.button>
                        <x-ui.button type="submit" variant="primary">{{ $editingRequestId ? 'Update Request & Bill' : 'Register Request & Bill' }}</x-ui.button>
                        @if($editingRequestId)
                            <x-ui.button type="button" variant="secondary" wire:click="cancelEdit">Cancel</x-ui.button>
                        @endif
                    </div>
                </form>
            </x-ui.card>

            <x-ui.card title="Recent Requests" subtitle="Latest investigation requests for this active visit.">
                <div class="space-y-3">
                    @forelse($recentRequests as $request)
                        <div class="rounded-md border border-med-line p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-med-ink">{{ $request->investigation?->name ?? 'Investigation' }}</p>
                                    <p class="mt-1 text-sm text-med-muted">{{ $request->lab_no ?? 'Pending lab number' }} | Bill: {{ $request->bill?->bill_number ?? 'N/A' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-med-ink">&#8358;{{ number_format((float) ($request->bill?->due_amount ?? $request->investigation?->price ?? 0), 2) }}</p>
                                    <x-ui.badge :variant="$request->bill?->status === 'paid' ? 'success' : 'warning'">{{ ucfirst($request->bill?->status ?? 'pending') }}</x-ui.badge>
                                </div>
                            </div>
                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                <button type="button" class="mf-focus rounded-md border border-med-line px-3 py-1.5 text-sm font-semibold text-med-primary transition hover:bg-med-canvas" wire:click="editRequest({{ $request->id }})">Edit</button>
                                <button type="button" class="mf-focus rounded-md border border-red-200 px-3 py-1.5 text-sm font-semibold text-med-danger transition hover:bg-red-50" wire:click="deleteRequest({{ $request->id }})" wire:confirm="Remove this investigation request and update the bill?">Remove</button>
                            </div>
                        </div>
                    @empty
                        <x-ui.empty-state title="No Recent Requests" message="No investigation requests have been recorded for this active visit." />
                    @endforelse
                </div>
            </x-ui.card>
        </div>
    </x-ui.page>
</div>

