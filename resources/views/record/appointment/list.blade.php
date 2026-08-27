@extends('layouts.app')

@section('title', 'Appointments')

@section('header')
<div class="d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-calendar-event text-success" style="font-size: 2rem;"></i>
        <div>
            <h1 class="h3 mb-1">All Appointments</h1>
            <p class="mb-0 text-muted">Total: <strong class="text-success">{{ $appointments->total() }} appointments</strong></p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-calendar3 text-success me-2"></i>Scheduled Appointments
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 datatable">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-person me-2"></i>Patient</th>
                                <th><i class="bi bi-hash me-2"></i>Hospital Number</th>
                                <th><i class="bi bi-calendar me-2"></i>Date</th>
                                <th><i class="bi bi-clock me-2"></i>Time</th>
                                <th class="text-center"><i class="bi bi-info-circle me-2"></i>Status</th>
                                <th><i class="bi bi-person-check me-2"></i>Scheduled By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appointments as $appointment)
                                <tr class="align-middle">
                                    <td class="fw-500">{{ $appointment->patient->demographic->full_name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $appointment->patient->hospital_number }}</span>
                                    </td>
                                    <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                    <td>{{ $appointment->appointment_time }}</td>
                                    <td class="text-center">
                                        @if($appointment->status == 'Scheduled')
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Scheduled</span>
                                        @elseif($appointment->status == 'Cancelled')
                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Cancelled</span>
                                        @elseif($appointment->status == 'Completed')
                                            <span class="badge bg-info"><i class="bi bi-check-all me-1"></i>Completed</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>{{ $appointment->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $appointment->scheduledBy->name ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                            <p class="mt-2">No appointments scheduled yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($appointments->hasPages())
                <div class="card-footer bg-light">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
