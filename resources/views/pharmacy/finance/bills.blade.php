@extends('layouts.app')

@section('title', 'Pharmacy Bills')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Pharmacy Bills</h1>
            <p class="text-muted mb-0">Bills generated from pharmacy transactions.</p>
        </div>
        <a href="{{ route('pharmacy.transactions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> New Transaction
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Bill No</th>
                        <th>Medicines</th>
                        <th>Status</th>
                        <th>Collected By</th>
                        <th>Date</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end">Paid</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        @php($bill = $transaction->bill)
                        <tr>
                            <td>{{ $bill?->bill_number ?? 'N/A' }}</td>
                            <td>
                                @foreach($transaction->stockTransactionItems as $item)
                                    <div>
                                        {{ $item->medicineBatch?->medicine?->name ?? 'N/A' }}
                                        <span class="text-muted">x {{ $item->quantity }}</span>
                                    </div>
                                @endforeach
                            </td>
                            <td><span class="badge bg-success">{{ ucfirst($bill?->status ?? 'paid') }}</span></td>
                            <td>{{ $transaction->createdBy?->name ?? 'System' }}</td>
                            <td>{{ $bill?->issued_date?->format('M d, Y h:i A') ?? $transaction->created_at?->format('M d, Y h:i A') }}</td>
                            <td class="text-end">&#8358;{{ number_format($bill?->due_amount ?? $transaction->total_amount, 2) }}</td>
                            <td class="text-end">&#8358;{{ number_format($bill?->totalPaid() ?? $transaction->payment?->amount ?? 0, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No pharmacy bills found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="card-footer bg-white">{{ $transactions->links() }}</div>
        @endif
    </div>
</div>
@endsection
