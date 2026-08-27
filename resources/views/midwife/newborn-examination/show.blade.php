@extends('layouts.app')

@section('title', 'Newborn Examination Details')

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-clipboard2-pulse"></i>
                Newborn Examination Details
            </h1>

            <small class="text-muted">
                Examination record for newborn:
                {{ $newbornExamination->newborn->newborn_registration_number ?? 'N/A' }}
            </small>

        </div>

        <div class="col-md-4 text-end">
                
            <a href="{{ route('midwife.newborn-examination.edit', [$newbornExamination]) }}"
               class="btn btn-outline-warning">

                <i class="bi bi-pencil"></i>
                Edit

            </a>

            <a href="{{ route('midwife.newborn.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

    </div>

    <!-- Examination Summary -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-info-circle"></i>
                Examination Summary
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <small class="text-muted">
                        Examination Date
                    </small>

                    <p class="fw-bold">
                        {{ optional($newbornExamination->examination_date_time)->format('M d, Y h:i A') }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Hours After Birth
                    </small>

                    <p class="fw-bold">
                        {{ $newbornExamination->hours_after_birth ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Exam Status
                    </small>

                    <p>

                        @if($newbornExamination->exam_status == 'normal')

                            <span class="badge bg-success">
                                Normal
                            </span>

                        @elseif($newbornExamination->exam_status == 'abnormal')

                            <span class="badge bg-danger">
                                Abnormal
                            </span>

                        @elseif($newbornExamination->exam_status == 'needs_follow_up')

                            <span class="badge bg-warning text-dark">
                                Needs Follow-up
                            </span>

                        @else

                            <span class="badge bg-primary">
                                Referral Needed
                            </span>

                        @endif

                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Recorded By
                    </small>

                    <p class="fw-bold">
                        {{ $newbornExamination->recordedBy->name ?? 'N/A' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Vital Signs -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-heart-pulse"></i>
                Vital Signs
            </h6>

        </div>

        <div class="card-body">

            <div class="row text-center">

                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <small class="text-muted">
                            Temperature
                        </small>

                        <h5 class="fw-bold">
                            {{ $newbornExamination->temperature ?? 'N/A' }} °C
                        </h5>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <small class="text-muted">
                            Heart Rate
                        </small>

                        <h5 class="fw-bold">
                            {{ $newbornExamination->heart_rate ?? 'N/A' }} bpm
                        </h5>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <small class="text-muted">
                            Respiratory Rate
                        </small>

                        <h5 class="fw-bold">
                            {{ $newbornExamination->respiratory_rate ?? 'N/A' }}
                        </h5>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Anthropometry -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-rulers"></i>
                Anthropometry
            </h6>

        </div>

        <div class="card-body">

            <div class="row text-center">

                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <small class="text-muted">
                            Weight
                        </small>

                        <h6 class="fw-bold">
                            {{ $newbornExamination->weight ?? 'N/A' }} g
                        </h6>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <small class="text-muted">
                            Length
                        </small>

                        <h6 class="fw-bold">
                            {{ $newbornExamination->length ?? 'N/A' }} cm
                        </h6>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <small class="text-muted">
                            Head Circumference
                        </small>

                        <h6 class="fw-bold">
                            {{ $newbornExamination->head_circumference ?? 'N/A' }} cm
                        </h6>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <small class="text-muted">
                            Chest Circumference
                        </small>

                        <h6 class="fw-bold">
                            {{ $newbornExamination->chest_circumference ?? 'N/A' }} cm
                        </h6>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- General Examination -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-person-check"></i>
                General Examination
            </h6>

        </div>

        <div class="card-body">

            <div class="mb-3">

                <small class="text-muted">
                    General Appearance
                </small>

                <p>
                    {{ $newbornExamination->general_appearance ?? 'N/A' }}
                </p>

            </div>

            <div class="mb-3">

                <small class="text-muted">
                    Skin Examination
                </small>

                <p>
                    {{ $newbornExamination->skin_examination ?? 'N/A' }}
                </p>

            </div>

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Head & Neck
                    </small>

                    <p>
                        {{ $newbornExamination->head_and_neck ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Eyes Examination
                    </small>

                    <p>
                        {{ $newbornExamination->eyes_examination ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Ear Examination
                    </small>

                    <p>
                        {{ $newbornExamination->ear_examination ?? 'N/A' }}
                    </p>

                </div>

            </div>

            <div class="mt-3">

                <small class="text-muted">
                    Mouth & Throat
                </small>

                <p>
                    {{ $newbornExamination->mouth_and_throat ?? 'N/A' }}
                </p>

            </div>

        </div>

    </div>

    <!-- Cardiovascular -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-heart"></i>
                Cardiovascular Examination
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Heart Sounds
                    </small>

                    <p>
                        {{ $newbornExamination->heart_sounds ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Pulses
                    </small>

                    <p>
                        {{ $newbornExamination->pulses ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Capillary Refill
                    </small>

                    <p>
                        {{ $newbornExamination->capillary_refill ?? 'N/A' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Respiratory -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-lungs"></i>
                Respiratory Examination
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Chest Expansion
                    </small>

                    <p>
                        {{ $newbornExamination->chest_expansion ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Breath Sounds
                    </small>

                    <p>
                        {{ $newbornExamination->breath_sounds ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Nasal Breathing
                    </small>

                    <p>
                        {{ $newbornExamination->nasal_breathing ?? 'N/A' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Abdomen -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-circle"></i>
                Abdominal Examination
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <small class="text-muted">
                        Abdomen Shape
                    </small>

                    <p>
                        {{ $newbornExamination->abdomen_shape ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-6">

                    <small class="text-muted">
                        Umbilical Cord Check
                    </small>

                    <p>
                        {{ $newbornExamination->umbilical_cord_check ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-6 mt-3">

                    <small class="text-muted">
                        Hepatomegaly / Splenomegaly
                    </small>

                    <p>
                        {{ $newbornExamination->hepatomegaly_splenomegaly ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-6 mt-3">

                    <small class="text-muted">
                        Bowel Sounds
                    </small>

                    <p>
                        {{ $newbornExamination->bowel_sounds ?? 'N/A' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Neurological -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-cpu"></i>
                Neurological Examination
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Reflex Assessment
                    </small>

                    <p>
                        {{ $newbornExamination->reflex_assessment ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Muscle Tone
                    </small>

                    <p>
                        {{ $newbornExamination->muscle_tone ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Developmental Screening
                    </small>

                    <p>
                        {{ $newbornExamination->developmental_screening ?? 'N/A' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Special Findings -->
    <div class="card mb-3 shadow-sm border-warning">

        <div class="card-header bg-warning-subtle">

            <h6 class="mb-0">
                <i class="bi bi-exclamation-triangle"></i>
                Special Findings
            </h6>

        </div>

        <div class="card-body">

            <div class="mb-3">

                <small class="text-muted">
                    Abnormal Findings
                </small>

                <p>
                    {{ $newbornExamination->abnormal_findings ?? 'None' }}
                </p>

            </div>

            <div>

                <small class="text-muted">
                    Congenital Anomalies
                </small>

                <p>
                    {{ $newbornExamination->congenital_anomalies ?? 'None' }}
                </p>

            </div>

        </div>

    </div>

    <!-- Jaundice -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-sun"></i>
                Jaundice Assessment
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Jaundice Present
                    </small>

                    <p>

                        @if($newbornExamination->jaundice_present)

                            <span class="badge bg-warning text-dark">
                                Yes
                            </span>

                        @else

                            <span class="badge bg-success">
                                No
                            </span>

                        @endif

                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Jaundice Level
                    </small>

                    <p>
                        {{ $newbornExamination->jaundice_level ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Jaundice Management
                    </small>

                    <p>
                        {{ $newbornExamination->jaundice_management ?? 'N/A' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Feeding Assessment -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-cup-hot"></i>
                Feeding Assessment
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Feeding Type
                    </small>

                    <p class="fw-bold text-capitalize">
                        {{ $newbornExamination->feeding_type ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Feeding Tolerance
                    </small>

                    <p>
                        {{ $newbornExamination->feeding_tolerance ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Feeding Challenges
                    </small>

                    <p>
                        {{ $newbornExamination->feeding_challenges ?? 'N/A' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Clinical Summary -->
    <div class="card mb-3 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-journal-medical"></i>
                Clinical Summary & Follow-up
            </h6>

        </div>

        <div class="card-body">

            <div class="mb-3">

                <small class="text-muted">
                    Clinical Summary
                </small>

                <p>
                    {{ $newbornExamination->clinical_summary ?? 'N/A' }}
                </p>

            </div>

            <div class="mb-3">

                <small class="text-muted">
                    Follow-up Plans
                </small>

                <p>
                    {{ $newbornExamination->follow_up_plans ?? 'N/A' }}
                </p>

            </div>

            <div>

                <small class="text-muted">
                    Next Follow-up Date
                </small>

                <p class="fw-bold">
                    {{ optional($newbornExamination->next_follow_up_date)->format('M d, Y h:i A') ?? 'N/A' }}
                </p>

            </div>

        </div>

    </div>

    <!-- Footer -->
    <div class="card shadow-sm bg-light">

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Created At
                    </small>

                    <p class="fw-bold">
                        {{ $newbornExamination->created_at->format('M d, Y h:i A') }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Last Updated
                    </small>

                    <p class="fw-bold">
                        {{ $newbornExamination->updated_at->format('M d, Y h:i A') }}
                    </p>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Examination ID
                    </small>

                    <p class="fw-bold">
                        #{{ $newbornExamination->id }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection