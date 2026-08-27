@extends('layouts.app')

@section('title', 'Delivery Record - ' . $delivery->patient->full_name)

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-heart-pulse"></i>
                Delivery Record
            </h1>

            <small class="text-muted">
                {{ $delivery->patient->name() }}
                -
                {{ optional($delivery->delivery_date_time)->format('M d, Y h:i A') }}
            </small>

        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('midwife.delivery.edit', $delivery) }}"
               class="btn btn-outline-warning">

                <i class="bi bi-pencil"></i>
                Edit

            </a>

            <a href="{{ $delivery->labour_id ? route('midwife.labour.show', $delivery->labour_id) : route('midwife.delivery-management', $delivery->patient) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                {{ $delivery->labour_id ? 'Back' : 'Delivery Management' }}

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
                        {{ $delivery->patient->hospital_number }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Patient Name
                    </small>

                    <p class="fw-bold">
                        {{ $delivery->patient->name() }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Age
                    </small>

                    <p class="fw-bold">
                        {{ $delivery->patient->age() }} years
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Delivery Status
                    </small>

                    <p>

                        @if($delivery->delivery_status == 'successful')

                            <span class="badge bg-success">
                                Successful
                            </span>

                        @elseif($delivery->delivery_status == 'complicated')

                            <span class="badge bg-warning">
                                Complicated
                            </span>

                        @elseif($delivery->delivery_status == 'maternal_death')

                            <span class="badge bg-danger">
                                Maternal Death
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Fetal Death
                            </span>

                        @endif

                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Delivery Details -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-calendar-heart"></i>
                Delivery Details
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Delivery Date & Time
                    </small>

                    <p class="fw-bold">
                        {{ optional($delivery->delivery_date_time)->format('M d, Y h:i A') }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Delivery Type
                    </small>

                    <p>

                        <span class="badge bg-primary text-capitalize">

                            {{ str_replace('_', ' ', $delivery->delivery_type) }}

                        </span>

                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Number of Babies
                    </small>

                    <p class="fw-bold">
                        {{ $delivery->number_of_babies }}
                    </p>

                </div>

                @if($delivery->reason_for_delivery_type)

                    <div class="col-md-12 mt-3">

                        <small class="text-muted">
                            Reason for Delivery Type
                        </small>

                        <p>
                            {{ $delivery->reason_for_delivery_type }}
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

    <!-- Assisted Delivery -->
    @if($delivery->delivery_type == 'assisted_vaginal')

        <div class="card mb-3 shadow-sm">

            <div class="card-header bg-light">

                <h6 class="mb-0">
                    <i class="bi bi-tools"></i>
                    Assisted Vaginal Delivery
                </h6>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">

                        <small class="text-muted">
                            Assisted With
                        </small>

                        <p class="fw-bold text-capitalize">
                            {{ $delivery->assisted_with ?? 'N/A' }}
                        </p>

                    </div>

                    <div class="col-md-12 mt-3">

                        <small class="text-muted">
                            Indication for Assistance
                        </small>

                        <p>
                            {{ $delivery->indication_for_assistance ?? 'N/A' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

    @endif

    <!-- Caesarean Section -->
    @if($delivery->delivery_type == 'caesarean')

        <div class="card mb-3 shadow-sm">

            <div class="card-header bg-light">

                <h6 class="mb-0">
                    <i class="bi bi-hospital"></i>
                    Caesarean Section
                </h6>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">

                        <small class="text-muted">
                            Caesarean Type
                        </small>

                        <p class="fw-bold text-capitalize">
                            {{ $delivery->caesarean_type ?? 'N/A' }}
                        </p>

                    </div>

                    <div class="col-md-12 mt-3">

                        <small class="text-muted">
                            Indication for Caesarean
                        </small>

                        <p>
                            {{ $delivery->indication_for_caesarean ?? 'N/A' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

    @endif

    <!-- Perineal Trauma -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-exclamation-triangle"></i>
                Perineal Trauma
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Perineal Trauma
                    </small>

                    <p class="fw-bold">
                        {{ $delivery->perineal_trauma ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Episiotomy
                    </small>

                    <p>
                        {{ $delivery->episiotomy ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Perineal Repair
                    </small>

                    <p>
                        {{ $delivery->perineal_repair ?? 'N/A' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Third Stage -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-droplet"></i>
                Third Stage Details
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Placenta Delivery Method
                    </small>

                    <p class="fw-bold">
                        {{ $delivery->placenta_delivery_method ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Placenta Delivered At
                    </small>

                    <p class="fw-bold">
                        {{ optional($delivery->placenta_delivered_at)->format('M d, Y h:i A') ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-12 mt-3">

                    <small class="text-muted">
                        Placental Examination
                    </small>

                    <p>
                        {{ $delivery->placental_examination ?? 'N/A' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Maternal Condition -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-heart-pulse"></i>
                Maternal Condition
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <small class="text-muted">
                        Estimated Blood Loss
                    </small>

                    <p class="fw-bold">
                        {{ $delivery->estimated_blood_loss ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Blood Pressure
                    </small>

                    <p class="fw-bold">
                        {{ $delivery->blood_pressure ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Pulse Rate
                    </small>

                    <p class="fw-bold">
                        {{ $delivery->pulse_rate ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        General Condition
                    </small>

                    <p class="fw-bold">
                        {{ $delivery->general_condition ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-6 mt-3">

                    <small class="text-muted">
                        Uterine Tone
                    </small>

                    <p>
                        {{ $delivery->uterine_tone ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-6 mt-3">

                    <small class="text-muted">
                        Per Vaginal Bleeding
                    </small>

                    <p>
                        {{ $delivery->per_vaginal_bleeding ?? 'N/A' }}
                    </p>

                </div>

                @if($delivery->blood_loss_assessment)

                    <div class="col-md-12 mt-3">

                        <small class="text-muted">
                            Blood Loss Assessment
                        </small>

                        <p>
                            {{ $delivery->blood_loss_assessment }}
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

    <!-- Complications -->
    @if($delivery->complications)

        <div class="card mb-3 shadow-sm border-warning">

            <div class="card-header bg-warning-subtle">

                <h6 class="mb-0">
                    <i class="bi bi-exclamation-octagon"></i>
                    Complications
                </h6>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <small class="text-muted">
                        Complications
                    </small>

                    <p>
                        {{ $delivery->complications }}
                    </p>

                </div>

                <div>

                    <small class="text-muted">
                        Management of Complications
                    </small>

                    <p>
                        {{ $delivery->management_of_complications ?? 'N/A' }}
                    </p>

                </div>

            </div>

        </div>

    @endif

    <!-- Delivery Summary -->
    @if($delivery->delivery_summary)

        <div class="card mb-3 shadow-sm">

            <div class="card-header bg-light">

                <h6 class="mb-0">
                    <i class="bi bi-journal-medical"></i>
                    Delivery Summary
                </h6>

            </div>

            <div class="card-body">

                <p>
                    {{ $delivery->delivery_summary }}
                </p>

            </div>

        </div>

    @endif

    <!-- Footer -->
    <div class="card shadow-sm bg-light">

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <small class="text-muted">
                        Delivered By
                    </small>

                    <p class="fw-bold">
                        {{ $delivery->deliveredBy->name ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Assisted By
                    </small>

                    <p class="fw-bold">
                        {{ $delivery->assistedBy->name ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Created At
                    </small>

                    <p class="fw-bold">
                        {{ $delivery->created_at->format('M d, Y h:i A') }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Record ID
                    </small>

                    <p class="fw-bold">
                        #{{ $delivery->id }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
