@extends('layouts.app')

@section('title', 'Record Officer Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #e7f1ff;">
                            <i class="bi bi-people-fill text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Patients</h6>
                            <h3 class="h4 mb-0">{{ App\Models\Patient::count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #e8f8f5;">
                            <i class="bi bi-stethoscope text-success" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Today's Visits</h6>
                            <h3 class="h4 mb-0">{{ App\Models\PatientVisit::whereDate('visit_date', today())->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #fff8e1;">
                            <i class="bi bi-file-medical text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Active Records</h6>
                            <h3 class="h4 mb-0">{{ App\Models\Patient::where('is_walkIn', false)->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #e0f7fa;">
                            <i class="bi bi-person-check text-info" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Walk-In Patients</h6>
                            <h3 class="h4 mb-0">{{ App\Models\WalkinPatient::all()->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-fill text-warning me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-lg-3">
                            <a href="{{ route('record.patients.register.form') }}" class="btn btn-outline-primary w-100 py-3 text-start">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-plus me-2" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <div class="fw-bold">Register Patient</div>
                                        <small class="text-muted">Add new patient record</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <a href="{{ route('record.patients.index') }}" class="btn btn-outline-info w-100 py-3 text-start">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-list-check me-2" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <div class="fw-bold">View Patients</div>
                                        <small class="text-muted">Browse all records</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <a href="{{ route('record.patient-register.index') }}" class="btn btn-outline-secondary w-100 py-3 text-start">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-spreadsheet me-2" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <div class="fw-bold">Patient Register</div>
                                        <small class="text-muted">Filter and export records</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <a href="{{ route('patient.search') }}" class="btn btn-outline-success w-100 py-3 text-start">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-search me-2" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <div class="fw-bold">Search Patient</div>
                                        <small class="text-muted">Find by phone/number</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recently Registered Patients -->
    @if(App\Models\Patient::whereDate('created_at', today())->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history text-info me-2"></i>Recently Registered Patients
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="bi bi-hash me-2"></i>Hospital Number</th>
                                        <th><i class="bi bi-person me-2"></i>Name</th>
                                        <th><i class="bi bi-telephone me-2"></i>Phone</th>
                                        <th><i class="bi bi-calendar-check me-2"></i>Registration Date</th>
                                        <th class="text-center no-export">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(App\Models\Patient::whereDate('created_at', today())->latest('registration_date')->limit(5)->get() as $patient)
                                        <tr class="align-middle">
                                            <td>
                                                <span class="badge bg-primary">{{ $patient->hospital_number }}</span>
                                            </td>
                                            <td>{{ $patient->demographic->full_name ?? 'N/A' }}</td>
                                            <td>{{ $patient->demographic->phone_number ?? 'N/A' }}</td>
                                            <td>{{ $patient->registration_date->format('M d, Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('patient.show', $patient) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye me-1"></i>View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .btn-outline-primary, .btn-outline-info, .btn-outline-success, .btn-outline-danger {
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover, .btn-outline-info:hover, .btn-outline-success:hover, .btn-outline-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
</style>
@endsection
