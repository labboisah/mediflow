<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h4 class="mb-1"><i class="bi bi-layers"></i> Medicine Batches</h4>
            <div class="text-muted small">Editable batch records that feed the stock inventory summary</div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('pharmacy.stocks.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-box-seam"></i> Stock
            </a>
            <a href="{{ route('pharmacy.stocks.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Stock
            </a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Batches</div>
                    <div class="fs-4 fw-bold">{{ number_format($summary['batches']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Available Quantity</div>
                    <div class="fs-4 fw-bold">{{ number_format($summary['quantity']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Purchase Value</div>
                    <div class="fs-4 fw-bold">₦{{ number_format($summary['purchase_value'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Retail Value</div>
                    <div class="fs-4 fw-bold">₦{{ number_format($summary['retail_value'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="search" class="form-control" wire:model.live.debounce.400ms="search" placeholder="Medicine or batch">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Medicine</label>
                    <select class="form-select" wire:model.live="medicineId">
                        <option value="">All medicines</option>
                        @foreach($medicines as $medicine)
                            <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Expiry</label>
                    <select class="form-select" wire:model.live="expiryStatus">
                        <option value="">All</option>
                        <option value="valid">Valid</option>
                        <option value="expiring">Expiring in 60 days</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">From</label>
                    <input type="date" class="form-control" wire:model.live="from">
                </div>
                <div class="col-md-2">
                    <label class="form-label">To</label>
                    <input type="date" class="form-control" wire:model.live="to">
                </div>
                <div class="col-12">
                    <button type="button" class="btn btn-outline-secondary" wire:click="resetFilters">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if($editingBatchId)
        <div class="card shadow-sm mb-3 border-warning">
            <div class="card-header bg-warning-subtle d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-pencil-square"></i> Edit Batch</span>
                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="cancelEdit">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="updateBatch">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Medicine</label>
                            <select class="form-select @error('batchForm.medicine_id') is-invalid @enderror" wire:model="batchForm.medicine_id">
                                <option value="">Select medicine</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                @endforeach
                            </select>
                            @error('batchForm.medicine_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Batch No <span class="text-muted small">(optional)</span></label>
                            <input type="text" class="form-control @error('batchForm.batch_number') is-invalid @enderror" wire:model="batchForm.batch_number">
                            @error('batchForm.batch_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Received</label>
                            <input type="number" min="0" class="form-control @error('batchForm.quantity_received') is-invalid @enderror" wire:model="batchForm.quantity_received">
                            @error('batchForm.quantity_received') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Remaining</label>
                            <input type="number" min="0" class="form-control @error('batchForm.quantity_remaining') is-invalid @enderror" wire:model="batchForm.quantity_remaining">
                            @error('batchForm.quantity_remaining') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Purchase Price</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('batchForm.purchase_price') is-invalid @enderror" wire:model="batchForm.purchase_price">
                            @error('batchForm.purchase_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Selling Price</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('batchForm.selling_price') is-invalid @enderror" wire:model="batchForm.selling_price">
                            @error('batchForm.selling_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Manufacture Date <span class="text-muted small">(optional)</span></label>
                            <input type="date" class="form-control @error('batchForm.manufacture_date') is-invalid @enderror" wire:model="batchForm.manufacture_date">
                            @error('batchForm.manufacture_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Expiry Date <span class="text-muted small">(optional)</span></label>
                            <input type="date" class="form-control @error('batchForm.expiry_date') is-invalid @enderror" wire:model="batchForm.expiry_date">
                            @error('batchForm.expiry_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-outline-secondary" wire:click="cancelEdit">Cancel</button>
                        <button type="submit" class="btn btn-warning" wire:loading.attr="disabled" wire:target="updateBatch">
                            <i class="bi bi-check-circle"></i> Update Batch
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Batch No</th>
                        <th>Received</th>
                        <th>Remaining</th>
                        <th>Purchase</th>
                        <th>Selling</th>
                        <th>Retail Value</th>
                        <th>Expiry</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $batch)
                        @php
                            $expired = \Carbon\Carbon::parse($batch->expiry_date)->isPast();
                            $expiring = ! $expired && \Carbon\Carbon::parse($batch->expiry_date)->lte(today()->addDays(60));
                        @endphp
                        <tr wire:key="medicine-batch-row-{{ $batch->id }}">
                            <td>
                                <div class="fw-semibold">{{ $batch->medicine?->name ?? 'N/A' }}</div>
                                <div class="small text-muted">{{ $batch->medicine?->medicineType?->name }} {{ $batch->medicine?->strength }}</div>
                            </td>
                            <td>{{ $batch->batch_number }}</td>
                            <td>{{ number_format($batch->quantity_received) }}</td>
                            <td><span class="badge bg-success">{{ number_format($batch->quantity_remaining) }}</span></td>
                            <td>₦{{ number_format($batch->purchase_price, 2) }}</td>
                            <td>₦{{ number_format($batch->selling_price, 2) }}</td>
                            <td>₦{{ number_format($batch->quantity_remaining * $batch->selling_price, 2) }}</td>
                            <td>{{ $batch->expiry_date }}</td>
                            <td>
                                @if($expired)
                                    <span class="badge bg-danger">Expired</span>
                                @elseif($expiring)
                                    <span class="badge bg-warning text-dark">Expiring</span>
                                @else
                                    <span class="badge bg-primary">Valid</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-warning" wire:click="editBatch({{ $batch->id }})">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">No medicine batches found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white">
            {{ $batches->links() }}
        </div>
    </div>
</div>
