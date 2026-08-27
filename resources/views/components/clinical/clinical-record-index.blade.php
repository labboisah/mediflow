<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h4 mb-1"><i class="bi {{ $config['icon'] }} me-2 text-success"></i>{{ $config['title'] }}</h1>
            <p class="text-muted mb-0">Recorded clinicals across patients. Open a row to edit from that patient workspace.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row g-2 align-items-end mb-3">
                <div class="col-md-8">
                    <label class="form-label">Search patient</label>
                    <input type="search" class="form-control" wire:model.live.debounce.400ms="search" placeholder="Hospital number or patient name">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Rows</label>
                    <select class="form-select" wire:model.live="perPage">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>

            @include("components.clinical.record-indexes.{$type}", [
                'records' => $records,
                'config' => $config,
            ])

            {{ $records->links() }}
        </div>
    </div>
</div>
