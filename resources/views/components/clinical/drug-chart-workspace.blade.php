<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h1 class="h4 mb-1">Drug Chart</h1><p class="text-muted mb-0">{{ $patient->name() }} | {{ $patient->hospital_number }}</p></div>
        <a href="{{ route('patient.show', $patient) }}" class="btn btn-outline-secondary">Back</a>
    </div>
    @include('components.clinical._feedback')
    <div class="row g-3">
        <div class="col-lg-5"><div class="card border-0 shadow-sm"><div class="card-body">
            <form wire:submit.prevent="save">
                <div class="mb-3"><label class="form-label">Prescription Item</label><select class="form-select" wire:model="prescriptionItemId">
                    <option value="">Select medicine</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">{{ $item->medicine?->name }} | {{ $item->dosage }} | {{ $item->route?->name }}</option>
                    @endforeach
                </select></div>
                <div class="mb-3"><label class="form-label">Dosage Given</label><input class="form-control" wire:model="dosage"></div>
                <div class="mb-3"><label class="form-label">Date</label><input type="date" class="form-control" wire:model="date" /></div>
                <div class="mb-3"><label class="form-label">Time</label><input type="time" class="form-control" wire:model="time" /></div>

                <div class="mb-3"><label class="form-label">Comment</label><textarea class="form-control" wire:model="comment"></textarea></div>
                <div class="d-flex gap-2">
                    <button class="btn btn-success">{{ $editingId ? 'Update Drug Chart' : 'Record Drug Chart' }}</button>
                    @if($editingId)
                        <button type="button" class="btn btn-outline-secondary" wire:click="cancelEdit">Cancel</button>
                    @endif
                </div>
            </form>
        </div></div></div>
        <div class="col-lg-7"><div class="card border-0 shadow-sm"><div class="table-responsive">
            <table class="table table-hover mb-0"><thead class="table-light"><tr><th>Date</th><th>Time</th><th>Medicine</th><th>Dosage</th><th>Comment</th><th></th></tr></thead><tbody>
                @forelse($recent as $chart)
                    <tr>
                        <td>{{ date('Y-m-d', strtotime($chart->date)) }}</td>
                        <td>{{ date('h:i:s A', strtotime($chart->time)) }}</td>
                        <td>{{ $chart->medicine?->name }}</td>
                        <td>{{ $chart->dosage }}</td>
                        <td>{{ $chart->comment }}</td>
                        <td class="text-end"><button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $chart->id }})">Edit</button></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No drug chart entries yet.</td></tr>
                @endforelse
            </tbody></table>
        </div></div></div>
    </div>
</div>
