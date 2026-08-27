@extends('layouts.app')

@section('title', 'Labour Progress Record')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="bi bi-eye"></i> Labour Progress Detail</h1>
            <small class="text-muted">Recorded at {{ $progress->recorded_at->format('M d, Y H:i') }}</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('midwife.labour-progress.index', $progress->labour) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Progress
            </a>
            <a href="{{ route('midwife.labour-progress.edit', $progress) }}" class="btn btn-outline-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6"><strong>Recorded By:</strong> {{ $progress->recordedBy->name ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Labour ID:</strong> {{ $progress->labour_id }}</div>
            </div>
            <hr>
            <dl class="row">
                <dt class="col-sm-3">Contraction Frequency</dt>
                <dd class="col-sm-9">{{ $progress->contraction_frequency ?? 'N/A' }} /10m</dd>
                <dt class="col-sm-3">Contraction Duration</dt>
                <dd class="col-sm-9">{{ $progress->contraction_duration ?? 'N/A' }} secs</dd>
                <dt class="col-sm-3">Contraction Intensity</dt>
                <dd class="col-sm-9">{{ ucfirst($progress->contraction_intensity) ?? 'N/A' }}</dd>
                <dt class="col-sm-3">Cervical Dilation</dt>
                <dd class="col-sm-9">{{ $progress->cervical_dilation ?? 'N/A' }} cm</dd>
                <dt class="col-sm-3">Cervical Effacement</dt>
                <dd class="col-sm-9">{{ $progress->cervical_effacement ?? 'N/A' }}%</dd>
                <dt class="col-sm-3">Fetal HR</dt>
                <dd class="col-sm-9">{{ $progress->fetal_heart_rate ?? 'N/A' }} bpm</dd>
                <dt class="col-sm-3">BP</dt>
                <dd class="col-sm-9">{{ $progress->blood_pressure ?? 'N/A' }}</dd>
                <dt class="col-sm-3">Temp</dt>
                <dd class="col-sm-9">{{ $progress->temperature ?? 'N/A' }} °C</dd>
                <dt class="col-sm-3">Pulse</dt>
                <dd class="col-sm-9">{{ $progress->pulse_rate ?? 'N/A' }} bpm</dd>
                <dt class="col-sm-3">Observations</dt>
                <dd class="col-sm-9">{{ $progress->observations_and_notes ?? 'N/A' }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection