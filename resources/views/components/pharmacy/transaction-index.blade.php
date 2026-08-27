<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h4 mb-1"><i class="bi bi-receipt me-2 text-success"></i>Transactions</h1>
            <p class="text-muted mb-0">Search, filter, and review pharmacy transactions.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pharmacy.transactions.report') }}" class="btn btn-outline-secondary">
                <i class="bi bi-graph-up me-1"></i> Report
            </a>
            <a href="{{ route('pharmacy.transactions.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Transaction
            </a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="border rounded bg-white p-3 h-100">
                <div class="text-muted small">Transactions</div>
                <div class="h4 mb-0">{{ number_format($summary['count']) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border rounded bg-white p-3 h-100">
                <div class="text-muted small">Transaction Amount</div>
                <div class="h4 mb-0">&#8358;{{ number_format($summary['amount'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border rounded bg-white p-3 h-100">
                <div class="text-muted small">Payment Collected</div>
                <div class="h4 mb-0">&#8358;{{ number_format($summary['payments'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border rounded bg-white p-3 h-100">
                <div class="text-muted small">Items Dispensed</div>
                <div class="h4 mb-0">{{ number_format($summary['items']) }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-4">
                    <label class="form-label">Search</label>
                    <input type="search" class="form-control" wire:model.live.debounce.300ms="search" placeholder="Medicine, bill, receipt, reference, staff">
                </div>
                <div class="col-md-2">
                    <label class="form-label">From</label>
                    <input type="date" class="form-control" wire:model.live="from">
                </div>
                <div class="col-md-2">
                    <label class="form-label">To</label>
                    <input type="date" class="form-control" wire:model.live="to">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Payment Method</label>
                    <select class="form-select" wire:model.live="paymentMethod">
                        <option value="">All methods</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Created By</label>
                    <select class="form-select" wire:model.live="createdBy">
                        <option value="">All staff</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="resetFilters">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filters
                    </button>
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
                        <th>Medicines</th>
                        <th>Bill</th>
                        <th>Payment</th>
                        <th>Method</th>
                        <th>Created By</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr wire:key="transaction-{{ $transaction->id }}">
                            <td>{{ $transaction->created_at?->format('M d, Y h:i A') }}</td>
                            <td>
                                @foreach($transaction->stockTransactionItems as $item)
                                    <div>
                                        {{ $item->medicineBatch?->medicine?->name ?? 'N/A' }}
                                        <span class="text-muted">x {{ $item->quantity }}</span>
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                {{ $transaction->bill?->bill_number ?? 'N/A' }}
                                <div class="small text-muted">{{ ucfirst($transaction->bill?->status ?? 'unknown') }}</div>
                            </td>
                            <td>
                                {{ $transaction->payment?->payment_id ?? 'N/A' }}
                                @if($transaction->payment?->reference_number)
                                    <div class="small text-muted">Ref: {{ $transaction->payment->reference_number }}</div>
                                @endif
                            </td>
                            <td>{{ $transaction->payment?->paymentMethod?->name ?? 'N/A' }}</td>
                            <td>{{ $transaction->createdBy?->name ?? 'System' }}</td>
                            <td class="text-end">&#8358;{{ number_format($transaction->total_amount, 2) }}</td>
                            <td class="text-end">
                                @if($transaction->payment)
                                    <a href="{{ route('pharmacy.finance.payments.receipt', $transaction->payment) }}" class="btn btn-sm btn-outline-primary">
                                        Receipt
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No transaction found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="card-footer bg-white">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
