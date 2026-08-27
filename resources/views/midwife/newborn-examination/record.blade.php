@extends('layouts.app')

@section('title', 'Newborn Examination Records')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-clock-history"></i>
                Newborn Examination History
            </h1>

            <small class="text-muted">

                Newborn:
                <strong>
                    {{ $newborn->newborn_registration_number ?? 'N/A' }}
                </strong>

                |
                Mother:
                <strong>
                    {{ $newborn->patient->name() }}
                </strong>

            </small>

        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('midwife.newborn-examination.create', $newborn) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                New Examination

            </a>

            <a href="{{ route('midwife.newborn.show', $newborn) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

    </div>

    <!-- Newborn Summary -->
    <div class="card mb-4 shadow-sm">

        <div class="card-header bg-light">

            <h6 class="mb-0">
                <i class="bi bi-baby"></i>
                Newborn Summary
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-2">

                    <small class="text-muted">
                        Sex
                    </small>

                    <p class="fw-bold text-capitalize">
                        {{ $newborn->sex }}
                    </p>

                </div>

                <div class="col-md-2">

                    <small class="text-muted">
                        Birth Weight
                    </small>

                    <p class="fw-bold">
                        {{ $newborn->birth_weight ?? 'N/A' }} g
                    </p>

                </div>

                <div class="col-md-2">

                    <small class="text-muted">
                        Delivery Type
                    </small>

                    <p class="fw-bold text-capitalize">
                        {{ str_replace('_', ' ', $newborn->delivery->delivery_type) }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Birth Date
                    </small>

                    <p class="fw-bold">
                        {{ optional($newborn->birth_date_time)->format('M d, Y h:i A') }}
                    </p>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Newborn Status
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

            </div>

        </div>

    </div>

    <!-- Examination Statistics -->
     @php 
        $examinations = $newborn->examinations->sortByDesc('examination_date_time');
     @endphp
    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold">
                        {{ $examinations->count() }}
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
                        {{ $examinations->where('exam_status', 'normal')->count() }}
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
                        {{ $examinations->where('exam_status', 'needs_follow_up')->count() }}
                    </h3>

                    <small class="text-muted">
                        Follow-up Needed
                    </small>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold text-danger">
                        {{ $examinations->where('exam_status', 'referral_needed')->count() }}
                    </h3>

                    <small class="text-muted">
                        Referral Needed
                    </small>

                </div>

            </div>

        </div>

    </div>

    <!-- Examination History -->
    <div class="card shadow-sm">

        <div class="card-header bg-light d-flex justify-content-between align-items-center">

            <h6 class="mb-0">
                <i class="bi bi-list-ul"></i>
                Examination Timeline
            </h6>

            <span class="badge bg-primary">
                {{ $examinations->count() }} Records
            </span>

        </div>

        <div class="card-body p-0">

            @forelse($examinations as $examination)

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

                        <!-- Status -->
                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                Status
                            </small>

                            @if($examination->exam_status == 'normal')

                                <span class="badge bg-success">
                                    Normal
                                </span>

                            @elseif($examination->exam_status == 'abnormal')

                                <span class="badge bg-danger">
                                    Abnormal
                                </span>

                            @elseif($examination->exam_status == 'needs_follow_up')

                                <span class="badge bg-warning text-dark">
                                    Follow-up
                                </span>

                            @else

                                <span class="badge bg-primary">
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

                                Temp:
                                <strong>
                                    {{ $examination->temperature ?? 'N/A' }}
                                </strong>

                                °C

                                <br>

                                HR:
                                <strong>
                                    {{ $examination->heart_rate ?? 'N/A' }}
                                </strong>

                                bpm

                                <br>

                                RR:
                                <strong>
                                    {{ $examination->respiratory_rate ?? 'N/A' }}
                                </strong>

                            </small>

                        </div>

                        <!-- Feeding -->
                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                Feeding
                            </small>

                            <span class="text-capitalize fw-bold">

                                {{ $examination->feeding_type ?? 'N/A' }}

                            </span>

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
                                           href="{{ route('midwife.newborn-examination.show', $examination) }}">

                                            <i class="bi bi-eye"></i>
                                            View

                                        </a>

                                    </li>

                                    <li>

                                        <a class="dropdown-item"
                                           href="{{ route('midwife.newborn-examination.edit', $examination) }}">

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

                                    {{ Str::limit($examination->clinical_summary, 250) }}

                                </div>

                            </div>

                        </div>

                    @endif

                    <!-- Follow Up -->
                    @if($examination->next_follow_up_date)

                        <div class="mt-3">

                            <small class="text-muted">
                                Next Follow-up:
                            </small>

                            <strong>
                                {{ optional($examination->next_follow_up_date)->format('M d, Y h:i A') }}
                            </strong>

                        </div>

                    @endif

                </div>

            @empty

                <div class="text-center py-5">

                    <i class="bi bi-clipboard-x display-4 text-muted"></i>

                    <h5 class="mt-3">
                        No Examination Records Found
                    </h5>

                    <p class="text-muted">
                        No newborn examinations have been recorded yet.
                    </p>

                    <a href="{{ route('midwife.newborn-examination.create', $newborn) }}"
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