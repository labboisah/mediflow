<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h1 class="h4 mb-1">Continuation Sheet</h1><p class="text-muted mb-0">{{ $patient->name() }} | {{ $patient->hospital_number }}</p></div>
        <a href="{{ route('patient.show', $patient) }}" class="btn btn-outline-secondary">Back</a>
    </div>
    @include('components.clinical._feedback')
    <div class="row g-3">
        <div class="col-lg-7"><div class="card border-0 shadow-sm"><div class="card-body">
            <form wire:submit.prevent="save">
                <label class="form-label">Clinical Note</label>
                <textarea rows="3" class="form-control @error('notes') is-invalid @enderror" wire:model="notes"></textarea>
                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <!-- clinical history -->
                <label class="form-label">Cliniacal History</label>
                <textarea rows="3" class="form-control @error('history') is-invalid @enderror" wire:model="history"></textarea>
                @error('history') <div class="invalid-feedback">{{ $message }}</div> @enderror
                
                <!-- clinical examination -->
                <label class="form-label">Clinical Examinataion</label>
                <textarea rows="3" class="form-control @error('examination') is-invalid @enderror" wire:model="examination"></textarea>
                @error('examination') <div class="invalid-feedback">{{ $message }}</div> @enderror

                <!-- clinical diagnoses -->

                <label class="form-label">Clinical Diagnoses</label>
                <textarea rows="3" class="form-control @error('diagnose') is-invalid @enderror" wire:model="diagnose"></textarea>
                @error('diagnose') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <!-- clinical plan -->

                <label class="form-label">Clinical Plan</label>
                <textarea rows="3" class="form-control @error('plan') is-invalid @enderror" wire:model="plan"></textarea>
                @error('plan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-success">{{ $editingId ? 'Update Note' : 'Save Note' }}</button>
                    @if($editingId)
                        <button type="button" class="btn btn-outline-secondary" wire:click="cancelEdit">Cancel</button>
                    @endif
                </div>
            </form>
        </div></div></div>
        <div class="col-lg-5"><div class="card border-0 shadow-sm"><div class="list-group list-group-flush">
            @php 
                $vitalSign = $patient->currentVisit()->vitalSigns()->latest()->first();
            @endphp
            <div class="p-4">
                @if($vitalSign)
                
                <h4>Last Vital Signs Taken</h4>
                <div class="row mb-3">
                     <p class="mb-0 text-muted">
                        Visit on:
                    <strong class="text-success">{{ date('M d, Y',strtotime($patient->currentVisit()->visit_date))  ?? 'No Visit Recorded' }} @ {{ date('h:s A',strtotime($patient->currentVisit()->created_at))}}</strong>
                    </p>
                    <hr>
                    <div class="col-md-2">
                        <label class="form-label text-muted">By</label>
                        <p class="h6">{{ $vitalSign->recordedBy->name }}</p>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted">Date</label>
                        <p class="h6">{{ $vitalSign->recorded_date->format('M d, Y') }}</p>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted">Temp</label>
                        <p class="h6">{{ $vitalSign->body_temperature ?? 'N/A' }}°C</p>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted">BP</label>
                        <p class="h6">{{ $vitalSign->blood_pressure_systolic ?? 'N/A' }}/{{ $vitalSign->blood_pressure_diastolic ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted">HR</label>
                        <p class="h6">{{ $vitalSign->heart_rate ?? 'N/A' }} bpm</p>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted">Resp.</label>
                        <p class="h6">{{ $vitalSign->respiratory_rate ?? 'N/A' }} </p>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted">Ox</label>
                        <p class="h6">{{ $vitalSign->oxygen_saturation ?? 'N/A' }} </p>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted">Blood</label>
                        <p class="h6">{{ $vitalSign->blood_glucose ?? 'N/A' }} </p>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted">Weight</label>
                        <p class="h6">{{ $vitalSign->weight ?? 'N/A' }} </p>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted">Height</label>
                        <p class="h6">{{ $vitalSign->height ?? 'N/A' }} </p>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted">Remark</label>
                        <p class="h6">{{ $vitalSign->notes ?? 'N/A' }} </p>
                    </div>
                    @if(auth()->user()->hasRole('nurse') || auth()->user()->hasRole('midwife'))
                        <div class="col-md-12">
                            <a href="{{ route('patient.vitalsign.create', $patient) }}" class="btn btn-sm btn-outline-primary">Edit in Vital Signs</a>
                        </div>
                    @endif
                </div>
                @else
                <h4>No vital signs recorded for this visit</h4>
                @endif
            </div>
            <hr>
            <h4 class="p-4">Recent Notes</h4>
            @forelse($recent as $note)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between gap-2">
                        <div class="small text-muted">{{ $note->created_at }}</div>
                        <button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $note->id }})">Edit</button>
                    </div>
                    <p><b>Notes</b>: {{ $note->note }}</p>
                    <p><b>History</b>: {{ $note->history }}</p>
                    <p><b>Examination</b>: {{ $note->examination }}</p>
                    <p><b>Diagnose</b>: {{ $note->diagnose }}</p>
                    <p><b>Plan</b>: {{ $note->plan }}</p>
                </div>
            @empty
                <div class="list-group-item text-muted">No continuation notes yet.</div>
            @endforelse
            
        </div></div>
    
    </div>
    </div>
</div>
