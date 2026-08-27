@extends('layouts.app')

@section('title', 'ANC Patient Medications')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-capsule"></i>
                ANC Patient Medications
            </h1>

            <small class="text-muted">

                Patient:
                <strong>
                    {{ $patient->name() }}
                </strong>

                |

                Hospital No:
                <strong>
                    {{ $patient->hospital_number }}
                </strong>

            </small>

        </div>

        <div class="col-md-4 text-end">

            <a href=""
               class="btn btn-outline-primary">

                <i class="bi bi-clipboard2-pulse"></i>
                Manage Investigations

            </a>

            <a href=""
               class="btn btn-primary">

                <i class="bi bi-capsule-pill"></i>
                Drug Prescription

            </a>

        </div>

    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold text-primary">
                        {{ $prescriptions->count() }}
                    </h3>

                    <small class="text-muted">
                        Total Prescriptions
                    </small>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold text-success">
                        {{ $prescriptions->where('status', 'active')->count() }}
                    </h3>

                    <small class="text-muted">
                        Active Prescriptions
                    </small>

                </div>
            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold text-warning">
                        {{ $investigations->count() }}
                    </h3>

                    <small class="text-muted">
                        Investigations
                    </small>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold text-danger">
                        {{ $prescriptions->where('status', 'stopped')->count() }}
                    </h3>

                    <small class="text-muted">
                        Stopped Drugs
                    </small>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        <!-- Drug Prescriptions -->
        <div class="col-lg-8">

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-light d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        <i class="bi bi-capsule-pill"></i>
                        Drug Prescriptions
                    </h5>

                    <span class="badge bg-primary">
                        {{ $prescriptions->count() }} Records
                    </span>

                </div>

                <div class="card-body p-0">

                    @forelse($prescriptions as $prescription)

                        <div class="border-bottom p-4">

                            <div class="row align-items-center">

                                <div class="col-md-3">

                                    <small class="text-muted d-block">
                                        Drug Name
                                    </small>

                                    <strong>
                                        {{ $prescription->drug_name }}
                                    </strong>

                                    <br>

                                    <small>
                                        {{ $prescription->dosage }}
                                    </small>

                                </div>

                                <div class="col-md-2">

                                    <small class="text-muted d-block">
                                        Frequency
                                    </small>

                                    <strong>
                                        {{ $prescription->frequency }}
                                    </strong>

                                </div>

                                <div class="col-md-2">

                                    <small class="text-muted d-block">
                                        Duration
                                    </small>

                                    <strong>
                                        {{ $prescription->duration }}
                                    </strong>

                                </div>

                                <div class="col-md-2">

                                    <small class="text-muted d-block">
                                        Status
                                    </small>

                                    @if($prescription->status == 'active')

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @elseif($prescription->status == 'completed')

                                        <span class="badge bg-primary">
                                            Completed
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Stopped
                                        </span>

                                    @endif

                                </div>

                                <div class="col-md-2">

                                    <small class="text-muted d-block">
                                        Prescribed By
                                    </small>

                                    <strong>
                                        {{ $prescription->prescribedBy->name ?? 'N/A' }}
                                    </strong>

                                </div>

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
                                                   href="{{ route('midwife.drug-prescription.show', $prescription) }}">

                                                    <i class="bi bi-eye"></i>
                                                    View

                                                </a>

                                            </li>

                                            <li>

                                                <a class="dropdown-item"
                                                   href="{{ route('midwife.drug-prescription.edit', $prescription) }}">

                                                    <i class="bi bi-pencil"></i>
                                                    Edit

                                                </a>

                                            </li>

                                        </ul>

                                    </div>

                                </div>

                            </div>

                            @if($prescription->instruction)

                                <div class="row mt-3">

                                    <div class="col-md-12">

                                        <div class="alert alert-light border mb-0">

                                            <small class="text-muted d-block mb-1">
                                                Instructions
                                            </small>

                                            {{ $prescription->instruction }}

                                        </div>

                                    </div>

                                </div>

                            @endif

                        </div>

                    @empty

                        <div class="text-center py-5">

                            <i class="bi bi-capsule display-4 text-muted"></i>

                            <h5 class="mt-3">
                                No Drug Prescriptions Found
                            </h5>

                            <p class="text-muted">
                                No medication has been prescribed for this ANC patient yet.
                            </p>

                            <a href="{{ route('midwife.drug-prescription.create', $patient) }}"
                               class="btn btn-primary">

                                <i class="bi bi-plus-circle"></i>
                                Create Prescription

                            </a>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

        <!-- Investigations Sidebar -->
        <div class="col-lg-4">

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-light d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        <i class="bi bi-clipboard2-pulse"></i>
                        Investigations
                    </h5>

                    <a href="{{ route('midwife.anc-investigation.create', $patient) }}"
                       class="btn btn-sm btn-primary">

                        <i class="bi bi-plus"></i>

                    </a>

                </div>

                <div class="card-body p-0">

                    @forelse($investigations as $investigation)

                        <div class="border-bottom p-3">

                            <div class="d-flex justify-content-between align-items-start">

                                <div>

                                    <strong>
                                        {{ $investigation->investigation_name }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ optional($investigation->created_at)->format('M d, Y') }}
                                    </small>

                                </div>

                                @if($investigation->status == 'completed')

                                    <span class="badge bg-success">
                                        Completed
                                    </span>

                                @elseif($investigation->status == 'pending')

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Requested
                                    </span>

                                @endif

                            </div>

                            @if($investigation->result)

                                <div class="alert alert-light border mt-3 mb-0">

                                    <small class="text-muted d-block mb-1">
                                        Result
                                    </small>

                                    {{ Str::limit($investigation->result, 150) }}

                                </div>

                            @endif

                            <div class="mt-3 d-flex gap-2">

                                <a href="{{ route('midwife.anc-investigation.show', $investigation) }}"
                                   class="btn btn-sm btn-outline-primary">

                                    <i class="bi bi-eye"></i>
                                    View

                                </a>

                                <a href="{{ route('midwife.anc-investigation.edit', $investigation) }}"
                                   class="btn btn-sm btn-outline-secondary">

                                    <i class="bi bi-pencil"></i>
                                    Edit

                                </a>

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-5">

                            <i class="bi bi-clipboard-x display-5 text-muted"></i>

                            <h6 class="mt-3">
                                No Investigations
                            </h6>

                            <p class="text-muted small px-3">
                                No ANC investigations have been requested yet.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

            <!-- Patient Quick Info -->
            <div class="card shadow-sm">

                <div class="card-header bg-light">

                    <h6 class="mb-0">
                        <i class="bi bi-person-vcard"></i>
                        Patient Information
                    </h6>

                </div>

                <div class="card-body">

                    <small class="text-muted">

                        <strong>Patient Name:</strong>
                        <br>
                        {{ $patient->full_name }}

                        <hr>

                        <strong>Hospital Number:</strong>
                        <br>
                        {{ $patient->hospital_number }}

                        <hr>

                        <strong>Phone Number:</strong>
                        <br>
                        {{ $patient->phone_number ?? 'N/A' }}

                        <hr>

                        <strong>Last ANC Visit:</strong>
                        <br>
                        {{ optional($latestAncVisit?->created_at)->format('M d, Y h:i A') ?? 'N/A' }}

                    </small>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

