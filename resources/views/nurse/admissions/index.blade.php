@extends('layouts.app')

@section('title', 'Admitted Patients')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-hospital"></i> Admitted Patients</h1>
            <small class="text-muted">Patients currently admitted to wards or beds</small>
        </div>
    </div>

    <form method="GET" action="{{ route('nurse.admissions.index') }}" class="card card-body mb-3">
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

    @if($admissions->isEmpty())
        <div class="alert alert-info">No admitted patients found.</div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Hospital #</th>
                                <th>Patient</th>
                                <th>Phone</th>
                                <th>Ward / Bed</th>
                                <th>Admission Date</th>
                                <th>Status</th>
                                <th>Admitted By</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admissions as $admission)
                                @php($patient = $admission->patientVisit?->patient)
                                <tr>
                                    <td>{{ $patient?->hospital_number ?? 'N/A' }}</td>
                                    <td>{{ $patient?->name() ?? 'N/A' }}</td>
                                    <td>{{ $patient?->demographic?->phone_number ?? 'N/A' }}</td>
                                    <td>{{ $admission->bed?->ward?->name ?? 'N/A' }} / {{ $admission->bed?->bed_no ?? 'N/A' }}</td>
                                    <td>{{ $admission->date ? date('M d, Y', strtotime($admission->date)) : 'N/A' }} {{ $admission->time }}</td>
                                    <td><span class="badge bg-secondary">{{ str($admission->status ?? 'registered')->headline() }}</span></td>
                                    <td>{{ $admission->admittedBy?->name ?? 'N/A' }}</td>
                                    <td class="text-end">
                                        @if($patient)
                                            <a href="{{ route('nurse.patient.show', $patient) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> View Profile
                                            </a>
                                            <a href="{{ route('nurse.admissions.record-absconded', $admission) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to mark this patient as absconded?');">
                                                <i class="bi bi-x-circle"></i> Mark as Absconded
                                            </a>
                                            <a href="{{ route('nurse.admissions.record-sama', $admission) }}" class="btn btn-sm btn-outline-warning" onclick="return confirm('Are you sure you want to mark this patient as Sign Against Medical Advice?');">
                                                <i class="bi bi-check-circle"></i> Mark as SAMA
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted">Total: <strong>{{ $admissions->count() }}</strong> admitted patients</div>
        </div>
    @endif
</div>
@endsection
