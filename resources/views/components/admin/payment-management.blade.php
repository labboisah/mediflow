<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Payments Management</h1>
            <p class="text-muted mb-0">Review, correct, reverse, and remove recorded payments.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ $pdfUrl }}" target="_blank" class="btn btn-outline-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i>
                Download PDF
            </a>

            @if($canManageFinance)
                <button type="button" class="btn btn-success" wire:click="resetForm">
                    <i class="bi bi-plus-circle me-1"></i>
                    Record Payment
                </button>
            @endif
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="border rounded p-3 bg-white h-100">
                <p class="text-muted small mb-1">Completed Amount</p>
                <h4 class="text-success mb-0">{{ number_format($totalAmount, 2) }}</h4>
            </div>
        </div>

        <div class="col-md-4">
            <div class="border rounded p-3 bg-white h-100">
                <p class="text-muted small mb-1">Matching Payments</p>
                <h4 class="mb-0">{{ number_format($paymentCount) }}</h4>
            </div>
        </div>

        <div class="col-md-4">
            <div class="border rounded p-3 bg-white h-100">
                <p class="text-muted small mb-1">Reversed Payments</p>
                <h4 class="text-warning mb-0">{{ number_format($reversedCount) }}</h4>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Filters</h5>
        </div>

        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="search" class="form-control" wire:model.live.debounce.400ms="search" placeholder="Payment ID, bill number, patient, reference">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" wire:model.live="status">
                        <option value="">All Statuses</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                        <option value="reversed">Reversed</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Method</label>
                    <select class="form-select" wire:model.live="method">
                        <option value="">All Methods</option>
                        @foreach($paymentMethods as $paymentMethod)
                            <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Recorded By</label>
                    <select class="form-select" wire:model.live="recordedBy">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">From</label>
                    <input type="date" class="form-control" wire:model.live="dateFrom">
                </div>

                <div class="col-md-1">
                    <label class="form-label">To</label>
                    <input type="date" class="form-control" wire:model.live="dateTo">
                </div>

                <div class="col-md-12">
                    <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="resetFilters">
                        <i class="bi bi-x-circle me-1"></i>
                        Clear Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if($canManageFinance)
    <div class="card shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    @if($editingPaymentId)
                        Edit Payment: {{ $paymentId }}
                    @else
                        Record Payment
                    @endif
                </h5>

                @if($editingPaymentId)
                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="resetForm">
                    <i class="bi bi-x-lg"></i>
                </button>
                @endif
            </div>

            <div class="card-body">
                <form wire:submit="save">
                    <div class="row g-3">
                        @if(!$editingPaymentId)
                            <div class="col-md-3">
                                <label class="form-label">Bill Number</label>
                                <input type="text" class="form-control @error('billNumber') is-invalid @enderror" wire:model="billNumber" placeholder="BL2600001">
                                @error('billNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @endif

                        <div class="col-md-3">
                            <label class="form-label">Amount</label>
                            <input type="number" step="0.01" min="0.01" class="form-control @error('amount') is-invalid @enderror" wire:model="amount">
                            @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Payment Method</label>
                            <select class="form-select @error('paymentMethodId') is-invalid @enderror" wire:model="paymentMethodId">
                                <option value="">Select Method</option>
                                @foreach($paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                                @endforeach
                            </select>
                            @error('paymentMethodId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Payment Date</label>
                            <input type="datetime-local" class="form-control @error('paymentDate') is-invalid @enderror" wire:model="paymentDate">
                            @error('paymentDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select @error('paymentStatus') is-invalid @enderror" wire:model="paymentStatus">
                                <option value="completed">Completed</option>
                                <option value="pending">Pending</option>
                                <option value="failed">Failed</option>
                                <option value="reversed">Reversed</option>
                            </select>
                            @error('paymentStatus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Reference Number</label>
                            <input type="text" class="form-control @error('referenceNumber') is-invalid @enderror" wire:model="referenceNumber">
                            @error('referenceNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Insurance Provider</label>
                            <input type="text" class="form-control @error('insuranceProvider') is-invalid @enderror" wire:model="insuranceProvider">
                            @error('insuranceProvider') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Notes</label>
                            <input type="text" class="form-control @error('notes') is-invalid @enderror" wire:model="notes">
                            @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
                                <span wire:loading.remove wire:target="save">
                                    <i class="bi bi-check-circle me-1"></i>
                                    @if($editingPaymentId)
                                        Save Changes
                                    @else
                                        Record Payment
                                    @endif
                                </span>
                                <span wire:loading wire:target="save">Saving...</span>
                            </button>

                            <button type="button" class="btn btn-outline-secondary" wire:click="resetForm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">All Payments</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Payment ID</th>
                            <th>Patient</th>
                            <th>Bill Number</th>
                            <th class="text-end">Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Recorded By</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($payments as $payment)
                            @php
                                $patientName = $payment->bill?->walkinPatient?->name
                                    ?? $payment->bill?->patientVisit?->patient?->name()
                                    ?? 'N/A';
                            @endphp

                            <tr wire:key="payment-{{ $payment->id }}">
                                <td><strong>{{ $payment->payment_id }}</strong></td>
                                <td>{{ $patientName }}</td>
                                <td>
                                    @if($payment->bill)
                                        <a href="{{ route($routePrefix . '.bills.show', $payment->bill) }}">{{ $payment->bill->bill_number }}</a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-success">{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->paymentMethod->name ?? 'N/A' }}</td>
                                <td>{{ $payment->payment_date?->format('M d, Y H:i') ?? 'N/A' }}</td>
                                <td>
                                    @if($payment->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($payment->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($payment->status === 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-secondary">Reversed</span>
                                    @endif
                                </td>
                                <td>{{ $payment->recordedBy->name ?? 'N/A' }}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route($routePrefix . '.payments.receipt', $payment) }}" class="btn btn-outline-secondary" title="Receipt">
                                            <i class="bi bi-printer"></i>
                                        </a>

                                        @if($canManageFinance)
                                            <button type="button" class="btn btn-outline-warning" title="Edit" wire:click="edit({{ $payment->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            @if($payment->status !== 'reversed')
                                                <button type="button" class="btn btn-outline-dark" title="Reverse" wire:click="reverse({{ $payment->id }})" wire:confirm="Reverse this payment?">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            @endif

                                            <button type="button" class="btn btn-outline-danger" title="Delete" wire:click="delete({{ $payment->id }})" wire:confirm="Delete this payment permanently?">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    No payments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($payments->hasPages())
            <div class="card-footer">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
