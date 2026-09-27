<div>
    <x-ui.page title="Drug Chart" subtitle="{{ $patient->name() }} | {{ $patient->hospital_number }}">
        <x-slot:actions>
            <a href="{{ route('patient.show', $patient) }}" class="mf-focus inline-flex items-center justify-center rounded-md border border-med-line bg-white px-3 py-2 text-sm font-semibold text-med-ink transition hover:bg-med-canvas">Back</a>
        </x-slot:actions>

        @include('components.clinical._feedback')

        <div class="grid gap-5 xl:grid-cols-[minmax(0,0.85fr)_minmax(0,1.15fr)]">
            <x-ui.card :title="$editingId ? 'Update Drug Chart' : 'Record Drug Chart'" subtitle="Record medication administration for started prescription items.">
                <form wire:submit.prevent="save" class="space-y-5">
                    <div>
                        <x-ui.select label="Prescription Item" wire:model="prescriptionItemId">
                            <option value="">Select medicine</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->medicine?->name }} | {{ $item->dosage }} | {{ $item->route?->name }}</option>
                            @endforeach
                        </x-ui.select>
                        @error('prescriptionItemId')<p class="mt-1 text-sm text-med-danger">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.input label="Dosage Given" wire:model="dosage" />
                        <x-ui.input label="Date" type="date" wire:model="date" />
                        <x-ui.input label="Time" type="time" wire:model="time" />
                    </div>
                    @error('dosage')<p class="-mt-3 text-sm text-med-danger">{{ $message }}</p>@enderror

                    <x-ui.textarea label="Comment" rows="4" wire:model="comment" />

                    <div class="flex flex-wrap items-center gap-2">
                        <x-ui.button type="submit" variant="primary">{{ $editingId ? 'Update Drug Chart' : 'Record Drug Chart' }}</x-ui.button>
                        @if($editingId)
                            <x-ui.button type="button" variant="secondary" wire:click="cancelEdit">Cancel</x-ui.button>
                        @endif
                    </div>
                </form>
            </x-ui.card>

            <x-ui.card title="Recent Drug Chart" subtitle="Latest medication administration records for this visit.">
                <x-ui.table>
                    <thead class="bg-med-canvas/80 text-left text-xs font-semibold uppercase tracking-wide text-med-muted">
                        <tr><th class="px-4 py-3">Date</th><th class="px-4 py-3">Time</th><th class="px-4 py-3">Medicine</th><th class="px-4 py-3">Dosage</th><th class="px-4 py-3">Comment</th><th class="px-4 py-3 text-right">Action</th></tr>
                    </thead>
                    <tbody class="divide-y divide-med-line bg-white">
                        @forelse($recent as $chart)
                            <tr class="hover:bg-med-canvas/50">
                                <td class="px-4 py-3 text-med-ink">{{ $chart->date ? date('Y-m-d', strtotime($chart->date)) : 'N/A' }}</td>
                                <td class="px-4 py-3 text-med-muted">{{ $chart->time ? date('h:i A', strtotime($chart->time)) : 'N/A' }}</td>
                                <td class="px-4 py-3 text-med-muted">{{ $chart->medicine?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-med-muted">{{ $chart->dosage ?? 'N/A' }}</td>
                                <td class="max-w-xs px-4 py-3 text-med-muted">{{ $chart->comment ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-right"><button type="button" class="mf-focus rounded-md border border-med-line px-3 py-1.5 text-sm font-semibold text-med-primary transition hover:bg-med-canvas" wire:click="edit({{ $chart->id }})">Edit</button></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-8"><x-ui.empty-state title="No Drug Chart" message="No drug chart entries have been recorded for this active visit." /></td></tr>
                        @endforelse
                    </tbody>
                </x-ui.table>
            </x-ui.card>
        </div>
    </x-ui.page>
</div>
