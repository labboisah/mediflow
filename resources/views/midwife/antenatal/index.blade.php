@extends('layouts.app')

@section('title', 'Antenatal Care Records')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-heart-pulse-fill"></i> Antenatal Care Records</h1>
            <small class="text-muted">Search and manage all antenatal records</small>
        </div>
        <a href="{{ route('midwife.anc-management') }}" class="btn btn-primary">
            <i class="bi bi-diagram-3"></i> Direct ANC Entry
        </a>
    </div>

    <form method="GET" action="{{ route('midwife.antenatal.index') }}" class="card card-body mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-10">
                <label class="form-label">Search</label>
                <input type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Search by hospital number, patient name, phone, or status">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Search</button>
            </div>
        </div>
    </form>

    @if($antenatalRecords->isEmpty())
        <div class="alert alert-info">No antenatal care records found.</div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Hospital #</th>
                                <th>Patient</th>
                                <th>Phone</th>
                                <th>Gestation</th>
                                <th>Status</th>
                                <th>Recorded By</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($antenatalRecords as $record)
                                @php($patient = $record->patient)
                                <tr>
                                    <td>{{ $record->created_at?->format('M d, Y h:i A') }}</td>
                                    <td>{{ $patient?->hospital_number ?? 'N/A' }}</td>
                                    <td>{{ $patient?->name() ?? 'N/A' }}</td>
                                    <td>{{ $patient?->demographic?->phone_number ?? 'N/A' }}</td>
                                    <td>{{ $record->gestational_weeks ? $record->gestational_weeks . ' weeks' : 'N/A' }}</td>
                                    <td><span class="badge bg-secondary">{{ str($record->status)->headline() }}</span></td>
                                    <td>{{ $record->recordedBy?->name ?? 'N/A' }}</td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('midwife.antenatal.show', $record) }}" class="btn btn-outline-primary">View</a>
                                            <a href="{{ route('midwife.antenatal.edit', $record) }}" class="btn btn-outline-secondary">Edit</a>
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
            <div class="card-footer text-muted">Total: <strong>{{ $antenatalRecords->count() }}</strong> records</div>
        </div>
    @endif
</div>
@endsection
