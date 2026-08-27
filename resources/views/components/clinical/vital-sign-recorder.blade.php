<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h1 class="h4 mb-1">Vital Signs</h1><p class="text-muted mb-0">{{ $patient->name() }} | {{ $patient->hospital_number }}</p></div>
        <a href="{{ route('patient.show', $patient) }}" class="btn btn-outline-secondary">Back</a>
    </div>
    @include('components.clinical._feedback')
    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <form wire:submit.prevent="save">
                    <div class="row g-2">
                        @foreach([
                            'body_temperature' => 'Temp',
                            'blood_pressure_systolic' => 'BP Systolic (optional for children)',
                            'blood_pressure_diastolic' => 'BP Diastolic (optional for children)',
                            'heart_rate' => 'Heart Rate',
                            'respiratory_rate' => 'Resp. Rate',
                            'oxygen_saturation' => 'Oxygen %',
                            'blood_glucose' => 'Glucose',
                            'weight' => 'Weight',
                            'height' => 'Height',
                        ] as $field => $label)
                            <div class="col-md-4">
                                <label class="form-label">{{ $label }}</label>
                                <input type="number" step="0.01" class="form-control @error('form.' . $field) is-invalid @enderror" wire:model="form.{{ $field }}">
                                @error('form.' . $field) <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3"><label class="form-label">Recorded Date</label><input type="datetime-local" class="form-control" wire:model="form.recorded_date"></div>
                    <div class="mt-3"><label class="form-label">Notes</label><textarea class="form-control" wire:model="form.notes"></textarea></div>
                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-success">{{ $editingId ? 'Update Vital Signs' : 'Save Vital Signs' }}</button>
                        @if($editingId)
                            <button type="button" class="btn btn-outline-secondary" wire:click="cancelEdit">Cancel</button>
                        @endif
                    </div>
                </form>
            </div></div>
        </div>
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm"><div class="table-responsive">
                <table class="table table-hover mb-0"><thead class="table-light"><tr><th>Date</th><th>Temp</th><th>BP</th><th>HR</th><th>SpO2</th><th></th></tr></thead><tbody>
                    @forelse($recent as $vital)
                        <tr>
                            <td>{{ $vital->recorded_date }}</td>
                            <td>{{ $vital->body_temperature }}</td>
                            <td>{{ $vital->blood_pressure_systolic && $vital->blood_pressure_diastolic ? $vital->blood_pressure_systolic . '/' . $vital->blood_pressure_diastolic : 'N/A' }}</td>
                            <td>{{ $vital->heart_rate }}</td>
                            <td>{{ $vital->oxygen_saturation }}</td>
                            <td class="text-end"><button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $vital->id }})">Edit</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No vital signs recorded yet.</td></tr>
                    @endforelse
                </tbody></table>
            </div></div>
        </div>
    </div>
</div>
