@extends('layouts.app')

@section('title', 'Newborn Records')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-bandaid-fill"></i> Newborn Records</h1>
            <small class="text-muted">Search and manage all newborn records</small>
        </div>
        <a href="{{ route('midwife.newborn-management') }}" class="btn btn-primary">
            <i class="bi bi-diagram-3"></i> Direct Newborn Entry
        </a>
    </div>

    <form method="GET" action="{{ route('midwife.newborn.index') }}" class="card card-body mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-10">
                <label class="form-label">Search</label>
                <input type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Search by hospital number, mother name, phone, registration number, or status">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Search</button>
            </div>
        </div>
    </form>

    @if($newborns->isEmpty())
        <div class="alert alert-info">No newborn records found.</div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Birth Date</th>
                                <th>Registration #</th>
                                <th>Mother</th>
                                <th>Sex</th>
                                <th>Weight</th>
                                <th>Status</th>
                                <th>Recorded By</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($newborns as $record)
                                @php($patient = $record->patient)
                                <tr>
                                    <td>{{ $record->birth_date_time?->format('M d, Y h:i A') }}</td>
                                    <td>{{ $record->newborn_registration_number ?? 'N/A' }}</td>
                                    <td>{{ $patient?->name() ?? 'N/A' }}</td>
                                    <td>{{ str($record->sex)->headline() }}</td>
                                    <td>{{ $record->birth_weight ?? 'N/A' }}</td>
                                    <td><span class="badge bg-secondary">{{ str($record->status)->headline() }}</span></td>
                                    <td>{{ $record->recordedBy?->name ?? 'N/A' }}</td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('midwife.newborn.show', $record) }}" class="btn btn-outline-primary">View</a>
                                            <a href="{{ route('midwife.newborn.edit', $record) }}" class="btn btn-outline-secondary">Edit</a>
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
            <div class="card-footer text-muted">Total: <strong>{{ $newborns->count() }}</strong> records</div>
        </div>
    @endif
</div>
@endsection
