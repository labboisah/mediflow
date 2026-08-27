@extends('layouts.app')

@section('title', 'Pharmacy Prescriptions')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Prescriptions</h1>
            <p class="text-muted mb-0">Submitted prescriptions awaiting pharmacy action.</p>
        </div>
        <a href="{{ route('pharmacy.transactions.create') }}" class="btn btn-primary">
            <i class="bi bi-receipt me-1"></i> New Transaction
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Medicines</th>
                        <th class="text-end">Amount</th>
                        <th>Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prescriptions as $prescription)
                        @php
                            $patient = $prescription->patientVisit?->patient;
                            $items = $prescription->prescriptionItems;
                            $amount = $items->sum(fn($item) => $item->medicine?->latestSellingPrice() ?? 0);
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $patient?->demographic?->full_name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $patient?->hospital_number ?? '' }}</small>
                            </td>
                            <td>
                                {{ $prescription->prescribedBy?->name ?? 'N/A' }}
                                <div class="small text-muted">{{ $prescription->prescribedBy?->department?->name ?? '' }}</div>
                            </td>
                            <td>
                                {{ $items->pluck('medicine.name')->filter()->take(4)->implode(', ') ?: 'No medicine' }}
                                @if($items->count() > 4)
                                    <span class="text-muted">+{{ $items->count() - 4 }} more</span>
                                @endif
                            </td>
                            <td class="text-end">&#8358;{{ number_format($amount, 2) }}</td>
                            <td>{{ $prescription->created_at?->format('M d, Y h:i A') }}</td>
                            <td class="text-end">
                                <a href="{{ route('pharmacy.prescriptions.show', $prescription) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No submitted prescriptions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($prescriptions->hasPages())
            <div class="card-footer bg-white">
                {{ $prescriptions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
