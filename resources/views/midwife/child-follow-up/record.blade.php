@extends('layouts.app')

@section('title', 'Child Follow-up Records')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-clock-history"></i>
                Child Follow-up Records
            </h1>

            <small class="text-muted">

                Baby:
                <strong>
                    {{ $newborn->newborn_registration_number ?? 'N/A' }}
                </strong>

                |

                Mother:
                <strong>
                    {{ $newborn->patient->full_name }}
                </strong>

            </small>

        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('midwife.child-follow-up.create', $newborn) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                New Follow-up

            </a>

            <a href="{{ route('midwife.newborn.show', $newborn) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

    </div>

    <!-- Child Summary -->
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-baby"></i>
                Child Summary
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

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
                        Sex
                    </small>

                    <p class="fw-bold">
                        {{ ucfirst($newborn->sex) }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Birth Weight
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->birth_weight ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Date of Birth
                    </small>

                    <p class="fw-bold">
                        {{ optional($newborn->birth_date_time)->format('M d, Y') }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Statistics -->
    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold">
                        {{ $childFollowUps->count() }}
                    </h3>

                    <small class="text-muted">
                        Total Follow-ups
                    </small>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold text-success">
                        {{ $childFollowUps->where('health_status', 'normal')->count() }}
                    </h3>

                    <small class="text-muted">
                        Normal
                    </small>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold text-warning">
                        {{ $childFollowUps->where('health_status', 'at_risk')->count() }}
                    </h3>

                    <small class="text-muted">
                        At Risk
                    </small>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold text-danger">
                        {{ $childFollowUps->whereIn('health_status', ['needs_referral', 'referred'])->count() }}
                    </h3>

                    <small class="text-muted">
                        Referrals
                    </small>

                </div>

            </div>

        </div>

    </div>

    <!-- Follow-up Timeline -->
    <div class="card shadow-sm">

        <div class="card-header bg-light d-flex justify-content-between align-items-center">

            <h6 class="mb-0">
                <i class="bi bi-list-ul"></i>
                Follow-up Timeline
            </h6>

            <span class="badge bg-primary">
                {{ $childFollowUps->count() }} Records
            </span>

        </div>

        <div class="card-body p-0">

            @forelse($childFollowUps as $followUp)

                <div class="border-bottom p-4">

                    <div class="row align-items-center">

                        <!-- Date -->
                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                Follow-up Date
                            </small>

                            <strong>
                                {{ optional($followUp->follow_up_date_time)->format('M d, Y') }}
                            </strong>

                            <br>

                            <small>
                                {{ optional($followUp->follow_up_date_time)->format('h:i A') }}
                            </small>

                        </div>

                        <!-- Follow-up Period -->
                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                Follow-up Stage
                            </small>

                            <span class="badge bg-info text-dark">

                                {{ str_replace('_', ' ', $followUp->follow_up_period) }}

                            </span>

                            <br>

                            <small class="text-muted">

                                {{ $followUp->days_of_life ?? 'N/A' }} Days Old

                            </small>

                        </div>

                        <!-- Health Status -->
                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                Health Status
                            </small>

                            @if($followUp->health_status == 'normal')

                                <span class="badge bg-success">
                                    Normal
                                </span>

                            @elseif($followUp->health_status == 'at_risk')

                                <span class="badge bg-warning text-dark">
                                    At Risk
                                </span>

                            @elseif($followUp->health_status == 'needs_referral')

                                <span class="badge bg-danger">
                                    Needs Referral
                                </span>

                            @else

                                <span class="badge bg-dark">
                                    Referred
                                </span>

                            @endif

                        </div>

                        <!-- Vital Signs -->
                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                Vital Signs
                            </small>

                            <small>

                                Temp:
                                <strong>
                                    {{ $followUp->temperature ?? 'N/A' }}
                                </strong>

                                °C

                                <br>

                                HR:
                                <strong>
                                    {{ $followUp->heart_rate ?? 'N/A' }}
                                </strong>

                                bpm

                            </small>

                        </div>

                        <!-- Growth -->
                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                Growth
                            </small>

                            <small>

                                Weight:
                                <strong>
                                    {{ $followUp->weight ?? 'N/A' }}
                                </strong>

                                kg

                                <br>

                                Length:
                                <strong>
                                    {{ $followUp->length ?? 'N/A' }}
                                </strong>

                                cm

                            </small>

                        </div>

                        <!-- Actions -->
                        <div class="col-md-2 text-end">

                            <div class="dropdown">

                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                        type="button"
                                        data-bs-toggle="dropdown">

                                    Action

                                </button>

                                <ul class="dropdown-menu dropdown-menu-end">

                                    <li>

                                        <a class="dropdown-item"
                                           href="{{ route('midwife.child-follow-up.show', $followUp) }}">

                                            <i class="bi bi-eye"></i>
                                            View

                                        </a>

                                    </li>

                                    <li>

                                        <a class="dropdown-item"
                                           href="{{ route('midwife.child-follow-up.edit', $followUp) }}">

                                            <i class="bi bi-pencil"></i>
                                            Edit

                                        </a>

                                    </li>

                                </ul>

                            </div>

                        </div>

                    </div>

                    <!-- Feeding Summary -->
                    @if($followUp->feeding_type || $followUp->feeding_problems)

                        <div class="row mt-3">

                            <div class="col-md-6">

                                <div class="alert alert-light border mb-0">

                                    <small class="text-muted d-block mb-1">
                                        Feeding Type
                                    </small>

                                    <strong class="text-capitalize">

                                        {{ str_replace('_', ' ', $followUp->feeding_type ?? 'N/A') }}

                                    </strong>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="alert alert-light border mb-0">

                                    <small class="text-muted d-block mb-1">
                                        Feeding Problems
                                    </small>

                                    {{ $followUp->feeding_problems ?? 'No Problems Reported' }}

                                </div>

                            </div>

                        </div>

                    @endif

                    <!-- Clinical Summary -->
                    @if($followUp->clinical_summary)

                        <div class="row mt-3">

                            <div class="col-md-12">

                                <div class="alert alert-light border mb-0">

                                    <small class="text-muted d-block mb-1">
                                        Clinical Summary
                                    </small>

                                    {{ Str::limit($followUp->clinical_summary, 300) }}

                                </div>

                            </div>

                        </div>

                    @endif

                    <!-- Additional Indicators -->
                    <div class="row mt-3">

                        @if($followUp->jaundice_present)

                            <div class="col-md-3">

                                <span class="badge bg-warning text-dark">
                                    Jaundice Present
                                </span>

                            </div>

                        @endif

                        @if($followUp->immunizations_up_to_date)

                            <div class="col-md-3">

                                <span class="badge bg-success">
                                    Immunizations Updated
                                </span>

                            </div>

                        @endif

                        @if($followUp->danger_signs_explained)

                            <div class="col-md-3">

                                <span class="badge bg-info text-dark">
                                    Danger Signs Explained
                                </span>

                            </div>

                        @endif

                        @if($followUp->next_follow_up_date)

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Next Follow-up:
                                </small>

                                <br>

                                <strong>
                                    {{ optional($followUp->next_follow_up_date)->format('M d, Y') }}
                                </strong>

                            </div>

                        @endif

                    </div>

                </div>

            @empty

                <div class="text-center py-5">

                    <i class="bi bi-clipboard-x display-4 text-muted"></i>

                    <h5 class="mt-3">
                        No Follow-up Records Found
                    </h5>

                    <p class="text-muted">
                        No child follow-up assessments have been recorded yet.
                    </p>

                    <a href="{{ route('midwife.child-follow-up.create', $newborn) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-circle"></i>
                        Record First Follow-up

                    </a>

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection