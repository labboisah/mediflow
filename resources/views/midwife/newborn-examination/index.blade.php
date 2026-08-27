@extends('layouts.app')

@section('title', 'Newborn Examination Records')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-clipboard2-pulse"></i> Newborn Examination Records</h1>
            <small class="text-muted">Search and manage all newborn examination records</small>
        </div>
    </div>

    <form method="GET" action="{{ route('midwife.newborn-examination.index') }}" class="card card-body mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-10">
                <label class="form-label">Search</label>
                <input type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Search by hospital number, mother name, newborn registration number, phone, or status">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Search</button>
            </div>
        </div>
    </form>

    @if($newbornExaminations->isEmpty())
        <div class="alert alert-info">No newborn examination records found.</div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Newborn #</th>
                                <th>Mother</th>
                                <th>Weight</th>
                                <th>Temperature</th>
                                <th>Status</th>
                                <th>Recorded By</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($newbornExaminations as $record)
                                @php($patient = $record->newborn?->patient)
                                <tr>
                                    <td>{{ $record->examination_date_time?->format('M d, Y h:i A') }}</td>
                                    <td>{{ $record->newborn?->newborn_registration_number ?? 'N/A' }}</td>
                                    <td>{{ $patient?->name() ?? 'N/A' }}</td>
                                    <td>{{ $record->weight ?? 'N/A' }}</td>
                                    <td>{{ $record->temperature ?? 'N/A' }}</td>
                                    <td><span class="badge bg-secondary">{{ str($record->exam_status)->headline() }}</span></td>
                                    <td>{{ $record->recordedBy?->name ?? 'N/A' }}</td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('midwife.newborn-examination.show', $record) }}" class="btn btn-outline-primary">View</a>
                                            <a href="{{ route('midwife.newborn-examination.edit', $record) }}" class="btn btn-outline-secondary">Edit</a>
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
            <div class="card-footer text-muted">Total: <strong>{{ $newbornExaminations->count() }}</strong> records</div>
        </div>
    @endif
</div>
@endsection
