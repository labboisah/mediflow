<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h1 class="h4 mb-1">Fluid Balance</h1><p class="text-muted mb-0">{{ $patient->name() }} | {{ $patient->hospital_number }}</p></div>
        <a href="{{ route('patient.show', $patient) }}" class="btn btn-outline-secondary">Back</a>
    </div>
    @include('components.clinical._feedback')
    @unless($admission)
        <div class="alert alert-warning">A confirmed admission is required before recording fluid balance.</div>
    @endunless
    <div class="row g-3">
        <div class="col-lg-5"><div class="card border-0 shadow-sm"><div class="card-body">
            <form wire:submit.prevent="save">
                <div class="row g-2">
                    <div class="col-md-6"><label class="form-label">Date</label><input type="date" class="form-control" wire:model="form.date"></div>
                    <div class="col-md-6"><label class="form-label">Time</label><input type="time" class="form-control" wire:model="form.time"></div>
                    @foreach(['type_in' => 'Type In', 'tube_in' => 'Tube In', 'oral' => 'Oral', 'iv' => 'IV', 'type_out' => 'Type Out', 'tube_out' => 'Tube Out', 'urine' => 'Urine', 'faces' => 'Faeces'] as $field => $label)
                        <div class="col-md-6"><label class="form-label">{{ $label }}</label><input class="form-control" wire:model="form.{{ $field }}"></div>
                    @endforeach
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-success">{{ $editingId ? 'Update Fluid Balance' : 'Save Fluid Balance' }}</button>
                    @if($editingId)
                        <button type="button" class="btn btn-outline-secondary" wire:click="cancelEdit">Cancel</button>
                    @endif
                </div>
            </form>
        </div></div></div>
        <div class="col-lg-7"><div class="card border-0 shadow-sm"><div class="table-responsive">
            <table class="table table-hover mb-0"><thead class="table-light"><tr><th>Date</th><th>Time</th><th>Oral</th><th>IV</th><th>Urine</th><th>Faeces</th><th></th></tr></thead><tbody>
                @forelse($recent as $item)
                    <tr>
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->time }}</td>
                        <td>{{ $item->oral }}</td>
                        <td>{{ $item->iv }}</td>
                        <td>{{ $item->urine }}</td>
                        <td>{{ $item->faces }}</td>
                        <td class="text-end"><button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $item->id }})">Edit</button></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No fluid balance entries yet.</td></tr>
                @endforelse
            </tbody></table>
        </div></div></div>
    </div>
</div>
