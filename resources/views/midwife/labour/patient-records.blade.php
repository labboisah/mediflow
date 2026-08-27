@extends('layouts.app')

@section('title', 'Labour Records - ' . $patient->full_name)

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-file-earmark-medical"></i>
                Labour Records
            </h1>

            <small class="text-muted">
                Complete labour history for {{ $patient->name() }}
            </small>

        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('midwife.labour.create', $patient) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                New Record

            </a>

            <a href="{{ route('midwife.labour.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

    </div>

    <!-- Patient Information -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="bi bi-person-badge"></i>
                Patient Information
            </h6>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">
                    <small class="text-muted">
                        Hospital Number
                    </small>

                    <p class="fw-bold">
                        {{ $patient->hospital_number }}
                    </p>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">
                        Patient Name
                    </small>

                    <p class="fw-bold">
                        {{ $patient->name() }}
                    </p>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">
                        Age
                    </small>

                    <p class="fw-bold">
                        {{ $patient->age() }} years
                    </p>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">
                        Phone Number
                    </small>

                    <p class="fw-bold">
                        {{ $patient->demographic->phone_number ?? 'N/A' }}
                    </p>
                </div>

            </div>

        </div>

    </div>

    @if($labours->isEmpty())

        <div class="alert alert-info">

            <i class="bi bi-info-circle"></i>

            No labour records found for this patient.

        </div>

    @else

        <!-- Summary Table -->
        <div class="card mb-4 shadow-sm">

            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-table"></i>
                    Labour Records Summary
                </h6>
            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Date</th>
                            <th>Onset</th>
                            <th>Stage</th>
                            <th>Status</th>
                            <th>BP</th>
                            <th>FHR</th>
                            <th>Temperature</th>
                            <th>Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($labours as $labour)

                            <tr>

                                <td>

                                    <strong>
                                        {{ optional($labour->labour_onset_time)->format('M d, Y') }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ optional($labour->labour_onset_time)->format('h:i A') }}
                                    </small>

                                </td>

                                <td>

                                    <span class="badge bg-info text-capitalize">
                                        {{ $labour->mode_of_onset ?? 'N/A' }}
                                    </span>

                                </td>

                                <td>

                                    <span class="badge bg-primary">
                                        {{ str_replace('_', ' ', ucfirst($labour->stage)) }}
                                    </span>

                                </td>

                                <td>

                                    @if($labour->status == 'ongoing')

                                        <span class="badge bg-warning">
                                            Ongoing
                                        </span>

                                    @elseif($labour->status == 'completed')

                                        <span class="badge bg-success">
                                            Completed
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Complicated
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    {{ $labour->blood_pressure ?? '-' }}

                                </td>

                                <td>

                                    {{ $labour->fetal_heart_rate ?? '-' }} bpm

                                </td>

                                <td>

                                    {{ $labour->temperature ?? '-' }} °C

                                </td>

                                <td>

                                    <div class="btn-group btn-group-sm">

                                        <a href="{{ route('midwife.labour.show', $labour) }}"
                                           class="btn btn-outline-primary">

                                            <i class="bi bi-eye"></i>

                                        </a>

                                        <a href="{{ route('midwife.labour.edit', $labour) }}"
                                           class="btn btn-outline-warning">

                                            <i class="bi bi-pencil"></i>

                                        </a>

                                        <form action="{{ route('midwife.labour.destroy', $labour) }}"
                                              method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-outline-danger"
                                                    onclick="return confirm('Delete this labour record?')">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        <!-- Timeline -->
        <div class="card shadow-sm">

            <div class="card-header bg-light">

                <h6 class="mb-0">
                    <i class="bi bi-clock-history"></i>
                    Labour Timeline
                </h6>

            </div>

            <div class="card-body">

                <div class="timeline">

                    @foreach($labours as $index => $labour)

                        <div class="timeline-item">

                            <div class="timeline-marker">

                                <span class="badge bg-primary">
                                    {{ $index + 1 }}
                                </span>

                            </div>

                            <div class="timeline-content">

                                <div class="card shadow-sm">

                                    <div class="card-header bg-light">

                                        <div class="d-flex justify-content-between">

                                            <div>

                                                <strong>
                                                    {{ optional($labour->labour_onset_time)->format('M d, Y h:i A') }}
                                                </strong>

                                            </div>

                                            <div>

                                                <small class="text-muted">

                                                    Recorded By:
                                                    {{ $labour->recordedBy->name ?? 'N/A' }}

                                                </small>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-md-3">

                                                <small class="text-muted">
                                                    Mode of Onset
                                                </small>

                                                <p class="fw-bold text-capitalize">
                                                    {{ $labour->mode_of_onset ?? 'N/A' }}
                                                </p>

                                            </div>

                                            <div class="col-md-3">

                                                <small class="text-muted">
                                                    Stage
                                                </small>

                                                <p>
                                                    <span class="badge bg-primary">
                                                        {{ str_replace('_', ' ', ucfirst($labour->stage)) }}
                                                    </span>
                                                </p>

                                            </div>

                                            <div class="col-md-3">

                                                <small class="text-muted">
                                                    Status
                                                </small>

                                                <p>
                                                    <span class="badge bg-success">
                                                        {{ ucfirst($labour->status) }}
                                                    </span>
                                                </p>

                                            </div>

                                            <div class="col-md-3">

                                                <small class="text-muted">
                                                    Gestational Weeks
                                                </small>

                                                <p class="fw-bold">
                                                    {{ $labour->gestational_weeks ?? 'N/A' }}
                                                </p>

                                            </div>

                                        </div>

                                        <hr>

                                        <div class="row">

                                            <div class="col-md-4">

                                                <small class="text-muted">
                                                    Blood Pressure
                                                </small>

                                                <p class="fw-bold">
                                                    {{ $labour->blood_pressure ?? 'N/A' }}
                                                </p>

                                            </div>

                                            <div class="col-md-4">

                                                <small class="text-muted">
                                                    Pulse Rate
                                                </small>

                                                <p class="fw-bold">
                                                    {{ $labour->pulse_rate ?? 'N/A' }}
                                                </p>

                                            </div>

                                            <div class="col-md-4">

                                                <small class="text-muted">
                                                    Fetal Heart Rate
                                                </small>

                                                <p class="fw-bold">
                                                    {{ $labour->fetal_heart_rate ?? 'N/A' }} bpm
                                                </p>

                                            </div>

                                        </div>

                                        @if($labour->complications)

                                            <hr>

                                            <div class="alert alert-warning mb-0">

                                                <strong>
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                    Complications
                                                </strong>

                                                <br>

                                                {{ $labour->complications }}

                                            </div>

                                        @endif

                                    </div>

                                    <div class="card-footer bg-light text-end">

                                        <a href="{{ route('midwife.labour.show', $labour) }}"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="bi bi-eye"></i>
                                            View Record

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    @endif

</div>

<style>

.timeline {
    position: relative;
}

.timeline-item {
    display: flex;
    margin-bottom: 30px;
}

.timeline-marker {
    width: 50px;
    margin-right: 20px;
    position: relative;
}

.timeline-marker::after {
    content: '';
    position: absolute;
    top: 50px;
    left: 24px;
    width: 2px;
    height: calc(100% + 10px);
    background: #dee2e6;
}

.timeline-item:last-child .timeline-marker::after {
    display: none;
}

.timeline-content {
    flex: 1;
}

</style>

@endsection