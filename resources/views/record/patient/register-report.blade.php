@extends('layouts.app')

@section('title', 'Patient Register')

@section('content')
@php
    $exportParams = request()->only(['start_date', 'end_date', 'search', 'gender', 'patient_type']);
@endphp

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 d-flex align-items-center">
            <i class="bi bi-file-earmark-spreadsheet text-success me-2"></i>
            Patient Register
        </h1>
        <p class="text-muted mb-0">{{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}</p>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('record.patient-register.csv', $exportParams) }}" class="btn btn-outline-success">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i>
            CSV
        </a>
        <a href="{{ route('record.patient-register.pdf', $exportParams) }}" class="btn btn-outline-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i>
            PDF
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Total</div>
                <div class="h4 mb-0">{{ number_format($summary['total']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Registered</div>
                <div class="h4 mb-0">{{ number_format($summary['registered']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Walk-in</div>
                <div class="h4 mb-0">{{ number_format($summary['walk_in']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Male</div>
                <div class="h4 mb-0">{{ number_format($summary['male']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Female</div>
                <div class="h4 mb-0">{{ number_format($summary['female']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Other</div>
                <div class="h4 mb-0">{{ number_format($summary['other']) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('record.patient-register.index') }}" class="row g-3 align-items-end">
            <div class="col-12 col-lg-3">
                <label for="search" class="form-label">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="search" id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Hospital no, name, phone">
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <label for="start_date" class="form-label">From</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="form-control">
            </div>

            <div class="col-6 col-lg-2">
                <label for="end_date" class="form-label">To</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="form-control">
            </div>

            <div class="col-6 col-lg-2">
                <label for="gender" class="form-label">Gender</label>
                <select id="gender" name="gender" class="form-select">
                    <option value="">All</option>
                    <option value="Male" @selected(request('gender') === 'Male')>Male</option>
                    <option value="Female" @selected(request('gender') === 'Female')>Female</option>
                    <option value="Other" @selected(request('gender') === 'Other')>Other</option>
                </select>
            </div>

            <div class="col-6 col-lg-2">
                <label for="patient_type" class="form-label">Type</label>
                <select id="patient_type" name="patient_type" class="form-select">
                    <option value="">All</option>
                    <option value="registered" @selected(request('patient_type') === 'registered')>Registered</option>
                    <option value="walk_in" @selected(request('patient_type') === 'walk_in')>Walk-in</option>
                </select>
            </div>

            <div class="col-12 col-lg-1 d-grid">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-funnel"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Hospital No</th>
                    <th>Patient</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Phone</th>
                    <th>File Type</th>
                    <th>Type</th>
                    <th>Registered</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr>
                        <td><span class="badge bg-primary">{{ $patient->hospital_number }}</span></td>
                        <td>
                            <div class="fw-semibold">{{ $patient->demographic?->full_name ?? 'N/A' }}</div>
                            <div class="text-muted small">{{ $patient->demographic?->email ?? 'No email' }}</div>
                        </td>
                        <td>{{ $patient->demographic?->gender ?? 'N/A' }}</td>
                        <td>{{ $patient->demographic?->age ?? 'N/A' }}</td>
                        <td>{{ $patient->demographic?->phone_number ?? 'N/A' }}</td>
                        <td>{{ $patient->fileType?->name ?? 'General file' }}</td>
                        <td>
                            <span class="badge {{ $patient->is_walkIn ? 'bg-warning text-dark' : 'bg-success' }}">
                                {{ $patient->is_walkIn ? 'Walk-in' : 'Registered' }}
                            </span>
                        </td>
                        <td>{{ $patient->registration_date?->format('M d, Y') ?? 'N/A' }}</td>
                        <td class="text-end">
                            <a href="{{ route('record.patients.show', $patient) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">No patients found for this register period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($patients->hasPages())
        <div class="card-footer bg-light">
            {{ $patients->links() }}
        </div>
    @endif
</div>
@endsection
