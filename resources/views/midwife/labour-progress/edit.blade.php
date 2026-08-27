@extends('layouts.app')

@section('title', 'Edit Labour Progress')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="bi bi-pencil"></i> Edit Labour Progress</h1>
            <small class="text-muted">Progress record at {{ $progress->recorded_at->format('M d, Y H:i') }}</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('midwife.labour-progress.show', $progress) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <form action="{{ route('midwife.labour-progress.update', $progress) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="recorded_at">Recorded At</label>
                        <input type="datetime-local" id="recorded_at" name="recorded_at" class="form-control @error('recorded_at') is-invalid @enderror" value="{{ old('recorded_at', $progress->recorded_at->format('Y-m-d\TH:i')) }}">
                        @error('recorded_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="cervical_dilation">Cervical Dilation (cm)</label>
                        <input type="number" id="cervical_dilation" name="cervical_dilation" min="0" max="10" step="0.1" class="form-control @error('cervical_dilation') is-invalid @enderror" value="{{ old('cervical_dilation', $progress->cervical_dilation) }}">
                        @error('cervical_dilation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="cervical_effacement">Cervical Effacement (%)</label>
                        <input type="number" id="cervical_effacement" name="cervical_effacement" min="0" max="100" class="form-control @error('cervical_effacement') is-invalid @enderror" value="{{ old('cervical_effacement', $progress->cervical_effacement) }}">
                        @error('cervical_effacement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="contraction_frequency">Contraction Frequency (/10 min)</label>
                        <input type="number" id="contraction_frequency" name="contraction_frequency" min="0" max="20" class="form-control @error('contraction_frequency') is-invalid @enderror" value="{{ old('contraction_frequency', $progress->contraction_frequency) }}">
                        @error('contraction_frequency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="contraction_duration">Contraction Duration (seconds)</label>
                        <input type="number" id="contraction_duration" name="contraction_duration" min="0" max="300" class="form-control @error('contraction_duration') is-invalid @enderror" value="{{ old('contraction_duration', $progress->contraction_duration) }}">
                        @error('contraction_duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="fetal_heart_rate">Fetal Heart Rate (bpm)</label>
                        <input type="number" id="fetal_heart_rate" name="fetal_heart_rate" min="100" max="190" class="form-control @error('fetal_heart_rate') is-invalid @enderror" value="{{ old('fetal_heart_rate', $progress->fetal_heart_rate) }}">
                        @error('fetal_heart_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="observations_and_notes">Observations and Notes</label>
                    <textarea id="observations_and_notes" name="observations_and_notes" rows="4" class="form-control @error('observations_and_notes') is-invalid @enderror">{{ old('observations_and_notes', $progress->observations_and_notes) }}</textarea>
                    @error('observations_and_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Changes</button>
                <a href="{{ route('midwife.labour-progress.index', $progress->labour) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection