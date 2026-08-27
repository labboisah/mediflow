@extends('layouts.app')

@section('title', 'Pharmacy Financial Report')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Pharmacy Financial Report</h1>
            <p class="text-muted mb-0">Bills, payments, and transaction totals for dispensed medicines.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pharmacy.finance.report.download', ['from' => $from, 'to' => $to]) }}" class="btn btn-success">
                <i class="bi bi-download me-1"></i> Download Report
            </a>
            <a href="{{ route('pharmacy.finance.payments') }}" class="btn btn-outline-secondary">
                <i class="bi bi-credit-card-2-front me-1"></i> Payments
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('pharmacy.finance.report') }}" class="card shadow-sm mb-3">
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
        <div class="col-md-2">
            <div class="border rounded bg-white p-3">
                <div class="text-muted small">Transactions</div>
                <div class="h4 mb-0">{{ number_format($summary['transactions']) }}</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="border rounded bg-white p-3">
                <div class="text-muted small">Bills</div>
                <div class="h4 mb-0">&#8358;{{ number_format($summary['bills'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="border rounded bg-white p-3">
                <div class="text-muted small">Payments</div>
                <div class="h4 mb-0">&#8358;{{ number_format($summary['payments'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="border rounded bg-white p-3">
                <div class="text-muted small">Stock Cost</div>
                <div class="h4 mb-0">&#8358;{{ number_format($summary['cost'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="border rounded bg-white p-3">
                <div class="text-muted small">Gross Profit</div>
                <div class="h4 mb-0 {{ $summary['profit'] >= 0 ? 'text-success' : 'text-danger' }}">&#8358;{{ number_format($summary['profit'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="border rounded bg-white p-3">
                <div class="text-muted small">Items Dispensed</div>
                <div class="h4 mb-0">{{ number_format($summary['items']) }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Revenue, Cost, and Profit</div>
                <div class="card-body">
                    @php $maxTotal = max(collect($chartTotals)->max('value') ?: 1, 1); @endphp
                    @foreach($chartTotals as $bar)
                        @php $width = max(3, abs($bar['value']) / $maxTotal * 100); @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>{{ $bar['label'] }}</span>
                                <strong>&#8358;{{ number_format($bar['value'], 2) }}</strong>
                            </div>
                            <div class="progress" style="height: 18px;">
                                <div class="progress-bar" style="width: {{ $width }}%; background: {{ $bar['color'] }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Daily Gross Profit</div>
                <div class="card-body">
                    @php $maxDaily = max($dailyProfit->max('value') ?: 1, 1); @endphp
                    @forelse($dailyProfit as $day)
                        @php $width = max(3, abs($day['value']) / $maxDaily * 100); @endphp
                        <div class="row align-items-center g-2 mb-2">
                            <div class="col-3 small text-muted">{{ $day['label'] }}</div>
                            <div class="col-6">
                                <div class="progress" style="height: 14px;">
                                    <div class="progress-bar bg-primary" style="width: {{ $width }}%;"></div>
                                </div>
                            </div>
                            <div class="col-3 small text-end">&#8358;{{ number_format($day['value'], 2) }}</div>
                        </div>
                    @empty
                        <div class="text-muted text-center py-4">No profit chart data for this period.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Bill</th>
                        <th>Receipt</th>
                        <th>Medicines</th>
                        <th>Collected By</th>
                        <th class="text-end">Cost</th>
                        <th class="text-end">Profit</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactionRows as $row)
                        @php $transaction = $row['transaction']; @endphp
                        <tr>
                            <td>{{ $transaction->created_at?->format('M d, Y h:i A') }}</td>
                            <td>{{ $transaction->bill?->bill_number ?? 'N/A' }}</td>
                            <td>{{ $transaction->payment?->payment_id ?? 'N/A' }}</td>
                            <td>
                                @foreach($transaction->stockTransactionItems as $item)
                                    <div>
                                        {{ $item->medicineBatch?->medicine?->name ?? 'N/A' }}
                                        <span class="text-muted">x {{ $item->quantity }}</span>
                                    </div>
                                @endforeach
                            </td>
                            <td>{{ $transaction->createdBy?->name ?? 'System' }}</td>
                            <td class="text-end">&#8358;{{ number_format($row['cost'], 2) }}</td>
                            <td class="text-end {{ $row['profit'] >= 0 ? 'text-success' : 'text-danger' }}">&#8358;{{ number_format($row['profit'], 2) }}</td>
                            <td class="text-end">&#8358;{{ number_format($row['revenue'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No pharmacy finance record found for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
