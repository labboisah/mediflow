<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Discharge</h1>
            <p class="text-muted mb-0">{{ $admission->patientVisit->patient->name() }} | {{ $admission->patientVisit->patient->hospital_number }}</p>
        </div>
        <a href="{{ route('patient.show', $admission->patientVisit->patient) }}" class="btn btn-outline-secondary">Back</a>
    </div>
    @include('components.clinical._feedback')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Date</label><input type="date" class="form-control" wire:model="date"></div>
                    <div class="col-md-4"><label class="form-label">Time</label><input type="time" class="form-control" wire:model="time"></div>
                    <div class="col-md-4"><label class="form-label">Next Appointment</label><input type="date" class="form-control" wire:model="nextAppointmentDate"></div>
                    <div class="col-12"><label class="form-label">Reason / Summary</label><textarea rows="6" class="form-control @error('reason') is-invalid @enderror" wire:model="reason"></textarea>@error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                </div>
                <button class="btn btn-success mt-3">{{ $admission->discharge ? 'Update Discharge Summary' : 'Discharge Patient' }}</button>
            </form>
        </div>
    </div>
</div>
