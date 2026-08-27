@extends('layouts.app')

@section('title', 'Child Follow-up Records')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-arrow-repeat"></i> Child Follow-up Records</h1>
            <small class="text-muted">Search and manage all child follow-up records</small>
        </div>
        <a href="{{ route('midwife.child-follow-up-management') }}" class="btn btn-primary">
            <i class="bi bi-diagram-3"></i> Direct Child Follow-up Entry
        </a>
    </div>

    <form method="GET" action="{{ route('midwife.child-follow-up.index') }}" class="card card-body mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-10">
                <label class="form-label">Search</label>
                <input type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Search by hospital number, mother name, phone, period, or health status">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Search</button>
            </div>
        </div>
    </form>

    @if($childFollowUps->isEmpty())
        <div class="alert alert-info">No child follow-up records found.</div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Hospital #</th>
                                <th>Mother</th>
                                <th>Newborn #</th>
                                <th>Period</th>
                                <th>Weight</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($childFollowUps as $record)
                                @php($patient = $record->patient)
                                <tr>
                                    <td>{{ $record->follow_up_date_time?->format('M d, Y h:i A') }}</td>
                                    <td>{{ $patient?->hospital_number ?? 'N/A' }}</td>
                                    <td>{{ $patient?->name() ?? 'N/A' }}</td>
                                    <td>{{ $record->newborn?->newborn_registration_number ?? 'N/A' }}</td>
                                    <td>{{ $record->follow_up_period ? str($record->follow_up_period)->headline() : 'N/A' }}</td>
                                    <td>{{ $record->weight ?? 'N/A' }}</td>
                                    <td><span class="badge bg-secondary">{{ str($record->health_status)->headline() }}</span></td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('midwife.child-follow-up.show', $record) }}" class="btn btn-outline-primary">View</a>
                                            <a href="{{ route('midwife.child-follow-up.edit', $record) }}" class="btn btn-outline-secondary">Edit</a>
                                            @if($patient)
                                                <a href="{{ route('midwife.patient.show', $patient) }}" class="btn btn-outline-info">Profile</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted">Total: <strong>{{ $childFollowUps->count() }}</strong> records</div>
        </div>
    @endif
</div>
@endsection
