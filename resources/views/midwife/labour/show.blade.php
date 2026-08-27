@extends('layouts.app')

@section('title', 'Labour Record - ' . $labour->patient->full_name)

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="bi bi-file-earmark-medical"></i>
                Labour Record
            </h1>

            <small class="text-muted">
                {{ $labour->patient->name() ?? 'N/A' }} -
                {{ $labour->patient->hospital_number ?? 'N/A' }} -
                {{ $labour->created_at->format('M d, Y') }}
            </small>
        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('midwife.labour.edit', $labour) }}"
               class="btn btn-outline-warning btn-sm">

                <i class="bi bi-pencil"></i>
                Edit
            </a>

            <a href="{{ route('midwife.labour.index') }}"
               class="btn btn-outline-secondary btn-sm">

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
                    <small class="text-muted">Hospital Number</small>
                    <p class="fw-bold">
                        {{ $labour->patient->hospital_number }}
                    </p>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">Patient Name</small>
                    <p class="fw-bold">
                        {{ $labour->patient->name() }}
                    </p>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">Age</small>
                    <p class="fw-bold">
                        {{ $labour->patient->age() }} years
                    </p>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">Gender</small>
                    <p class="fw-bold">
                        {{ $labour->patient->demographic->gender }}
                    </p>
                </div>

            </div>

        </div>

    </div>

    <!-- Labour Information -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="bi bi-calendar-event"></i>
                Labour Information
            </h6>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">
                    <small class="text-muted">
                        Labour Onset Time
                    </small>

                    <p class="fw-bold">
                        {{ optional($labour->labour_onset_time)->format('M d, Y h:i A') ?? 'N/A' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">
                        Mode of Onset
                    </small>

                    <p class="fw-bold text-capitalize">
                        {{ $labour->mode_of_onset ?? 'N/A' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">
                        Gestational Weeks
                    </small>

                    <p class="fw-bold">
                        {{ $labour->gestational_weeks ?? 'N/A' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">
                        Labour Type
                    </small>

                    <p class="fw-bold">
                        {{ $labour->labour_type ?? 'N/A' }}
                    </p>
                </div>

                @if($labour->reason_for_induction)
                    <div class="col-md-12">
                        <small class="text-muted">
                            Reason for Induction
                        </small>

                        <p class="fw-bold">
                            {{ $labour->reason_for_induction }}
                        </p>
                    </div>
                @endif

                @if($labour->previous_obstetric_history)
                    <div class="col-md-12">
                        <small class="text-muted">
                            Previous Obstetric History
                        </small>

                        <p>
                            {{ $labour->previous_obstetric_history }}
                        </p>
                    </div>
                @endif

            </div>

        </div>

    </div>

    <!-- Pre Labour Assessment -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="bi bi-clipboard-pulse"></i>
                Pre-Labour Assessment
            </h6>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">
                    <small class="text-muted">
                        Cervical State
                    </small>

                    <p class="fw-bold">
                        {{ $labour->cervical_state ?? 'N/A' }}
                    </p>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">
                        Show
                    </small>

                    <p class="fw-bold text-capitalize">
                        {{ $labour->show ?? 'N/A' }}
                    </p>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">
                        Rupture of Membranes
                    </small>

                    <p class="fw-bold text-capitalize">
                        {{ $labour->rupture_of_membranes ?? 'N/A' }}
                    </p>
                </div>

                @if($labour->liquor)
                    <div class="col-md-12">
                        <small class="text-muted">
                            Liquor
                        </small>

                        <p>
                            {{ $labour->liquor }}
                        </p>
                    </div>
                @endif

            </div>

        </div>

    </div>

    <!-- Maternal Vital Signs -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="bi bi-heart-pulse"></i>
                Maternal Vital Signs
            </h6>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">
                    <small class="text-muted">
                        Blood Pressure
                    </small>

                    <p class="fw-bold">
                        {{ $labour->blood_pressure ?? 'N/A' }}
                    </p>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">
                        Pulse Rate
                    </small>

                    <p class="fw-bold">
                        {{ $labour->pulse_rate ?? 'N/A' }} bpm
                    </p>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">
                        Temperature
                    </small>

                    <p class="fw-bold">
                        {{ $labour->temperature ?? 'N/A' }} °C
                    </p>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">
                        Respiration Rate
                    </small>

                    <p class="fw-bold">
                        {{ $labour->respiration_rate ?? 'N/A' }}
                    </p>
                </div>

            </div>

        </div>

    </div>

    <!-- Labour Progress -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="bi bi-diagram-3"></i>
                Labour Progress
            </h6>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">
                    <small class="text-muted">
                        Stage
                    </small>

                    <p>
                        <span class="badge bg-primary">
                            {{ str_replace('_', ' ', ucfirst($labour->stage)) }}
                        </span>
                    </p>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">
                        Status
                    </small>

                    <p>
                        <span class="badge bg-success">
                            {{ ucfirst($labour->status) }}
                        </span>
                    </p>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">
                        First Stage Started
                    </small>

                    <p class="fw-bold">
                        {{ optional($labour->first_stage_started_at)->format('M d, Y h:i A') ?? 'N/A' }}
                    </p>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">
                        Second Stage Started
                    </small>

                    <p class="fw-bold">
                        {{ optional($labour->second_stage_started_at)->format('M d, Y h:i A') ?? 'N/A' }}
                    </p>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">
                        Third Stage Started
                    </small>

                    <p class="fw-bold">
                        {{ optional($labour->third_stage_started_at)->format('M d, Y h:i A') ?? 'N/A' }}
                    </p>
                </div>

            </div>

        </div>

    </div>

    <!-- Fetal Monitoring -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="bi bi-heart-fill"></i>
                Fetal Monitoring
            </h6>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">
                    <small class="text-muted">
                        Fetal Heart Rate
                    </small>

                    <p class="fw-bold">
                        {{ $labour->fetal_heart_rate ?? 'N/A' }} bpm
                    </p>
                </div>

                @if($labour->fetal_monitoring_notes)
                    <div class="col-md-12">
                        <small class="text-muted">
                            Monitoring Notes
                        </small>

                        <p>
                            {{ $labour->fetal_monitoring_notes }}
                        </p>
                    </div>
                @endif

            </div>

        </div>

    </div>

    <!-- Complications -->
    @if($labour->complications)

        <div class="card mb-3 shadow-sm">

            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-exclamation-triangle"></i>
                    Complications
                </h6>
            </div>

            <div class="card-body">

                <p>
                    {{ $labour->complications }}
                </p>

            </div>

        </div>

    @endif

    <!-- Clinical Notes -->
    @if($labour->clinical_notes)

        <div class="card mb-3 shadow-sm">

            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-journal-medical"></i>
                    Clinical Notes
                </h6>
            </div>

            <div class="card-body">

                <p>
                    {{ $labour->clinical_notes }}
                </p>

            </div>

        </div>

    @endif

    <!-- Footer Metadata -->
    <div class="card shadow-sm bg-light">

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">
                    <small class="text-muted">
                        Created At
                    </small>

                    <p class="fw-bold">
                        {{ $labour->created_at->format('M d, Y h:i A') }}
                    </p>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">
                        Last Updated
                    </small>

                    <p class="fw-bold">
                        {{ $labour->updated_at->format('M d, Y h:i A') }}
                    </p>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">
                        Recorded By
                    </small>

                    <p class="fw-bold">
                        {{ $labour->recordedBy->name ?? 'N/A' }}
                    </p>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">
                        Record ID
                    </small>

                    <p class="fw-bold">
                        #{{ $labour->id }}
                    </p>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection