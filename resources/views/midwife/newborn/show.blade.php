@extends('layouts.app')

@section('title', 'Newborn Record - ' . ($newborn->newborn_registration_number ?? 'Newborn'))

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-baby"></i>
                Newborn Record
            </h1>

            <small class="text-muted">
                {{ $newborn->patient->name() }}
                -
                {{ optional($newborn->birth_date_time)->format('M d, Y h:i A') }}
            </small>

        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('midwife.newborn.edit', $newborn) }}"
               class="btn btn-outline-warning">

                <i class="bi bi-pencil"></i>
                Edit

            </a>

            <a href="{{ route('midwife.delivery.show', $newborn->delivery_id) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

    </div>

    <!-- Delivery Information -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-heart-pulse"></i>
                Delivery Information
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <small class="text-muted">
                        Mother
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->patient->name() }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Hospital Number
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->patient->hospital_number }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Delivery Type
                    </small>

                    <p>

                        <span class="badge bg-primary">
                            {{ str_replace('_', ' ', ucfirst($newborn->delivery->delivery_type)) }}
                        </span>

                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Delivery Date
                    </small>

                    <p class="fw-bold">
                        {{ optional($newborn->delivery->delivery_date_time)->format('M d, Y h:i A') }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Newborn Information -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-baby"></i>
                Newborn Information
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <small class="text-muted">
                        Sex
                    </small>

                    <p class="fw-bold text-capitalize">
                        {{ $newborn->sex }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Birth Order
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->birth_order ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Registration Number
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->newborn_registration_number ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Birth Date & Time
                    </small>

                    <p class="fw-bold">
                        {{ optional($newborn->birth_date_time)->format('M d, Y h:i A') }}
                    </p>

                </div>

                <div class="col-md-12 mt-3">

                    <small class="text-muted">
                        Presentation
                    </small>

                    <p class="fw-bold text-capitalize">
                        {{ $newborn->presentation ?? 'N/A' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Measurements -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-rulers"></i>
                Anthropometric Measurements
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Birth Weight
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->birth_weight ?? 'N/A' }} g
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Birth Length
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->birth_length ?? 'N/A' }} cm
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Head Circumference
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->head_circumference ?? 'N/A' }} cm
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- APGAR Scores -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-clipboard-pulse"></i>
                APGAR Scores
            </h6>

        </div>

        <div class="card-body">

            <div class="row text-center">

                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <small class="text-muted">
                            1 Minute
                        </small>

                        <h3 class="fw-bold">
                            {{ $newborn->apgar_score_1_minute ?? '-' }}
                        </h3>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <small class="text-muted">
                            5 Minutes
                        </small>

                        <h3 class="fw-bold">
                            {{ $newborn->apgar_score_5_minutes ?? '-' }}
                        </h3>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <small class="text-muted">
                            10 Minutes
                        </small>

                        <h3 class="fw-bold">
                            {{ $newborn->apgar_score_10_minutes ?? '-' }}
                        </h3>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- APGAR Components -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-bar-chart"></i>
                APGAR Components (1 Minute)
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-2">

                    <small class="text-muted">
                        Appearance
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->apgar_appearance_1min ?? '-' }}
                    </p>

                </div>

                <div class="col-md-2">

                    <small class="text-muted">
                        Pulse
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->apgar_pulse_1min ?? '-' }}
                    </p>

                </div>

                <div class="col-md-2">

                    <small class="text-muted">
                        Grimace
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->apgar_grimace_1min ?? '-' }}
                    </p>

                </div>

                <div class="col-md-2">

                    <small class="text-muted">
                        Activity
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->apgar_activity_1min ?? '-' }}
                    </p>

                </div>

                <div class="col-md-2">

                    <small class="text-muted">
                        Respiration
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->apgar_respiration_1min ?? '-' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Newborn Condition -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-heart"></i>
                Newborn Condition
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        General Condition
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->general_condition ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-12 mt-3">

                    <small class="text-muted">
                        Physical Examination
                    </small>

                    <p>
                        {{ $newborn->physical_examination ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-12 mt-3">

                    <small class="text-muted">
                        Birth Defects Noted
                    </small>

                    <p>
                        {{ $newborn->birth_defects_noted ?? 'None' }}
                    </p>

                </div>

                <div class="col-md-12 mt-3">

                    <small class="text-muted">
                        Meconium Aspiration
                    </small>

                    <p>
                        {{ $newborn->meconium_aspiration ?? 'None' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Feeding & Care -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-cup-hot"></i>
                Feeding & Care
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Breastfeeding Initiated
                    </small>

                    <p>

                        @if($newborn->breastfeeding_initiated)

                            <span class="badge bg-success">
                                Yes
                            </span>

                        @else

                            <span class="badge bg-danger">
                                No
                            </span>

                        @endif

                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        First Breastfeed Time
                    </small>

                    <p class="fw-bold">
                        {{ optional($newborn->first_breastfeed_time)->format('M d, Y h:i A') ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-12 mt-3">

                    <small class="text-muted">
                        Feeding Problems
                    </small>

                    <p>
                        {{ $newborn->feeding_problems ?? 'None' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Early Care -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-shield-check"></i>
                Early Newborn Care
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Vitamin K
                    </small>

                    <p>

                        @if($newborn->vitamin_k_given)

                            <span class="badge bg-success">
                                Given
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Not Given
                            </span>

                        @endif

                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Eye Prophylaxis
                    </small>

                    <p>

                        @if($newborn->eye_prophylaxis_given)

                            <span class="badge bg-success">
                                Given
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Not Given
                            </span>

                        @endif

                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Immunizations
                    </small>

                    <p>

                        @if($newborn->immunizations_given)

                            <span class="badge bg-success">
                                Given
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Not Given
                            </span>

                        @endif

                    </p>

                </div>

                <div class="col-md-12 mt-3">

                    <small class="text-muted">
                        Immunization Details
                    </small>

                    <p>
                        {{ $newborn->immunizations_details ?? 'N/A' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Screening & Special Care -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-hospital"></i>
                Screening & Special Care
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <small class="text-muted">
                        Screening Test Done
                    </small>

                    <p>

                        @if($newborn->screening_test_done)

                            <span class="badge bg-success">
                                Yes
                            </span>

                        @else

                            <span class="badge bg-danger">
                                No
                            </span>

                        @endif

                    </p>

                </div>

                <div class="col-md-12 mt-3">

                    <small class="text-muted">
                        Screening Test Results
                    </small>

                    <p>
                        {{ $newborn->screening_test_results ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-12 mt-3">

                    <small class="text-muted">
                        Special Care Needed
                    </small>

                    <p>
                        {{ $newborn->special_care_needed ?? 'None' }}
                    </p>

                </div>

                <div class="col-md-12 mt-3">

                    <small class="text-muted">
                        Referred To
                    </small>

                    <p>
                        {{ $newborn->referred_to ?? 'N/A' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Outcome -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-activity"></i>
                Status & Observations
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Status
                    </small>

                    <p>

                        @if($newborn->status == 'alive')

                            <span class="badge bg-success">
                                Alive
                            </span>

                        @elseif($newborn->status == 'stillborn')

                            <span class="badge bg-danger">
                                Stillborn
                            </span>

                        @else

                            <span class="badge bg-dark">
                                Early Neonatal Death
                            </span>

                        @endif

                    </p>

                </div>

                <div class="col-md-12 mt-3">

                    <small class="text-muted">
                        Neonatal Observations
                    </small>

                    <p>
                        {{ $newborn->neonatal_observations ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-12 mt-3">

                    <small class="text-muted">
                        Delivery Notes
                    </small>

                    <p>
                        {{ $newborn->delivery_notes ?? 'N/A' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Footer -->
    <div class="card shadow-sm bg-light">

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <small class="text-muted">
                        Recorded By
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->recordedBy->name ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Created At
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->created_at->format('M d, Y h:i A') }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Last Updated
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->updated_at->format('M d, Y h:i A') }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Record ID
                    </small>

                    <p class="fw-bold">
                        #{{ $newborn->id }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection