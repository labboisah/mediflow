<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h4 mb-1">{{ $listMode === 'deleted' ? 'Deleted Bills' : ($listMode === 'unpaid' ? 'Unpaid Bills' : 'Bills Management') }}</h1>
            <p class="text-muted mb-0">
                @if($listMode === 'deleted')
                    Soft-deleted bills are shown here for restore.
                @elseif($listMode === 'unpaid')
                    All unpaid bills are shown here for accountant correction.
                @else
                    Bills you created today are shown here.
                @endif
            </p>
        </div>

        <div class="d-flex gap-2">
            @if($mode === 'index')
                <a href="{{ route('accountant.bills.unpaid') }}" class="btn btn-outline-danger">
                    <i class="bi bi-receipt-cutoff"></i> Unpaid Bills
                </a>
                <a href="{{ route('accountant.bills.deleted') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i> Deleted Bills
                </a>
                <button type="button" class="btn btn-primary" wire:click="createMode">
                    <i class="bi bi-plus-circle"></i> New Bill
                </button>
            @else
                <button type="button" class="btn btn-outline-secondary" wire:click="indexMode">
                    <i class="bi bi-arrow-left"></i> Back to Bills
                </button>
            @endif
        </div>
    </div>

    @if($mode === 'index')
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted small mb-1">{{ $listMode === 'deleted' ? 'Deleted Bills' : ($listMode === 'unpaid' ? 'Unpaid Bills' : "Today's Bills") }}</p>
                        <h3 class="h4 mb-0">{{ number_format($summary['count']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Gross Amount</p>
                        <h3 class="h4 mb-0">&#8358;{{ number_format($summary['amount'], 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Discount</p>
                        <h3 class="h4 mb-0">&#8358;{{ number_format($summary['discount'], 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Amount Due</p>
                        <h3 class="h4 mb-0">&#8358;{{ number_format($summary['due'], 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-2 align-items-end mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Search</label>
                        <input type="search" class="form-control" wire:model.live.debounce.400ms="search" placeholder="Bill number, patient, or description">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" wire:model.live="status">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="partial">Partial</option>
                            <option value="paid">Paid</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Rows</label>
                        <select class="form-select" wire:model.live="perPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Bill</th>
                                <th>Patient</th>
                                <th>Description</th>
                                <th class="text-end">Amount</th>
                                <th class="text-end">Due</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bills as $bill)
                                @php
                                    $patient = $bill->walkinPatient?->name
                                        ?? $bill->patientVisit?->patient?->demographic?->getFullNameAttribute()
                                        ?? 'N/A';
                                    $hospitalNumber = $bill->walkinPatient ? 'Walk-in' : ($bill->patientVisit?->patient?->hospital_number ?? 'N/A');
                                @endphp
                                <tr wire:key="bill-{{ $bill->id }}">
                                    <td>
                                        <div class="fw-semibold">{{ $bill->bill_number }}</div>
                                        <small class="text-muted">{{ optional($bill->issued_date)->format('M d, Y h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $patient }}</div>
                                        <small class="text-muted">{{ $hospitalNumber }}</small>
                                    </td>
                                    <td>{{ $bill->service_description }}</td>
                                    <td class="text-end">&#8358;{{ number_format($bill->amount, 2) }}</td>
                                    <td class="text-end">&#8358;{{ number_format($bill->due_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $bill->status === 'paid' ? 'success' : ($bill->status === 'partial' ? 'warning' : ($bill->status === 'cancelled' ? 'secondary' : 'danger')) }}">
                                            {{ ucfirst($bill->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        @if($listMode === 'deleted')
                                            <button type="button" class="btn btn-sm btn-outline-success" wire:click="restore({{ $bill->id }})" wire:confirm="Restore this bill?">
                                                <i class="bi bi-arrow-counterclockwise"></i> Restore
                                            </button>
                                        @else
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('accountant.bills.show', $bill) }}" class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($bill->canBeManagedAsUnpaidByAccountant(auth()->user()))
                                                    <button type="button" class="btn btn-outline-warning" wire:click="edit({{ $bill->id }})">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" wire:click="delete({{ $bill->id }})" wire:confirm="Soft delete this unpaid bill? It can be restored later.">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No bill found for this view.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $bills->links() }}
                </div>
            </div>
        </div>
    @else
        <form wire:submit="save">
            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white">
                            <h2 class="h6 mb-0">{{ $isEditing ? 'Edit Bill' : 'Create Bill' }}</h2>
                        </div>
                        <div class="card-body">
                            @error('bill')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            @error('items')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            @if($isEditing)
                                <div class="alert alert-info">
                                    Patient details are locked for existing bills. Create a new bill if the patient was selected incorrectly.
                                </div>
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Hospital Number</label>
                                    <input type="text" class="form-control @error('hospitalNumber') is-invalid @enderror" wire:model.live.debounce.500ms="hospitalNumber" placeholder="Use for registered patient" @disabled($isEditing)>
                                    @error('hospitalNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    @if($selectedPatient)
                                        <div class="form-text text-success">
                                            {{ $selectedPatient->demographic?->getFullNameAttribute() }} found.
                                        </div>
                                    @elseif($hospitalNumber !== '')
                                        <div class="form-text text-danger">No patient found with this hospital number.</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Walk-in Patient Name</label>
                                    <input type="text" class="form-control @error('walkinName') is-invalid @enderror" wire:model.live="walkinName" placeholder="Use when patient is not registered" @disabled($isEditing)>
                                    @error('walkinName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Walk-in Phone</label>
                                    <input type="text" class="form-control @error('walkinPhone') is-invalid @enderror" wire:model.live="walkinPhone" @disabled($isEditing)>
                                    @error('walkinPhone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Walk-in Email</label>
                                    <input type="email" class="form-control @error('walkinEmail') is-invalid @enderror" wire:model.live="walkinEmail" @disabled($isEditing)>
                                    @error('walkinEmail') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h2 class="h6 mb-0">Services</h2>
                            <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addService">
                                <i class="bi bi-plus"></i> Add Service
                            </button>
                        </div>
                        <div class="card-body">
                            @foreach($services as $index => $serviceRow)
                                <div class="row g-2 align-items-end mb-2" wire:key="service-row-{{ $index }}">
                                    <div class="col-md-8">
                                        <label class="form-label">Service</label>
                                        <select class="form-select @error('services.' . $index . '.id') is-invalid @enderror" wire:model.live="services.{{ $index }}.id">
                                            <option value="">Select service</option>
                                            @foreach($serviceGroups as $group => $groupServices)
                                                <optgroup label="{{ $group ?: 'Other' }}">
                                                    @foreach($groupServices as $service)
                                                        <option value="{{ $service->id }}">{{ $service->name }} - &#8358;{{ number_format($service->price, 2) }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        @error('services.' . $index . '.id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Qty</label>
                                        <input type="number" min="1" class="form-control @error('services.' . $index . '.quantity') is-invalid @enderror" wire:model.live="services.{{ $index }}.quantity">
                                        @error('services.' . $index . '.quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-outline-danger w-100" wire:click="removeService({{ $index }})">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h2 class="h6 mb-0">Investigations</h2>
                            <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addInvestigation">
                                <i class="bi bi-plus"></i> Add Investigation
                            </button>
                        </div>
                        <div class="card-body">
                            @forelse($investigations as $index => $investigationRow)
                                <div class="row g-2 align-items-end mb-2" wire:key="investigation-row-{{ $index }}">
                                    <div class="col-md-8">
                                        <label class="form-label">Investigation</label>
                                        <select class="form-select @error('investigations.' . $index . '.id') is-invalid @enderror" wire:model.live="investigations.{{ $index }}.id">
                                            <option value="">Select investigation</option>
                                            @foreach($investigationGroups as $group => $groupInvestigations)
                                                <optgroup label="{{ $group ?: 'Other' }}">
                                                    @foreach($groupInvestigations as $investigation)
                                                        <option value="{{ $investigation->id }}">{{ $investigation->name }} - &#8358;{{ number_format($investigation->price, 2) }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        @error('investigations.' . $index . '.id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Qty</label>
                                        <input type="number" min="1" class="form-control @error('investigations.' . $index . '.quantity') is-invalid @enderror" wire:model.live="investigations.{{ $index }}.quantity">
                                        @error('investigations.' . $index . '.quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-outline-danger w-100" wire:click="removeInvestigation({{ $index }})">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No investigation selected.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm position-sticky" style="top: 1rem;">
                        <div class="card-header bg-white">
                            <h2 class="h6 mb-0">Bill Summary</h2>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Issued Date</label>
                                <input type="date" class="form-control @error('issuedDate') is-invalid @enderror" wire:model.live="issuedDate" @if(! $isEditing) min="{{ today()->toDateString() }}" max="{{ today()->toDateString() }}" readonly @endif>
                                @error('issuedDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Due Date</label>
                                <input type="date" class="form-control @error('dueDate') is-invalid @enderror" wire:model.live="dueDate">
                                @error('dueDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Discount (%)</label>
                                <input type="number" min="0" max="100" class="form-control @error('discount') is-invalid @enderror" wire:model.live="discount">
                                @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            @if($isEditing)
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select @error('billStatus') is-invalid @enderror" wire:model.live="billStatus">
                                        <option value="pending">Pending</option>
                                        <option value="partial">Partial</option>
                                        <option value="paid">Paid</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                    @error('billStatus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <hr>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Amount</span>
                                <strong>&#8358;{{ number_format($totals['amount'], 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount</span>
                                <strong>&#8358;{{ number_format($totals['discount'], 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between fs-5">
                                <span>Due</span>
                                <strong>&#8358;{{ number_format($totals['due'], 2) }}</strong>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mt-3" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="save">
                                    <i class="bi bi-save"></i> {{ $isEditing ? 'Update Bill' : 'Save Bill' }}
                                </span>
                                <span wire:loading wire:target="save">Saving...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>
