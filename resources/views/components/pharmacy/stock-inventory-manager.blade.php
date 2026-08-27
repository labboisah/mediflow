<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h4 class="mb-1"><i class="bi bi-box-seam"></i> Pharmacy Inventory</h4>
            <div class="text-muted small">Medicine stock summary, stock import, and printable inventory reports</div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-outline-secondary" wire:click="downloadTemplate">
                <i class="bi bi-download"></i> Template
            </button>
            <button type="button" class="btn btn-outline-success" wire:click="exportStock">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export Stock
            </button>
            <button type="button" class="btn btn-outline-danger" wire:click="downloadStockPdf">
                <i class="bi bi-file-earmark-pdf"></i> Stock PDF
            </button>
            <button type="button" class="btn btn-outline-primary" wire:click="exportFinance">
                <i class="bi bi-cash-stack"></i> Export Finance
            </button>
            @if(auth()->user()?->hasRole('head_of_department'))
                <a href="{{ route('pharmacy.stocks.reconciliation') }}" class="btn btn-outline-info">
                    <i class="bi bi-clipboard-check"></i> Reconciliation
                </a>
            @endif
            <a href="{{ route('pharmacy.batches.index') }}" class="btn btn-outline-warning">
                <i class="bi bi-layers"></i> Batches
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
                    <div class="text-muted small">Medicines / Batches</div>
                    <div class="fs-4 fw-bold">{{ number_format($summary['medicines']) }} / {{ number_format($summary['batches']) }}</div>
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
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Sales / Purchases</div>
                    <div class="fw-bold">₦{{ number_format($summary['sales'], 2) }}</div>
                    <div class="small text-muted">Purchases: ₦{{ number_format($summary['purchases'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="search" class="form-control" wire:model.live.debounce.400ms="search" placeholder="Medicine, generic, or company">
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
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-cloud-upload"></i> Import Medicine Stock From Old System
        </div>
        <div class="card-body">
            <form wire:submit="importStock">
                <div class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label">CSV file</label>
                        <input type="file" class="form-control @error('importFile') is-invalid @enderror" wire:model="importFile" accept=".csv,text/csv">
                        @error('importFile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success w-100" wire:loading.attr="disabled" wire:target="importFile,importStock">
                            <i class="bi bi-upload"></i> Import Stock
                        </button>
                    </div>
                </div>

                <div class="mt-3" x-data="{ uploading: false, progress: 0 }"
                     x-on:livewire-upload-start="uploading = true; progress = 0"
                     x-on:livewire-upload-finish="uploading = false; progress = 100"
                     x-on:livewire-upload-error="uploading = false"
                     x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <div x-show="uploading" class="progress" style="height: 22px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" x-bind:style="`width: ${progress}%`" x-text="`${progress}%`"></div>
                    </div>
                    <div wire:loading wire:target="importStock" class="text-muted small mt-2">
                        Processing uploaded stock records...
                    </div>
                </div>
            </form>

            @if(! empty($importSummary))
                <div class="alert alert-info mt-3 mb-0">
                    Processed {{ $importSummary['processed'] }} rows, imported {{ $importSummary['imported'] }}, skipped {{ $importSummary['skipped'] }}.
                    @if(! empty($importSummary['errors']))
                        <div class="small mt-2">{{ implode(' ', array_slice($importSummary['errors'], 0, 5)) }}</div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Batches</th>
                        <th>Received</th>
                        <th>Remaining</th>
                        <th>Avg Purchase</th>
                        <th>Next Selling</th>
                        <th>Retail Value</th>
                        <th>Nearest Expiry</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $medicine)
                        @php
                            $medicineBatches = $medicine->batches;
                            $quantityReceived = (int) $medicineBatches->sum('quantity_received');
                            $quantityRemaining = (int) $medicineBatches->sum('quantity_remaining');
                            $purchaseValue = (float) $medicineBatches->sum(fn($batch) => $batch->quantity_remaining * $batch->purchase_price);
                            $retailValue = (float) $medicineBatches->sum(fn($batch) => $batch->quantity_remaining * $batch->selling_price);
                            $averagePurchase = $quantityRemaining > 0 ? $purchaseValue / $quantityRemaining : 0;
                            $nextBatch = $medicineBatches->where('quantity_remaining', '>', 0)->where('expiry_date', '>=', today()->toDateString())->sortBy('expiry_date')->first();
                            $nearestExpiry = $medicineBatches->where('quantity_remaining', '>', 0)->sortBy('expiry_date')->first()?->expiry_date;
                            $expired = $nearestExpiry ? \Carbon\Carbon::parse($nearestExpiry)->isPast() : false;
                            $expiring = $nearestExpiry && ! $expired && \Carbon\Carbon::parse($nearestExpiry)->lte(today()->addDays(60));
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $medicine->name }}</div>
                                <div class="small text-muted">{{ $medicine->medicineType?->name }} {{ $medicine->strength }}</div>
                            </td>
                            <td>{{ number_format($medicineBatches->count()) }}</td>
                            <td>{{ number_format($quantityReceived) }}</td>
                            <td><span class="badge bg-success">{{ number_format($quantityRemaining) }}</span></td>
                            <td>₦{{ number_format($averagePurchase, 2) }}</td>
                            <td>₦{{ number_format($nextBatch?->selling_price ?? 0, 2) }}</td>
                            <td>₦{{ number_format($retailValue, 2) }}</td>
                            <td>{{ $nearestExpiry ?? 'N/A' }}</td>
                            <td>
                                @if($quantityRemaining <= 0)
                                    <span class="badge bg-secondary">Out</span>
                                @elseif($expired)
                                    <span class="badge bg-danger">Expired</span>
                                @elseif($expiring)
                                    <span class="badge bg-warning text-dark">Expiring</span>
                                @else
                                    <span class="badge bg-primary">Valid</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('pharmacy.batches.index', ['medicine' => $medicine->id]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View Batches
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">No medicine stock found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white">
            {{ $stocks->links() }}
        </div>
    </div>
</div>
