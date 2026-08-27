@extends('layouts.app')

@section('title', 'Pharmacy Transaction Report')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Transaction Report</h1>
            <p class="text-muted mb-0">Pharmacy sales and dispensing summary.</p>
        </div>
        <a href="{{ route('pharmacy.transactions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Transactions
        </a>
    </div>

    <form method="GET" action="{{ route('pharmacy.transactions.report') }}" class="card shadow-sm mb-3">
        <div class="card-body row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">From</label>
                <input type="date" name="from" value="{{ $from }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">To</label>
                <input type="date" name="to" value="{{ $to }}" class="form-control">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
            </div>
        </div>
    </form>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="border rounded bg-white p-3">
                <div class="text-muted small">Transactions</div>
                <div class="h4 mb-0">{{ number_format($transactions->count()) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="border rounded bg-white p-3">
                <div class="text-muted small">Items Dispensed</div>
                <div class="h4 mb-0">{{ number_format($transactions->flatMap->stockTransactionItems->sum('quantity')) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="border rounded bg-white p-3">
                <div class="text-muted small">Total Amount</div>
                <div class="h4 mb-0">&#8358;{{ number_format($transactions->sum('total_amount'), 2) }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Medicines</th>
                        <th>Created By</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->created_at?->format('M d, Y h:i A') }}</td>
                            <td>{{ $transaction->reference ?? $transaction->id }}</td>
                            <td>
                                @foreach($transaction->stockTransactionItems as $item)
                                    <div>
                                        {{ $item->medicineBatch?->medicine?->name ?? 'N/A' }}
                                        <span class="text-muted">x {{ $item->quantity }}</span>
                                    </div>
                                @endforeach
                            </td>
                            <td>{{ $transaction->createdBy?->name ?? 'System' }}</td>
                            <td class="text-end">&#8358;{{ number_format($transaction->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No transaction found for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
