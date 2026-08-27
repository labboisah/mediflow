@extends('layouts.app')

@section('title', 'Child Follow-up Details')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-clipboard2-pulse"></i>
                Child Follow-up Details
            </h1>

            <small class="text-muted">

                Baby:
                <strong>
                    {{ $childFollowUp->newborn->newborn_registration_number ?? 'N/A' }}
                </strong>

                |

                Mother:
                <strong>
                    {{ $childFollowUp->mother->name() ?? 'N/A' }}
                </strong>

            </small>

        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('midwife.child-follow-up.edit', $childFollowUp) }}"
               class="btn btn-primary">

                <i class="bi bi-pencil-square"></i>
                Edit

            </a>

            <a href="{{ route('midwife.child-follow-up.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <small class="text-muted d-block">
                        Follow-up Period
                    </small>

                    <h5 class="fw-bold text-primary">

                        {{ str_replace('_', ' ', $childFollowUp->follow_up_period) }}

                    </h5>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <small class="text-muted d-block">
                        Days of Life
                    </small>

                    <h5 class="fw-bold">

                        {{ $childFollowUp->days_of_life ?? 'N/A' }}

                    </h5>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <small class="text-muted d-block">
                        Weight
                    </small>

                    <h5 class="fw-bold">

                        {{ $childFollowUp->weight ?? 'N/A' }} kg

                    </h5>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <small class="text-muted d-block">
                        Health Status
                    </small>

                    @if($childFollowUp->health_status == 'normal')

                        <span class="badge bg-success">
                            Normal
                        </span>

                    @elseif($childFollowUp->health_status == 'at_risk')

                        <span class="badge bg-warning text-dark">
                            At Risk
                        </span>

                    @elseif($childFollowUp->health_status == 'needs_referral')

                        <span class="badge bg-danger">
                            Needs Referral
                        </span>

                    @else

                        <span class="badge bg-dark">
                            Referred
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        <!-- Main Content -->
        <div class="col-lg-9">

            <!-- Follow-up Information -->
            <div class="card shadow-sm mb-3">

                <div class="card-header bg-light">

                    <h6 class="mb-0">
                        <i class="bi bi-calendar-check"></i>
                        Follow-up Information
                    </h6>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4">

                            <small class="text-muted">
                                Follow-up Date
                            </small>

                            <p class="fw-bold">
                                {{ optional($childFollowUp->follow_up_date_time)->format('M d, Y h:i A') }}
                            </p>

                        </div>

                        <div class="col-md-4">

                            <small class="text-muted">
                                Follow-up Location
                            </small>

                            <p class="fw-bold text-capitalize">
                                {{ $childFollowUp->location ?? 'N/A' }}
                            </p>

                        </div>

                        <div class="col-md-4">

                            <small class="text-muted">
                                Recorded By
                            </small>

                            <p class="fw-bold">
                                {{ $childFollowUp->recordedBy->name ?? 'N/A' }}
                            </p>

                        </div>

                    </div>

                    @if($childFollowUp->location_details)

                        <hr>

                        <small class="text-muted d-block">
                            Location Details
                        </small>

                        <p>
                            {{ $childFollowUp->location_details }}
                        </p>

                    @endif

                </div>

            </div>

            <!-- Feeding Assessment -->
            <div class="card shadow-sm mb-3">

                <div class="card-header bg-light">

                    <h6 class="mb-0">
                        <i class="bi bi-heart"></i>
                        Feeding Assessment
                    </h6>

                </div>

                <div class="card-body">

                    <div class="row mb-3">

                        <div class="col-md-4">

                            <small class="text-muted">
                                Feeding Type
                            </small>

                            <p class="fw-bold text-capitalize">

                                {{ str_replace('_', ' ', $childFollowUp->feeding_type ?? 'N/A') }}

                            </p>

                        </div>

                        <div class="col-md-4">

                            <small class="text-muted">
                                Feeding Frequency
                            </small>

                            <p class="fw-bold">
                                {{ $childFollowUp->feeding_frequency ?? 'N/A' }}
                            </p>

                        </div>

                        <div class="col-md-4">

                            <small class="text-muted">
                                Feeding Duration
                            </small>

                            <p class="fw-bold">
                                {{ $childFollowUp->feeding_duration ?? 'N/A' }}
                            </p>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                How Baby is Feeding
                            </small>

                            <div class="border rounded p-2 bg-light">

                                {{ $childFollowUp->how_baby_is_feeding ?? 'N/A' }}

                            </div>

                        </div>

                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Feeding Problems
                            </small>

                            <div class="border rounded p-2 bg-light">

                                {{ $childFollowUp->feeding_problems ?? 'None Reported' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Vital Signs & Growth -->
            <div class="card shadow-sm mb-3">

                <div class="card-header bg-light">

                    <h6 class="mb-0">
                        <i class="bi bi-heart-pulse"></i>
                        Vital Signs & Growth Parameters
                    </h6>

                </div>

                <div class="card-body">

                    <div class="row text-center">

                        <div class="col-md-3">

                            <small class="text-muted d-block">
                                Temperature
                            </small>

                            <h6 class="fw-bold">
                                {{ $childFollowUp->temperature ?? 'N/A' }} °C
                            </h6>

                        </div>

                        <div class="col-md-3">

                            <small class="text-muted d-block">
                                Heart Rate
                            </small>

                            <h6 class="fw-bold">
                                {{ $childFollowUp->heart_rate ?? 'N/A' }} bpm
                            </h6>

                        </div>

                        <div class="col-md-3">

                            <small class="text-muted d-block">
                                Respiratory Rate
                            </small>

                            <h6 class="fw-bold">
                                {{ $childFollowUp->respiratory_rate ?? 'N/A' }}
                            </h6>

                        </div>

                        <div class="col-md-3">

                            <small class="text-muted d-block">
                                Weight Percentile
                            </small>

                            <h6 class="fw-bold">
                                {{ $childFollowUp->weight_percentile ?? 'N/A' }}
                            </h6>

                        </div>

                    </div>

                    <hr>

                    <div class="row text-center">

                        <div class="col-md-4">

                            <small class="text-muted d-block">
                                Weight
                            </small>

                            <h6 class="fw-bold">
                                {{ $childFollowUp->weight ?? 'N/A' }} kg
                            </h6>

                        </div>

                        <div class="col-md-4">

                            <small class="text-muted d-block">
                                Length
                            </small>

                            <h6 class="fw-bold">
                                {{ $childFollowUp->length ?? 'N/A' }} cm
                            </h6>

                        </div>

                        <div class="col-md-4">

                            <small class="text-muted d-block">
                                Head Circumference
                            </small>

                            <h6 class="fw-bold">
                                {{ $childFollowUp->head_circumference ?? 'N/A' }} cm
                            </h6>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Jaundice & Immunization -->
            <div class="card shadow-sm mb-3">

                <div class="card-header bg-light">

                    <h6 class="mb-0">
                        <i class="bi bi-shield-check"></i>
                        Jaundice & Immunization
                    </h6>

                </div>

                <div class="card-body">

                    <div class="row mb-3">

                        <div class="col-md-4">

                            <small class="text-muted">
                                Jaundice Present
                            </small>

                            <p>

                                @if($childFollowUp->jaundice_present)

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
                                Immunization Status
                            </small>

                            <p>

                                @if($childFollowUp->immunizations_up_to_date)

                                    <span class="badge bg-success">
                                        Up to Date
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Pending
                                    </span>

                                @endif

                            </p>

                        </div>

                        <div class="col-md-4">

                            <small class="text-muted">
                                Hearing Screening
                            </small>

                            <p>

                                @if($childFollowUp->hearing_screening_done)

                                    <span class="badge bg-success">
                                        Done
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @endif

                            </p>

                        </div>

                    </div>

                    @if($childFollowUp->immunizations_given)

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Immunizations Given
                            </small>

                            <div class="border rounded p-2 bg-light">

                                {{ $childFollowUp->immunizations_given }}

                            </div>

                        </div>

                    @endif

                    @if($childFollowUp->jaundice_management)

                        <div>

                            <small class="text-muted d-block">
                                Jaundice Management
                            </small>

                            <div class="border rounded p-2 bg-light">

                                {{ $childFollowUp->jaundice_management }}

                            </div>

                        </div>

                    @endif

                </div>

            </div>

            <!-- Clinical Summary -->
            <div class="card shadow-sm mb-4">

                <div class="card-header bg-light">

                    <h6 class="mb-0">
                        <i class="bi bi-journal-medical"></i>
                        Clinical Summary & Management
                    </h6>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Clinical Summary
                        </small>

                        <div class="border rounded p-3 bg-light">

                            {{ $childFollowUp->clinical_summary ?? 'No Summary Provided' }}

                        </div>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Management Plan
                        </small>

                        <div class="border rounded p-3 bg-light">

                            {{ $childFollowUp->management_plan ?? 'No Management Plan' }}

                        </div>

                    </div>

                    @if($childFollowUp->referral_reason)

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Referral Reason
                            </small>

                            <div class="border rounded p-3 bg-light">

                                {{ $childFollowUp->referral_reason }}

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

        <!-- Sidebar -->
        <div class="col-lg-3">

            <!-- Follow-up Status -->
            <div class="card shadow-sm mb-3">

                <div class="card-header bg-light">

                    <h6 class="mb-0">
                        <i class="bi bi-activity"></i>
                        Follow-up Status
                    </h6>

                </div>

                <div class="card-body">

                    <small class="text-muted d-block mb-2">
                        Danger Signs Explained
                    </small>

                    @if($childFollowUp->danger_signs_explained)

                        <span class="badge bg-success">
                            Explained
                        </span>

                    @else

                        <span class="badge bg-warning text-dark">
                            Not Explained
                        </span>

                    @endif

                    <hr>

                    <small class="text-muted d-block mb-2">
                        Next Follow-up
                    </small>

                    <strong>

                        {{ optional($childFollowUp->next_follow_up_date)->format('M d, Y h:i A') ?? 'Not Scheduled' }}

                    </strong>

                </div>

            </div>

            <!-- Child Information -->
            <div class="card shadow-sm">

                <div class="card-header bg-light">

                    <h6 class="mb-0">
                        <i class="bi bi-baby"></i>
                        Child Information
                    </h6>

                </div>

                <div class="card-body">

                    <small class="text-muted">

                        <strong>Sex:</strong>
                        <br>
                        {{ ucfirst($childFollowUp->newborn->sex) }}

                        <hr>

                        <strong>Birth Weight:</strong>
                        <br>
                        {{ $childFollowUp->newborn->birth_weight ?? 'N/A' }}

                        <hr>

                        <strong>Birth Date:</strong>
                        <br>
                        {{ optional($childFollowUp->newborn->birth_date_time)->format('M d, Y') }}

                        <hr>

                        <strong>Recorded:</strong>
                        <br>
                        {{ $childFollowUp->created_at->format('M d, Y h:i A') }}

                    </small>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection