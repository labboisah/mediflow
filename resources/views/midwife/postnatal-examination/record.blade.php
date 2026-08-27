@extends('layouts.app')

@section('title', 'Postnatal Examination Records')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-clock-history"></i>
                Postnatal Examination Records
            </h1>

            <small class="text-muted">

                Mother:
                <strong>
                    {{ $delivery->patient->full_name }}
                </strong>

                |

                Delivery Type:
                <strong class="text-capitalize">
                    {{ str_replace('_', ' ', $delivery->delivery_type) }}
                </strong>

            </small>

        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('midwife.postnatal-examination.create', $delivery) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                New Examination

            </a>

            <a href="{{ route('midwife.delivery.show', $delivery) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

    </div>

    <!-- Delivery Summary -->
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-info-circle"></i>
                Delivery Summary
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <small class="text-muted">
                        Delivery Date
                    </small>

                    <p class="fw-bold">
                        {{ optional($delivery->delivery_date_time)->format('M d, Y h:i A') }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Delivery Type
                    </small>

                    <p class="fw-bold text-capitalize">
                        {{ str_replace('_', ' ', $delivery->delivery_type) }}
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

                            <span class="badge bg-warning text-dark">
                                Complicated
                            </span>

                        @elseif($delivery->delivery_status == 'maternal_death')

                            <span class="badge bg-danger">
                                Maternal Death
                            </span>

                        @else

                            <span class="badge bg-dark">
                                Fetal Death
                            </span>

                        @endif

                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Number of Babies
                    </small>

                    <p class="fw-bold">
                        {{ $delivery->number_of_babies }}
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
                        {{ $delivery->postnatalExaminations->count() }}
                    </h3>

                    <small class="text-muted">
                        Total Examinations
                    </small>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold text-success">
                        {{ $delivery->postnatalExaminations->where('recovery_status', 'normal')->count() }}
                    </h3>

                    <small class="text-muted">
                        Normal Recovery
                    </small>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold text-warning">
                        {{ $delivery->postnatalExaminations->where('recovery_status', 'complicated')->count() }}
                    </h3>

                    <small class="text-muted">
                        Complicated Cases
                    </small>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold text-danger">
                        {{ $delivery->postnatalExaminations->where('recovery_status', 'needs_referral')->count() }}
                    </h3>

                    <small class="text-muted">
                        Referrals
                    </small>

                </div>

            </div>

        </div>

    </div>

    <!-- Records Timeline -->
    <div class="card shadow-sm">

        <div class="card-header bg-light d-flex justify-content-between align-items-center">

            <h6 class="mb-0">
                <i class="bi bi-list-ul"></i>
                Postnatal Follow-up Timeline
            </h6>

            <span class="badge bg-primary">
                {{ $delivery->postnatalExaminations->count() }} Records
            </span>

        </div>

        <div class="card-body p-0">

            @forelse($delivery->postnatalExaminations as $examination)

                <div class="border-bottom p-4">

                    <div class="row align-items-center">

                        <!-- Date -->
                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                Examination Date
                            </small>

                            <strong>
                                {{ optional($examination->examination_date_time)->format('M d, Y') }}
                            </strong>

                            <br>

                            <small>
                                {{ optional($examination->examination_date_time)->format('h:i A') }}
                            </small>

                        </div>

                        <!-- Examination Time -->
                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                Follow-up Stage
                            </small>

                            <span class="badge bg-info text-dark">

                                {{ str_replace('_', ' ', $examination->examination_time) }}

                            </span>

                        </div>

                        <!-- Recovery Status -->
                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                Recovery Status
                            </small>

                            @if($examination->recovery_status == 'normal')

                                <span class="badge bg-success">
                                    Normal
                                </span>

                            @elseif($examination->recovery_status == 'complicated')

                                <span class="badge bg-warning text-dark">
                                    Complicated
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Referral
                                </span>

                            @endif

                        </div>

                        <!-- Vital Signs -->
                        <div class="col-md-3">

                            <small class="text-muted d-block">
                                Vital Signs
                            </small>

                            <small>

                                BP:
                                <strong>
                                    {{ $examination->blood_pressure ?? 'N/A' }}
                                </strong>

                                <br>

                                Pulse:
                                <strong>
                                    {{ $examination->pulse_rate ?? 'N/A' }}
                                </strong>

                                bpm

                                <br>

                                Temp:
                                <strong>
                                    {{ $examination->temperature ?? 'N/A' }}
                                </strong>

                                °C

                            </small>

                        </div>

                        <!-- Recorded By -->
                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                Recorded By
                            </small>

                            <strong>
                                {{ $examination->recordedBy->name ?? 'N/A' }}
                            </strong>

                        </div>

                        <!-- Actions -->
                        <div class="col-md-1 text-end">

                            <div class="dropdown">

                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                        type="button"
                                        data-bs-toggle="dropdown">

                                    Action

                                </button>

                                <ul class="dropdown-menu dropdown-menu-end">

                                    <li>

                                        <a class="dropdown-item"
                                           href="{{ route('midwife.postnatal-examination.show', $examination) }}">

                                            <i class="bi bi-eye"></i>
                                            View

                                        </a>

                                    </li>

                                    <li>

                                        <a class="dropdown-item"
                                           href="{{ route('midwife.postnatal-examination.edit', $examination) }}">

                                            <i class="bi bi-pencil"></i>
                                            Edit

                                        </a>

                                    </li>

                                </ul>

                            </div>

                        </div>

                    </div>

                    <!-- Clinical Summary -->
                    @if($examination->clinical_summary)

                        <div class="row mt-3">

                            <div class="col-md-12">

                                <div class="alert alert-light border mb-0">

                                    <small class="text-muted d-block mb-1">
                                        Clinical Summary
                                    </small>

                                    {{ Str::limit($examination->clinical_summary, 300) }}

                                </div>

                            </div>

                        </div>

                    @endif

                    <!-- Additional Alerts -->
                    <div class="row mt-3">

                        @if($examination->signs_of_depression)

                            <div class="col-md-3">

                                <span class="badge bg-danger">
                                    Depression Signs
                                </span>

                            </div>

                        @endif

                        @if($examination->signs_of_dvt)

                            <div class="col-md-3">

                                <span class="badge bg-warning text-dark">
                                    DVT Signs
                                </span>

                            </div>

                        @endif

                        @if($examination->breastfeeding_successful)

                            <div class="col-md-3">

                                <span class="badge bg-success">
                                    Breastfeeding Successful
                                </span>

                            </div>

                        @endif

                        @if($examination->next_follow_up_date)

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Next Follow-up:
                                </small>

                                <br>

                                <strong>
                                    {{ optional($examination->next_follow_up_date)->format('M d, Y') }}
                                </strong>

                            </div>

                        @endif

                    </div>

                </div>

            @empty

                <div class="text-center py-5">

                    <i class="bi bi-clipboard-x display-4 text-muted"></i>

                    <h5 class="mt-3">
                        No Postnatal Records Found
                    </h5>

                    <p class="text-muted">
                        No postnatal examinations have been recorded yet.
                    </p>

                    <a href="{{ route('midwife.postnatal-examination.create', $delivery) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-circle"></i>
                        Record First Examination

                    </a>

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection