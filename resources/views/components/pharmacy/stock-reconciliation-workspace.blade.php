<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h4 class="mb-1"><i class="bi bi-clipboard-check"></i> Stock Reconciliation</h4>
            <div class="text-muted small">Compare physical pharmacy stock with system records and keep a referenced audit trail.</div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('pharmacy.stocks.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Inventory
            </a>
            <button type="button" class="btn btn-outline-primary" wire:click="fillVisibleWithSystemCounts">
                <i class="bi bi-copy"></i> Fill System Qty
            </button>
            <button type="button" class="btn btn-outline-danger" wire:click="clearCounts">
                <i class="bi bi-x-circle"></i> Clear
            </button>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Batches In View</div>
                    <div class="fs-4 fw-bold">{{ number_format($summary['batches']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">System Quantity</div>
                    <div class="fs-4 fw-bold">{{ number_format($summary['quantity']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Entered Counts</div>
                    <div class="fs-4 fw-bold">{{ collect($physicalCounts)->filter(fn($value) => trim((string) $value) !== '')->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Search</label>
                    <input type="search" class="form-control" wire:model.live.debounce.400ms="search" placeholder="Medicine, generic, company, or batch">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Expiry</label>
                    <select class="form-select" wire:model.live="expiryStatus">
                        <option value="">All</option>
                        <option value="valid">Valid</option>
                        <option value="expiring">Expiring in 60 days</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Check Note</label>
                    <input type="text" class="form-control @error('notes') is-invalid @enderror" wire:model="notes" placeholder="Optional note for this stock check">
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    @error('physicalCounts')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <form wire:submit="saveReconciliation">
        <div class="card shadow-sm mb-3">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Batch</th>
                            <th>Expiry</th>
                            <th class="text-end">System Qty</th>
                            <th class="text-end">Physical Qty</th>
                            <th class="text-end">Variance</th>
                            <th>Line Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($batches as $batch)
                            @php
                                $physical = trim((string) ($physicalCounts[$batch->id] ?? ''));
                                $variance = $physical === '' ? null : ((int) $physical - (int) $batch->quantity_remaining);
                            @endphp
                            <tr wire:key="reconcile-batch-{{ $batch->id }}">
                                <td>
                                    <div class="fw-semibold">{{ $batch->medicine?->name ?? 'N/A' }}</div>
                                    <div class="small text-muted">{{ $batch->medicine?->medicineType?->name }} {{ $batch->medicine?->strength }}</div>
                                </td>
                                <td>{{ $batch->batch_number }}</td>
                                <td>{{ $batch->expiry_date }}</td>
                                <td class="text-end">{{ number_format($batch->quantity_remaining) }}</td>
                                <td style="min-width: 130px;">
                                    <input type="number" min="0" class="form-control form-control-sm text-end @error('physicalCounts.' . $batch->id) is-invalid @enderror" wire:model.live="physicalCounts.{{ $batch->id }}">
                                    @error('physicalCounts.' . $batch->id)<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </td>
                                <td class="text-end">
                                    @if($variance === null)
                                        <span class="text-muted">-</span>
                                    @elseif($variance === 0)
                                        <span class="badge bg-success">0</span>
                                    @elseif($variance > 0)
                                        <span class="badge bg-primary">+{{ number_format($variance) }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ number_format($variance) }}</span>
                                    @endif
                                </td>
                                <td style="min-width: 180px;">
                                    <input type="text" class="form-control form-control-sm @error('itemNotes.' . $batch->id) is-invalid @enderror" wire:model="itemNotes.{{ $batch->id }}" placeholder="Reason if different">
                                    @error('itemNotes.' . $batch->id)<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No stock batch found for this filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>{{ $batches->links() }}</div>
                <button type="submit" class="btn btn-success" wire:loading.attr="disabled" wire:target="saveReconciliation">
                    <i class="bi bi-check2-circle"></i>
                    <span wire:loading.remove wire:target="saveReconciliation">Save Reconciliation</span>
                    <span wire:loading wire:target="saveReconciliation">Saving...</span>
                </button>
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-clock-history"></i> Recent Stock Reconciliations
        </div>
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Date</th>
                        <th>Checked By</th>
                        <th class="text-end">Batches</th>
                        <th class="text-end">System</th>
                        <th class="text-end">Physical</th>
                        <th class="text-end">Variance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($summary['recent'] as $reconciliation)
                        <tr>
                            <td class="fw-semibold">{{ $reconciliation->reference }}</td>
                            <td>{{ $reconciliation->checked_date?->format('M d, Y') }}</td>
                            <td>{{ $reconciliation->checkedBy?->name ?? 'System' }}</td>
                            <td class="text-end">{{ number_format($reconciliation->items_count) }}</td>
                            <td class="text-end">{{ number_format($reconciliation->total_system_quantity) }}</td>
                            <td class="text-end">{{ number_format($reconciliation->total_physical_quantity) }}</td>
                            <td class="text-end">
                                @if($reconciliation->total_variance === 0)
                                    <span class="badge bg-success">0</span>
                                @elseif($reconciliation->total_variance > 0)
                                    <span class="badge bg-primary">+{{ number_format($reconciliation->total_variance) }}</span>
                                @else
                                    <span class="badge bg-danger">{{ number_format($reconciliation->total_variance) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No reconciliation has been recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
