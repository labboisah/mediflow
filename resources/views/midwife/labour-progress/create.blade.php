@extends('layouts.app')

@section('title', 'New Labour Progress - ' . $labour->patient->full_name)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="bi bi-plus-circle"></i> Add Labour Progress</h1>
            <small class="text-muted">Record new progress for labour of {{ $labour->patient->full_name }}</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('midwife.labour-progress.index', $labour) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <form action="{{ route('midwife.labour-progress.store', $labour) }}" method="POST">
        @csrf

        <div class="card mb-3">
            <div class="card-header bg-light"><strong>Progress Details</strong></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="recorded_at" class="form-label">Recorded At</label>
                        <input type="datetime-local" id="recorded_at" name="recorded_at" class="form-control @error('recorded_at') is-invalid @enderror" value="{{ old('recorded_at', now()->format('Y-m-d\TH:i')) }}">
                        @error('recorded_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="contraction_frequency" class="form-label">Contraction Frequency (/10 min)</label>
                        <input type="number" id="contraction_frequency" name="contraction_frequency" min="0" max="20" class="form-control @error('contraction_frequency') is-invalid @enderror" value="{{ old('contraction_frequency') }}">
                        @error('contraction_frequency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="contraction_duration" class="form-label">Contraction Duration (seconds)</label>
                        <input type="number" id="contraction_duration" name="contraction_duration" min="0" max="300" class="form-control @error('contraction_duration') is-invalid @enderror" value="{{ old('contraction_duration') }}">
                        @error('contraction_duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="cervical_dilation" class="form-label">Cervical Dilation (cm)</label>
                        <input type="number" id="cervical_dilation" name="cervical_dilation" min="0" max="10" step="0.1" class="form-control @error('cervical_dilation') is-invalid @enderror" value="{{ old('cervical_dilation') }}">
                        @error('cervical_dilation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="cervical_effacement" class="form-label">Cervical Effacement (%)</label>
                        <input type="number" id="cervical_effacement" name="cervical_effacement" min="0" max="100" class="form-control @error('cervical_effacement') is-invalid @enderror" value="{{ old('cervical_effacement') }}">
                        @error('cervical_effacement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="cervical_position" class="form-label">Cervical Position</label>
                        <select id="cervical_position" name="cervical_position" class="form-select @error('cervical_position') is-invalid @enderror">
                            <option value="">- select -</option>
                            <option value="posterior" {{ old('cervical_position')=='posterior' ? 'selected' : '' }}>Posterior</option>
                            <option value="middle" {{ old('cervical_position')=='middle' ? 'selected' : '' }}>Middle</option>
                            <option value="anterior" {{ old('cervical_position')=='anterior' ? 'selected' : '' }}>Anterior</option>
                        </select>
                        @error('cervical_position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="fetal_position" class="form-label">Fetal Position</label>
                        <select id="fetal_position" name="fetal_position" class="form-select @error('fetal_position') is-invalid @enderror">
                            <option value="">- select -</option>
                            <option value="cephalic" {{ old('fetal_position')=='cephalic' ? 'selected' : '' }}>Cephalic</option>
                            <option value="breech" {{ old('fetal_position')=='breech' ? 'selected' : '' }}>Breech</option>
                            <option value="oblique" {{ old('fetal_position')=='oblique' ? 'selected' : '' }}>Oblique</option>
                            <option value="transverse" {{ old('fetal_position')=='transverse' ? 'selected' : '' }}>Transverse</option>
                        </select>
                        @error('fetal_position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="fetal_station" class="form-label">Fetal Station (-5 to +5)</label>
                        <input type="number" id="fetal_station" name="fetal_station" min="-5" max="5" class="form-control @error('fetal_station') is-invalid @enderror" value="{{ old('fetal_station') }}">
                        @error('fetal_station')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="fetal_heart_rate" class="form-label">Fetal Heart Rate (bpm)</label>
                        <input type="number" id="fetal_heart_rate" name="fetal_heart_rate" min="100" max="190" class="form-control @error('fetal_heart_rate') is-invalid @enderror" value="{{ old('fetal_heart_rate') }}">
                        @error('fetal_heart_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="blood_pressure" class="form-label">Blood Pressure</label>
                        <input type="text" id="blood_pressure" name="blood_pressure" class="form-control @error('blood_pressure') is-invalid @enderror" value="{{ old('blood_pressure') }}" placeholder="120/80">
                        @error('blood_pressure')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="temperature" class="form-label">Temperature (°C)</label>
                        <input type="number" step="0.1" id="temperature" name="temperature" min="34" max="42" class="form-control @error('temperature') is-invalid @enderror" value="{{ old('temperature') }}">
                        @error('temperature')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pulse_rate" class="form-label">Pulse Rate (bpm)</label>
                        <input type="number" id="pulse_rate" name="pulse_rate" min="40" max="180" class="form-control @error('pulse_rate') is-invalid @enderror" value="{{ old('pulse_rate') }}">
                        @error('pulse_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="observations_and_notes" class="form-label">Observations and Notes</label>
                        <textarea id="observations_and_notes" name="observations_and_notes" rows="4" class="form-control @error('observations_and_notes') is-invalid @enderror">{{ old('observations_and_notes') }}</textarea>
                        @error('observations_and_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Progress</button>
                <a href="{{ route('midwife.labour-progress.index', $labour) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection