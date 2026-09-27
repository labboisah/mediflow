<div>
    <x-ui.page title="Prescription" subtitle="{{ $patient->name() }} | {{ $patient->hospital_number }}">
        <x-slot:actions>
            <a href="{{ route('patient.show', $patient) }}" class="mf-focus inline-flex items-center justify-center rounded-md border border-med-line bg-white px-3 py-2 text-sm font-semibold text-med-ink transition hover:bg-med-canvas">Back</a>
        </x-slot:actions>

        @include('components.clinical._feedback')

        <div class="grid gap-5 xl:grid-cols-[minmax(340px,0.85fr)_minmax(0,1.35fr)]">
            <x-ui.card :title="$editingItemId ? 'Update Medicine' : 'Add Medicine'" subtitle="Build the prescription for this active visit.">
                <form wire:submit.prevent="addItem" class="space-y-4">
                    <div>
                        <x-ui.textarea label="Treatment / Infection / Disease" rows="3" wire:model.live="treatmentDiagnosis" placeholder="Indicate diagnosis, infection, or disease being treated" />
                        @error('treatmentDiagnosis')<p class="mt-1 text-sm text-med-danger">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <x-ui.input label="Medicine" list="medicine-options" wire:model.live="medicineName" placeholder="Type or select medicine" />
                        <datalist id="medicine-options">
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->name }}" label="{{ $medicine->displayName() }}"></option>
                            @endforeach
                        </datalist>
                        @error('medicineName')<p class="mt-1 text-sm text-med-danger">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-med-muted">Existing medicines show stock status; type a new name to add one.</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.select label="Medicine Type" wire:model="medicineTypeId">
                            <option value="">Select type</option>
                            @foreach($medicineTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </x-ui.select>
                        <div>
                            <x-ui.select label="Route" wire:model="routeId">
                                <option value="">Select route</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}">{{ $route->name }}</option>
                                @endforeach
                            </x-ui.select>
                            @error('routeId')<p class="mt-1 text-sm text-med-danger">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div><x-ui.input label="Dosage" wire:model="dosage" />@error('dosage')<p class="mt-1 text-sm text-med-danger">{{ $message }}</p>@enderror</div>
                        <div><x-ui.input label="Period" wire:model="period" />@error('period')<p class="mt-1 text-sm text-med-danger">{{ $message }}</p>@enderror</div>
                        <div><x-ui.input label="Duration" wire:model="duration" />@error('duration')<p class="mt-1 text-sm text-med-danger">{{ $message }}</p>@enderror</div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <x-ui.button type="submit" variant="primary">{{ $editingItemId ? 'Update Medicine' : 'Add Medicine' }}</x-ui.button>
                        @if($editingItemId)
                            <x-ui.button type="button" variant="secondary" wire:click="cancelEdit">Cancel</x-ui.button>
                        @endif
                    </div>
                </form>
            </x-ui.card>

            <x-ui.card title="Prescription Items" subtitle="Medicines, stock status, administration status, and total prescription amount.">
                <x-slot:actions>
                    <x-ui.button type="button" variant="primary" wire:click="submitPrescription">Submit to Pharmacy</x-ui.button>
                </x-slot:actions>

                <x-ui.table>
                    <thead class="bg-med-canvas/80 text-left text-xs font-semibold uppercase tracking-wide text-med-muted">
                        <tr><th class="px-4 py-3">Medicine</th><th class="px-4 py-3">Stock</th><th class="px-4 py-3 text-right">Amount</th><th class="px-4 py-3">Route</th><th class="px-4 py-3">Dosage</th><th class="px-4 py-3">Period</th><th class="px-4 py-3">Duration</th><th class="px-4 py-3">Status</th><th class="px-4 py-3 text-right">Actions</th></tr>
                    </thead>
                    <tbody class="divide-y divide-med-line bg-white">
                        @forelse($prescription?->prescriptionItems ?? [] as $item)
                            @php($quantity = $item->medicine?->availableQuantity() ?? 0)
                            <tr class="hover:bg-med-canvas/50" wire:key="prescription-item-{{ $item->id }}">
                                <td class="px-4 py-3"><p class="font-semibold text-med-ink">{{ $item->medicine?->name ?? 'N/A' }}</p>@if($item->medicine?->generic_name)<p class="text-xs text-med-muted">{{ $item->medicine->generic_name }}</p>@endif</td>
                                <td class="px-4 py-3"><x-ui.badge :variant="$quantity > 0 ? 'success' : 'danger'">{{ $quantity > 0 ? 'Available: ' . $quantity : 'Not available' }}</x-ui.badge></td>
                                <td class="px-4 py-3 text-right font-semibold text-med-ink">&#8358;{{ number_format($item->medicine?->latestSellingPrice() ?? 0, 2) }}</td>
                                <td class="px-4 py-3 text-med-muted">{{ $item->route?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-med-muted">{{ $item->dosage }}</td>
                                <td class="px-4 py-3 text-med-muted">{{ $item->period }}</td>
                                <td class="px-4 py-3 text-med-muted">{{ $item->duration }}</td>
                                <td class="px-4 py-3"><x-ui.badge :variant="$item->isStarted() ? 'success' : 'neutral'">{{ $item->isStarted() ? 'Started' : 'Stopped' }}</x-ui.badge>@if($item->medication_status_changed_at)<p class="mt-1 text-xs text-med-muted">{{ $item->medication_status_changed_at->format('M d, h:i A') }}</p>@endif</td>
                                <td class="px-4 py-3 text-right">
                                    @if(auth()->user()->hasRole('doctor'))
                                        <div class="flex flex-wrap justify-end gap-1.5">
                                            <button type="button" class="mf-focus rounded-md border border-med-line px-2.5 py-1.5 text-xs font-semibold text-med-primary transition hover:bg-med-canvas" wire:click="editItem({{ $item->id }})">Edit</button>
                                            <button type="button" class="mf-focus rounded-md border border-red-200 px-2.5 py-1.5 text-xs font-semibold text-med-danger transition hover:bg-red-50" wire:click="removeItem({{ $item->id }})" wire:confirm="Delete this medicine from the prescription?">Delete</button>
                                            @if($item->isStarted())
                                                <button type="button" class="mf-focus rounded-md border border-orange-200 px-2.5 py-1.5 text-xs font-semibold text-orange-700 transition hover:bg-orange-50" wire:click="stopMedication({{ $item->id }})">Stop</button>
                                            @else
                                                <button type="button" class="mf-focus rounded-md border border-green-200 px-2.5 py-1.5 text-xs font-semibold text-med-primary transition hover:bg-green-50" wire:click="startMedication({{ $item->id }})">Start</button>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="px-4 py-8"><x-ui.empty-state title="No Medicine Added" message="Add at least one medicine to prepare this prescription." /></td></tr>
                        @endforelse
                    </tbody>
                    @if($prescription?->prescriptionItems?->count())
                        <tfoot class="bg-med-canvas/60">
                            <tr><th colspan="2" class="px-4 py-3 text-right text-sm font-semibold text-med-ink">Prescription Amount</th><th class="px-4 py-3 text-right text-sm font-semibold text-med-ink">&#8358;{{ number_format($prescription->prescriptionItems->sum(fn($item) => $item->medicine?->latestSellingPrice() ?? 0), 2) }}</th><th colspan="6"></th></tr>
                        </tfoot>
                    @endif
                </x-ui.table>
            </x-ui.card>
        </div>
    </x-ui.page>
</div>
