@php
    $maxVisitCount = max($visitStatusRows->max('count') ?: 1, 1);
    $maxBillCount = max($billStatusRows->max('count') ?: 1, 1);
@endphp

<div wire:poll.10s>
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-speedometer2 me-2 text-success"></i>
                {{ $canViewTechnicalRecords ? 'Admin Dashboard' : 'Medical Director Dashboard' }}
            </h1>
            <p class="text-muted mb-0">{{ $canViewTechnicalRecords ? 'Live hospital, finance, access, and sync overview.' : 'Live hospital, patient flow, setup, and finance overview.' }}</p>
        </div>

        <div class="text-muted small text-end">
            <div wire:ignore>
                <i class="bi bi-calendar-event me-1"></i>
                <span id="admin-dashboard-local-date">{{ $lastUpdated->format('F j, Y') }}</span>
            </div>
            <div wire:ignore>
                <i class="bi bi-clock me-1"></i>
                <span id="admin-dashboard-local-time">{{ $lastUpdated->format('h:i:s A') }}</span>
            </div>
            <div>
                <i class="bi bi-arrow-repeat me-1"></i>
                Data refreshed {{ $lastUpdated->format('h:i:s A') }}
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="border rounded bg-white p-3 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1">Patients</p>
                        <h3 class="mb-0">{{ number_format($hospitalMetrics['patients']) }}</h3>
                        <small class="text-muted">{{ number_format($hospitalMetrics['walkin_patients']) }} walk-in</small>
                    </div>
                    <i class="bi bi-people-fill fs-2 text-success"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="border rounded bg-white p-3 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1">Active Visits</p>
                        <h3 class="mb-0">{{ number_format($hospitalMetrics['active_visits']) }}</h3>
                        <small class="text-muted">{{ number_format($hospitalMetrics['today_visits']) }} today</small>
                    </div>
                    <i class="bi bi-clipboard-pulse fs-2 text-primary"></i>
                </div>
            </div>
        </div>

        @if($canViewTechnicalRecords)
            <div class="col-md-6 col-xl-3">
                <div class="border rounded bg-white p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Collected Today</p>
                            <h3 class="mb-0">{{ number_format($financeMetrics['collected_today'], 2) }}</h3>
                            <small class="text-muted">{{ number_format($financeMetrics['payments_today']) }} payments</small>
                        </div>
                        <i class="bi bi-cash-coin fs-2 text-success"></i>
                    </div>
                </div>
            </div>
        @endif

        @if($canViewTechnicalRecords)
            <div class="col-md-6 col-xl-3">
                <div class="border rounded bg-white p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Sync Queue</p>
                            <h3 class="mb-0">{{ number_format($syncMetrics['pending']) }}</h3>
                            <small class="{{ $syncMetrics['failed'] > 0 ? 'text-danger' : 'text-muted' }}">{{ number_format($syncMetrics['failed']) }} failed</small>
                        </div>
                        <i class="bi bi-cloud-arrow-up fs-2 text-warning"></i>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row g-3 mb-4">
        @if($canViewTechnicalRecords)
            <div class="col-md-6 col-xl-3">
                <div class="border rounded bg-white p-3 h-100">
                    <p class="text-muted small mb-1">Bills</p>
                    <h4 class="mb-0">{{ number_format($financeMetrics['bills']) }}</h4>
                    <small class="text-muted">{{ number_format($financeMetrics['open_bills']) }} open, {{ number_format($financeMetrics['today_bills']) }} today</small>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="border rounded bg-white p-3 h-100">
                    <p class="text-muted small mb-1">Today Billed</p>
                    <h4 class="mb-0">{{ number_format($financeMetrics['total_billed_today'], 2) }}</h4>
                    <small class="text-muted">{{ number_format($financeMetrics['payments']) }} total payments</small>
                </div>
            </div>
        @endif

        @if($canViewTechnicalRecords)
            <div class="col-md-6 col-xl-3">
                <div class="border rounded bg-white p-3 h-100">
                    <p class="text-muted small mb-1">Access Control</p>
                    <h4 class="mb-0">{{ number_format($accessMetrics['users']) }} users</h4>
                    <small class="text-muted">{{ number_format($accessMetrics['roles']) }} roles, {{ number_format($accessMetrics['permissions']) }} permissions</small>
                </div>
            </div>
        @endif

        <div class="col-md-6 col-xl-3">
            <div class="border rounded bg-white p-3 h-100">
                <p class="text-muted small mb-1">Bed Occupancy</p>
                <h4 class="mb-0">{{ number_format($setupMetrics['occupied_beds']) }} / {{ number_format($setupMetrics['beds']) }}</h4>
                <small class="text-muted">{{ number_format($setupMetrics['wards']) }} wards</small>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Live Patient Flow</h5>
                    <span class="badge text-bg-success">Polling every 10s</span>
                </div>
                <div class="card-body">
                    @forelse($visitStatusRows as $row)
                        @php $width = max(5, ($row->count / $maxVisitCount) * 100); @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>{{ $row->label }}</span>
                                <strong>{{ number_format($row->count) }}</strong>
                            </div>
                            <div class="progress" style="height: 18px;">
                                <div class="progress-bar bg-success" style="width: {{ $width }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">No visit data yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        @if($canViewTechnicalRecords)
            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Bill Status</h5>
                    </div>
                    <div class="card-body">
                        @forelse($billStatusRows as $row)
                            @php $width = max(5, ($row->count / $maxBillCount) * 100); @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>{{ ucfirst($row->label) }}</span>
                                    <strong>{{ number_format($row->count) }}</strong>
                                </div>
                                <div class="progress" style="height: 18px;">
                                    <div class="progress-bar bg-primary" style="width: {{ $width }}%;"></div>
                                </div>
                                <div class="text-end text-muted small">{{ number_format($row->amount ?? 0, 2) }}</div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">No bill data yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Management Snapshot</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3"><span>Departments</span><strong>{{ number_format($setupMetrics['departments']) }}</strong></div>
                    <div class="d-flex justify-content-between mb-3"><span>Services</span><strong>{{ number_format($setupMetrics['services']) }}</strong></div>
                    <div class="d-flex justify-content-between mb-3"><span>Investigations</span><strong>{{ number_format($setupMetrics['investigations']) }}</strong></div>
                    @if($canViewTechnicalRecords)
                        <div class="d-flex justify-content-between mb-3"><span>Administrators</span><strong>{{ number_format($accessMetrics['administrators']) }}</strong></div>
                        <div class="d-flex justify-content-between"><span>Temporary Permissions</span><strong>{{ number_format($accessMetrics['temporary_permissions']) }}</strong></div>
                    @endif
                </div>
            </div>
        </div>

        @if($canViewTechnicalRecords)
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent System Activity</h5>
                        @if($syncMetrics['latest'])
                            <span class="text-muted small">Latest sync: {{ $syncMetrics['latest']->updated_at?->diffForHumans() }}</span>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush" style="max-height: 430px; overflow-y: auto;">
                            @forelse($recentActivities as $activity)
                                @php
                                    $actionLabel = ucwords(str_replace(['.', '_'], [' ', ' '], $activity->action));
                                    $modelLabel = $activity->model_type ? class_basename($activity->model_type) : null;
                                @endphp
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between gap-3">
                                        <div>
                                            <div class="fw-semibold">{{ $activity->actor?->name ?? 'System' }}</div>
                                            <div class="small text-muted">
                                                {{ $actionLabel }}
                                                @if($modelLabel)
                                                    on {{ $modelLabel }}
                                                    @if($activity->model_id)
                                                        #{{ $activity->model_id }}
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-end text-muted small">
                                            {{ $activity->created_at?->format('M j, h:i A') }}
                                            <div>{{ $activity->created_at?->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-5">No recent activity yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        function updateAdminDashboardLocalClock() {
            const dateElement = document.getElementById('admin-dashboard-local-date');
            const timeElement = document.getElementById('admin-dashboard-local-time');

            if (!dateElement || !timeElement) {
                return;
            }

            const now = new Date();

            dateElement.textContent = now.toLocaleDateString(undefined, {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });

            timeElement.textContent = now.toLocaleTimeString(undefined, {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
            });
        }

        updateAdminDashboardLocalClock();
        setInterval(updateAdminDashboardLocalClock, 1000);
    </script>
@endpush
