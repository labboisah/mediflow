@extends('layouts.app')

@section('title', 'Patient Search')

@section('header')
<div class="d-flex align-items-center gap-3">
    <i class="bi bi-search text-success" style="font-size: 2rem;"></i>
    <div>
        <h1 class="h3 mb-1">Search Patient</h1>
        <p class="mb-0 text-muted">Find patients by hospital number, payment ID, or phone number</p>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">
        <!-- Search Form -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <form action="{{ route('record_officer.patients.search') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="q" class="form-control form-control-lg" 
                           value="{{ request('q') }}" 
                           placeholder="Hospital Number, Phone Number, First Name, or Last Name" required>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-search me-2"></i>Search
                    </button>
                </form>
            </div>
        </div>

        <!-- Search Results -->
        @if(request('q'))
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul text-success me-2"></i>
                        Search Results for "<strong class="text-success">{{ request('q') }}</strong>"
                        @if($patients->count() > 0)
                            <span class="badge bg-success ms-2">{{ $patients->count() }} result{{ $patients->count() != 1 ? 's' : '' }}</span>
                        @endif
                    </h5>
                </div>
                
                @if($patients->count() > 0)
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="bi bi-hash me-2"></i>Hospital Number</th>
                                        <th><i class="bi bi-person me-2"></i>Patient Name</th>
                                        <th><i class="bi bi-telephone me-2"></i>Phone</th>
                                        <th><i class="bi bi-calendar-check me-2"></i>Registration Date</th>
                                        <th class="text-center no-export">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patients as $patient)
                                        <tr class="align-middle">
                                            <td>
                                                <span class="badge bg-primary">{{ $patient->hospital_number }}</span>
                                            </td>
                                            <td class="fw-500">{{ $patient->demographic->full_name ?? 'N/A' }}</td>
                                            <td>{{ $patient->demographic->phone_number ?? 'N/A' }}</td>
                                            <td>{{ $patient->registration_date->format('M d, Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('record_officer.patients.show', $patient) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye me-1"></i>View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-2">No patients found matching your search.</p>
                        <p class="text-muted small">Try searching with a different hospital number, payment ID, or phone number.</p>
                    </div>
                @endif
            </div>
        @else
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body text-center py-5">
                    <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">Enter a search term to find a patient</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
