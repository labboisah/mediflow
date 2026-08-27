<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Bills Management</h1>
            <p class="text-muted mb-0">Review, filter, correct, and manage patient bills.</p>
        </div>
        <a href="{{ $pdfUrl }}" target="_blank" class="btn btn-outline-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i>
            Download PDF
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="border rounded p-3 bg-white h-100">
                <p class="text-muted small mb-1">Matching Bills</p>
                <h4 class="mb-0">{{ number_format($billCount) }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="border rounded p-3 bg-white h-100">
                <p class="text-muted small mb-1">Total Amount</p>
                <h4 class="text-primary mb-0">{{ number_format($totalAmount, 2) }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="border rounded p-3 bg-white h-100">
                <p class="text-muted small mb-1">Total Due</p>
                <h4 class="text-success mb-0">{{ number_format($totalDue, 2) }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="border rounded p-3 bg-white h-100">
                <p class="text-muted small mb-1">Open Bills</p>
                <h4 class="text-warning mb-0">{{ number_format($pendingCount) }}</h4>
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
                    <input type="search" class="form-control" wire:model.live.debounce.400ms="search" placeholder="Bill number, patient, service">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" wire:model.live="status">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="partial">Partial</option>
                        <option value="paid">Paid</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Issued By</label>
                    <select class="form-select" wire:model.live="issuedBy">
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

    @if($canManageFinance && $editingBillId)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Bill: {{ $billNumber }}</h5>

                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="resetForm">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="card-body">
                <form wire:submit="save">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Service Description</label>
                            <input type="text" class="form-control @error('serviceDescription') is-invalid @enderror" wire:model="serviceDescription">
                            @error('serviceDescription') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Amount</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('amount') is-invalid @enderror" wire:model="amount">
                            @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Discount (%)</label>
                            <input type="number" step="0.01" min="0" max="100" class="form-control @error('discount') is-invalid @enderror" wire:model="discount">
                            @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Issued Date</label>
                            <input type="date" class="form-control @error('issuedDate') is-invalid @enderror" wire:model="issuedDate">
                            @error('issuedDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Due Date</label>
                            <input type="date" class="form-control @error('dueDate') is-invalid @enderror" wire:model="dueDate">
                            @error('dueDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select @error('billStatus') is-invalid @enderror" wire:model="billStatus">
                                <option value="pending">Pending</option>
                                <option value="partial">Partial</option>
                                <option value="paid">Paid</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            @error('billStatus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
                                <span wire:loading.remove wire:target="save">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Save Changes
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
            <h5 class="mb-0">All Bills</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Bill Number</th>
                            <th>Patient</th>
                            <th>Service Description</th>
                            <th class="text-end">Amount</th>
                            <th class="text-end">Due</th>
                            <th>Status</th>
                            <th>Consistency</th>
                            <th>Issued By</th>
                            <th>Issued Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($bills as $bill)
                            @php
                                $patientName = $bill->walkinPatient?->name
                                    ?? $bill->patientVisit?->patient?->name()
                                    ?? 'N/A';
                            @endphp

                            <tr wire:key="bill-{{ $bill->id }}">
                                <td><strong>{{ $bill->bill_number }}</strong></td>
                                <td>{{ $patientName }}</td>
                                <td>{{ Str::limit($bill->service_description, 35) }}</td>
                                <td class="text-end">{{ number_format($bill->amount, 2) }}</td>
                                <td class="text-end fw-bold">{{ number_format($bill->due_amount, 2) }}</td>
                                <td>
                                    @if($bill->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($bill->status === 'partial')
                                        <span class="badge bg-warning">Partial</span>
                                    @elseif($bill->status === 'pending')
                                        <span class="badge bg-danger">Pending</span>
                                    @else
                                        <span class="badge bg-secondary">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    @if($bill->isAmountConsistent())
                                        <span class="text-success"><i class="bi bi-check-circle"></i> Consistent</span>
                                    @else
                                        <span class="text-danger"><i class="bi bi-exclamation-triangle"></i> Inconsistent</span>
                                    @endif
                                </td>
                                <td>{{ $bill->issuedBy->name ?? 'N/A' }}</td>
                                <td>{{ $bill->issued_date?->format('M d, Y') ?? 'N/A' }}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route($routePrefix . '.bills.show', $bill) }}" class="btn btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if($canManageFinance)
                                            <button type="button" class="btn btn-outline-warning" title="Edit" wire:click="edit({{ $bill->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <button type="button" class="btn btn-outline-danger" title="Delete" wire:click="delete({{ $bill->id }})" wire:confirm="Delete this bill?">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    No bills found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($bills->hasPages())
            <div class="card-footer">
                {{ $bills->links() }}
            </div>
        @endif
    </div>
</div>
