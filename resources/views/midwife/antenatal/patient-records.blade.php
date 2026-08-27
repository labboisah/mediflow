@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-0">Antenatal Care Records</h1>
            <p class="text-muted">
                {{ $patient->demographic->first_name }} 
                {{ $patient->demographic->last_name }} 
                ({{ now()->diffInYears($patient->demographic->date_of_birth) }} years)
            </p>
        </div>
        <div class="col-md-4 text-end">
            @if(auth()->user()->hasAnyRole(['midwife', 'administrator']))
                <a href="{{ route('midwife.antenatal.create', $patient) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> New Record
                </a>
                <a href="{{ route('midwife.antenatal.index') }}" class="btn btn-secondary">
                    <i class="bi bi-chevron-left"></i> Back
                </a>
            @endif
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Patient Summary Card -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Patient Summary</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="text-muted">Hospital Number</label>
                    <p class="fw-bold">{{ $patient->hospital_number }}</p>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">Age</label>
                    <p class="fw-bold">{{ $patient->age() }} years</p>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">Gender</label>
                    <p class="fw-bold">{{ $patient->demographic->gender }}</p>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">Contact</label>
                    <p class="fw-bold">{{ $patient->demographic->phone ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($antenatalRecords->count() > 0)
        <div class="card">
            <div class="card-header bg-light">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">Antenatal Care History</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="badge bg-info">{{ $antenatalRecords->count() }} Records</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Gestational Weeks</th>
                                <th>Blood Pressure</th>
                                <th>Weight</th>
                                <th>FHR (bpm)</th>
                                <th>Status</th>
                                <th>Recorded By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($antenatalRecords as $record)
                                <tr>
                                    <td>
                                        <small class="text-muted">{{ $record->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        @if($record->gestational_weeks)
                                            <span class="badge bg-secondary">{{ $record->gestational_weeks }} weeks</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $record->blood_pressure ?? '-' }}</td>
                                    <td>{{ $record->weight ? $record->weight . ' kg' : '-' }}</td>
                                    <td>{{ $record->fetal_heart_rate ?? '-' }}</td>
                                    <td>
                                        @switch($record->status)
                                            @case('normal')
                                                <span class="badge bg-success">Normal</span>
                                                @break
                                            @case('complicated')
                                                <span class="badge bg-warning">Complicated</span>
                                                @break
                                            @case('high_risk')
                                                <span class="badge bg-danger">High Risk</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">-</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <small>{{ $record->recordedBy->name ?? 'Unknown' }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('midwife.antenatal.show', $record) }}" class="btn btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if(auth()->user()->hasAnyRole(['midwife', 'administrator']))
                                                <a href="{{ route('midwife.antenatal.edit', $record) }}" class="btn btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('midwife.antenatal.destroy', $record) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete" onclick="return confirm('Are you sure?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Timeline View -->
                <div class="mt-5">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="bi bi-clock-history"></i> Complete History
                    </h6>
                    <div class="timeline">
                        @foreach($antenatalRecords as $record)
                            <div class="timeline-item mb-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="timeline-marker">
                                            <span class="badge bg-primary">{{ $record->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <p class="text-muted small">
                                            @if($record->gestational_weeks)
                                                {{ $record->gestational_weeks }} weeks
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-3">
                                                        <small class="text-muted">BP</small>
                                                        <p class="mb-0">{{ $record->blood_pressure ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <small class="text-muted">Weight</small>
                                                        <p class="mb-0">{{ $record->weight ? $record->weight . ' kg' : 'N/A' }}</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <small class="text-muted">FHR</small>
                                                        <p class="mb-0">{{ $record->fetal_heart_rate ? $record->fetal_heart_rate . ' bpm' : 'N/A' }}</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <small class="text-muted">Status</small>
                                                        <p class="mb-0">
                                                            @switch($record->status)
                                                                @case('normal')
                                                                    <span class="badge bg-success">Normal</span>
                                                                    @break
                                                                @case('complicated')
                                                                    <span class="badge bg-warning">Complicated</span>
                                                                    @break
                                                                @case('high_risk')
                                                                    <span class="badge bg-danger">High Risk</span>
                                                                    @break
                                                            @endswitch
                                                        </p>
                                                    </div>
                                                </div>

                                                @if($record->complications)
                                                    <div class="alert alert-warning small mb-3">
                                                        <strong>Complications:</strong> {{ $record->complications }}
                                                    </div>
                                                @endif

                                                @if($record->risk_factors)
                                                    <div class="alert alert-info small mb-3">
                                                        <strong>Risk Factors:</strong> {{ $record->risk_factors }}
                                                    </div>
                                                @endif

                                                <small class="text-muted">
                                                    Recorded by: {{ $record->recordedBy->name ?? 'Unknown' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox" style="font-size: 48px; color: #ccc;"></i>
                <p class="text-muted mt-3">No antenatal care records found for this patient.</p>
                @if(auth()->user()->hasAnyRole(['midwife', 'administrator']))
                    <a href="{{ route('midwife.antenatal.create', $patient) }}" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-circle"></i> Create First Record
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
    .timeline {
        position: relative;
    }

    .timeline-item {
        position: relative;
        margin-left: 0;
    }

    .timeline-marker {
        position: absolute;
        left: 0;
        top: 0;
    }
</style>
@endsection
