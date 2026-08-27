<div class="container-fluid py-3" wire:poll.visible.20s="refreshList">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h4 mb-1">Payment Record Management</h1>
            <p class="text-muted mb-0">Search bill, select bill(s), record payment and generate receipt.</p>
        </div>

        <a href="{{ route('accountant.bills.payments.verify') }}" class="btn btn-outline-success">
            <i class="bi bi-search"></i> Verify Bill
        </a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Matching Payments</p>
                    <h3 class="h4 mb-0">{{ number_format($summary['count']) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Completed Amount</p>
                    <h3 class="h4 mb-0 text-success">
                        &#8358;{{ number_format($summary['completedAmount'], 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Pending Payments</p>
                    <h3 class="h4 mb-0 text-warning">{{ number_format($summary['pendingCount']) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Reversed Payments</p>
                    <h3 class="h4 mb-0 text-secondary">{{ number_format($summary['reversedCount']) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ $editingPaymentId ? 'Edit Payment: ' . $paymentId : 'Record Payment' }}
            </h5>

            @if($editingPaymentId)
                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="resetForm">
                    <i class="bi bi-x-lg"></i>
                </button>
            @endif
        </div>

        <div class="card-body">
            <form wire:submit.prevent="save">

                <div class="row g-3">

                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Bill Number</label>

                        <div class="input-group">
                            <input type="text"
                                   class="form-control @error('billNumber') is-invalid @enderror"
                                   wire:model.defer="billNumber"
                                   placeholder="Enter bill number"
                                   @if($editingPaymentId) readonly @endif>

                            @if(! $editingPaymentId)
                                <button type="button"
                                        class="btn btn-success"
                                        wire:click="loadBills"
                                        wire:loading.attr="disabled"
                                        wire:target="loadBills">
                                    <span wire:loading.remove wire:target="loadBills">
                                        <i class="bi bi-search"></i> Search
                                    </span>

                                    <span wire:loading wire:target="loadBills">
                                        Searching...
                                    </span>
                                </button>
                            @endif
                        </div>

                        @error('billNumber')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select @error('paymentMethodId') is-invalid @enderror"
                                wire:model="paymentMethodId">
                            <option value="">Select Method</option>
                            @foreach($paymentMethods as $paymentMethod)
                                <option value="{{ $paymentMethod->id }}">
                                    {{ $paymentMethod->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('paymentMethodId')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Payment Date</label>
                        <input type="datetime-local"
                               class="form-control @error('paymentDate') is-invalid @enderror"
                               wire:model="paymentDate">

                        @error('paymentDate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Status</label>
                        <select class="form-select @error('paymentStatus') is-invalid @enderror"
                                wire:model="paymentStatus">
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                            <option value="failed">Failed</option>
                            <option value="reversed">Reversed</option>
                        </select>

                        @error('paymentStatus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Reference Number</label>
                        <input type="text"
                               class="form-control @error('referenceNumber') is-invalid @enderror"
                               wire:model="referenceNumber">

                        @error('referenceNumber')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Insurance Provider</label>
                        <input type="text"
                               class="form-control @error('insuranceProvider') is-invalid @enderror"
                               wire:model="insuranceProvider">

                        @error('insuranceProvider')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-12">
                        <label class="form-label">Notes</label>
                        <input type="text"
                               class="form-control @error('notes') is-invalid @enderror"
                               wire:model="notes">

                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($editingPaymentId && $selectedBill)
                        @php
                            $patientName = $selectedBill->walkinPatient?->name
                                ?? $selectedBill->patientVisit?->patient?->name()
                                ?? 'N/A';
                        @endphp

                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                <div class="d-flex flex-wrap justify-content-between gap-2">
                                    <span><strong>Patient:</strong> {{ $patientName }}</span>
                                    <span><strong>Bill:</strong> {{ $selectedBill->bill_number }}</span>
                                    <span><strong>Due:</strong> &#8358;{{ number_format($selectedBill->due_amount, 2) }}</span>
                                    <span><strong>Balance:</strong> &#8358;{{ number_format($selectedBill->balance, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Amount</label>
                            <input type="number"
                                   step="0.01"
                                   min="0.01"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   wire:model="amount">

                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    @if(! $editingPaymentId && count($availableBills ?? []))
                        <div class="col-12">

                            <div class="alert alert-light border mb-2">
                                <strong>Available Bills:</strong>
                                Select only the bill(s) the patient wants to pay now.
                                Unselected bills will remain pending.
                            </div>

                            @error('selectedBills')
                                <div class="alert alert-danger py-2">{{ $message }}</div>
                            @enderror

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60px;">Select</th>
                                            <th>Bill No</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th class="text-end">Amount</th>
                                            <th class="text-end">Due</th>
                                            <th class="text-end">Balance</th>
                                            <th style="width: 180px;">Paying Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($availableBills as $bill)
                                            <tr wire:key="available-bill-{{ $bill['id'] }}">
                                                <td class="text-center">
                                                    <input type="checkbox"
                                                           class="form-check-input"
                                                           wire:model.live="selectedBills"
                                                           value="{{ $bill['id'] }}">
                                                </td>

                                                <td class="fw-semibold">
                                                    {{ $bill['bill_number'] }}
                                                </td>

                                                <td>
                                                    {{ $bill['description'] }}
                                                </td>

                                                <td>
                                                    <span class="badge bg-{{ $bill['status'] === 'partial' ? 'warning' : 'secondary' }}">
                                                        {{ ucfirst($bill['status']) }}
                                                    </span>
                                                </td>

                                                <td class="text-end">
                                                    &#8358;{{ number_format($bill['amount'], 2) }}
                                                </td>

                                                <td class="text-end">
                                                    &#8358;{{ number_format($bill['due_amount'], 2) }}
                                                </td>

                                                <td class="text-end fw-semibold text-danger">
                                                    &#8358;{{ number_format($bill['balance'], 2) }}
                                                </td>

                                                <td>
                                                    <input type="number"
                                                           step="0.01"
                                                           min="0.01"
                                                           max="{{ $bill['balance'] }}"
                                                           class="form-control @error('billAmounts.' . $bill['id']) is-invalid @enderror"
                                                           wire:model.defer="billAmounts.{{ $bill['id'] }}">

                                                    @error('billAmounts.' . $bill['id'])
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th colspan="7" class="text-end">Selected Total</th>
                                            <th>
                                                &#8358;{{ number_format(
                                                    collect($selectedBills ?? [])->sum(
                                                        fn ($billId) => (float) ($billAmounts[$billId] ?? 0)
                                                    ),
                                                    2
                                                ) }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if(! $editingPaymentId && $billNumber && empty($availableBills ?? []))
                        <div class="col-12">
                            <div class="alert alert-warning mb-0">
                                No pending or partial bill found for this search.
                            </div>
                        </div>
                    @endif

                    <div class="col-12 d-flex flex-wrap gap-2">
                        <button type="submit"
                                class="btn btn-primary"
                                wire:loading.attr="disabled"
                                wire:target="save">

                            <span wire:loading.remove wire:target="save">
                                <i class="bi bi-check-circle"></i>
                                {{ $editingPaymentId ? 'Save Changes' : 'Record Selected Payment' }}
                            </span>

                            <span wire:loading wire:target="save">
                                Saving...
                            </span>
                        </button>

                        <button type="button"
                                class="btn btn-outline-secondary"
                                wire:click="resetForm">
                            Cancel
                        </button>

                        @if(! empty($receiptNumber))
                            <a href="{{ route('accountant.payments.receipt.batch', $receiptNumber) }}"
                               target="_blank"
                               class="btn btn-outline-success">
                                <i class="bi bi-printer"></i>
                                Print Receipt
                            </a>
                        @endif
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="row g-2 align-items-end mb-3">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Search</label>
                    <input type="search"
                           class="form-control"
                           wire:model.live.debounce.400ms="search"
                           placeholder="Payment ID, bill, patient, phone, reference">
                </div>

                <div class="col-lg-2 col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" wire:model.live="status">
                        <option value="">All Statuses</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                        <option value="reversed">Reversed</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-3">
                    <label class="form-label">Method</label>
                    <select class="form-select" wire:model.live="method">
                        <option value="">All Methods</option>
                        @foreach($paymentMethods as $paymentMethod)
                            <option value="{{ $paymentMethod->id }}">
                                {{ $paymentMethod->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-3">
                    <label class="form-label">From</label>
                    <input type="date" class="form-control" wire:model.live="dateFrom">
                </div>

                <div class="col-lg-2 col-md-3">
                    <label class="form-label">To</label>
                    <input type="date" class="form-control" wire:model.live="dateTo">
                </div>

                <div class="col-lg-1 col-md-3">
                    <label class="form-label">Rows</label>
                    <select class="form-select" wire:model.live="perPage">
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>

                <div class="col-12">
                    <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="resetFilters">
                        <i class="bi bi-x-circle"></i> Clear Filters
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Payment</th>
                            <th>Patient</th>
                            <th>Bill</th>
                            <th class="text-end">Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($payments as $payment)
                            @php
                                $bill = $payment->bill;

                                $patientName = $bill?->walkinPatient?->name
                                    ?? $bill?->patientVisit?->patient?->name()
                                    ?? 'N/A';

                                $hospitalNumber = $bill?->walkinPatient
                                    ? 'Walk-in'
                                    : ($bill?->patientVisit?->patient?->hospital_number ?? 'N/A');
                            @endphp

                            <tr wire:key="accountant-payment-{{ $payment->id }}">
                                <td>
                                    <div class="fw-semibold">{{ $payment->payment_id }}</div>

                                    @if($payment->receipt_number ?? false)
                                        <small class="text-success d-block">
                                            Receipt: {{ $payment->receipt_number }}
                                        </small>
                                    @endif

                                    @if($payment->reference_number)
                                        <small class="text-muted">{{ $payment->reference_number }}</small>
                                    @endif
                                </td>

                                <td>
                                    <div>{{ $patientName }}</div>
                                    <small class="text-muted">{{ $hospitalNumber }}</small>
                                </td>

                                <td>
                                    @if($bill && (int) $bill->issued_by === (int) auth()->id() && $bill->issued_date?->isToday())
                                        <a href="{{ route('accountant.bills.show', $bill) }}">
                                            {{ $bill->bill_number }}
                                        </a>
                                    @elseif($bill)
                                        <span>{{ $bill->bill_number }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>

                                <td class="text-end fw-semibold text-success">
                                    &#8358;{{ number_format($payment->amount, 2) }}
                                </td>

                                <td>{{ $payment->paymentMethod?->name ?? 'N/A' }}</td>

                                <td>
                                    {{ $payment->payment_date?->format('M d, Y h:i A') ?? 'N/A' }}
                                </td>

                                <td>
                                    <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : ($payment->status === 'failed' ? 'danger' : 'secondary')) }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">

                                        @if($payment->receipt_number ?? false)
                                            <a href="{{ route('accountant.payments.receipt.batch', $payment->receipt_number) }}"
                                               class="btn btn-outline-secondary"
                                               title="Receipt">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('accountant.payments.receipt', $payment) }}"
                                               class="btn btn-outline-secondary"
                                               title="Receipt">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                        @endif

                                        <button type="button"
                                                class="btn btn-outline-warning"
                                                title="Edit"
                                                wire:click="edit({{ $payment->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        @if($payment->status !== 'reversed')
                                            <button type="button"
                                                    class="btn btn-outline-dark"
                                                    title="Reverse"
                                                    wire:click="reverse({{ $payment->id }})"
                                                    wire:confirm="Reverse this payment?">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        @endif

                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No payments found for your current filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                <small class="text-muted">
                    Auto-refreshes every 20 seconds while this page is visible.
                </small>

                {{ $payments->links() }}
            </div>

        </div>
    </div>

</div>