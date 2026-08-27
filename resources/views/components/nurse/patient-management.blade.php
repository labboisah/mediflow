<div class="container-fluid py-3" wire:poll.visible.15s="refreshList">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h4 mb-1">Nursing Patient Management</h1>
            <p class="text-muted mb-0">Patients are ordered by their latest visit first and refresh automatically.</p>
        </div>

        <button type="button" class="btn btn-outline-secondary" wire:click="resetFilters">
            <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
        </button>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <label class="form-label">Search All Patients</label>
            <input type="search"
                   class="form-control"
                   wire:model.live.debounce.400ms="search"
                   placeholder="Hospital number, patient name, phone, email, or next of kin">
            <small class="text-muted">This search checks all registered patients, including patients not currently assigned to nursing requests.</small>
        </div>
    </div>

    @if(trim($search) !== '')
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Patient Search Results</h5>
                <span class="badge text-bg-success">{{ $allPatients->count() }} found</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Contact</th>
                                <th>Next of Kin</th>
                                <th>Last Visit</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allPatients as $patient)
                                @php
                                    $demographic = $patient->demographic;
                                    $nextOfKin = $patient->nextOfKin;
                                    $lastVisit = $patient->patientVisits->first();
                                @endphp
                                <tr wire:key="all-patient-search-{{ $patient->id }}">
                                    <td>
                                        <div class="fw-semibold">{{ $demographic?->full_name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $patient->hospital_number ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $demographic?->phone_number ?? 'N/A' }}</div>
                                        <small class="text-muted">
                                            {{ $demographic?->gender ?? 'N/A' }}
                                            @if($demographic?->date_of_birth)
                                                , {{ $demographic->date_of_birth->age }} yrs
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <div>{{ $nextOfKin?->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $nextOfKin?->telephone ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $lastVisit?->visit_type ?? 'No visit' }}</div>
                                        <small class="text-muted">{{ $lastVisit?->created_at?->format('M d, Y') ?? '' }}</small>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('nurse.patient.show', $patient) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View Profile
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No registered patients match this search.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if(! auth()->user()->department_id)
        <div class="alert alert-warning">
            Your account is not attached to a department, so nursing patient requests cannot be loaded.
        </div>
    @else
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Filtered Requests</p>
                        <h3 class="h4 mb-0">{{ number_format($summary['total']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Pending</p>
                        <h3 class="h4 mb-0 text-warning">{{ number_format($summary['pending']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Completed</p>
                        <h3 class="h4 mb-0 text-success">{{ number_format($summary['completed']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Active Visits</p>
                        <h3 class="h4 mb-0 text-primary">{{ number_format($summary['activeVisits']) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-2 align-items-end mb-3">
                    <div class="col-lg-3 col-md-3">
                        <label class="form-label">Request Status</label>
                        <select class="form-select" wire:model.live="requestStatus">
                            <option value="">All Requests</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <label class="form-label">Visit Status</label>
                        <select class="form-select" wire:model.live="visitStatus">
                            <option value="">All Visits</option>
                            <option value="Active">Active</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Service</label>
                        <select class="form-select" wire:model.live="serviceId">
                            <option value="">All Services</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3">
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
                                <th>Patient</th>
                                <th>Service</th>
                                <th>Visit</th>
                                <th>Contact</th>
                                <th>Next of Kin</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $request)
                                @php
                                    $visit = $request->patientVisit;
                                    $patient = $visit?->patient;
                                    $demographic = $patient?->demographic;
                                    $nextOfKin = $patient?->nextOfKin;
                                    $requestStatus = strtolower((string) $request->status);
                                    $visitStatus = (string) ($visit?->status ?? 'N/A');
                                @endphp
                                <tr wire:key="nurse-request-{{ $request->id }}">
                                    <td>
                                        <div class="fw-semibold">{{ $demographic?->full_name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $patient?->hospital_number ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $request->service?->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $request->requested_at ? $request->requested_at->format('M d, Y h:i A') : $request->created_at?->format('M d, Y h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $visit?->visit_type ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $visit?->visit_date?->format('M d, Y') ?? $visit?->created_at?->format('M d, Y') ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $demographic?->phone_number ?? 'N/A' }}</div>
                                        <small class="text-muted">
                                            {{ $demographic?->gender ?? 'N/A' }}
                                            @if($demographic?->date_of_birth)
                                                , {{ $demographic->date_of_birth->age }} yrs
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <div>{{ $nextOfKin?->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $nextOfKin?->telephone ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            <span class="badge bg-{{ $requestStatus === 'completed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($request->status ?? 'pending') }}
                                            </span>
                                        </div>
                                        <span class="badge bg-{{ $visitStatus === 'Active' ? 'primary' : 'secondary' }}">
                                            {{ $visitStatus }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if($patient)
                                                <a href="{{ route('nurse.patient.show', $patient) }}" class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                            @endif
                                            @if($requestStatus !== 'completed')
                                                <button type="button"
                                                        class="btn btn-outline-success"
                                                        wire:click="completeRequest({{ $request->id }})"
                                                        wire:confirm="Mark this service request as completed?">
                                                    <i class="bi bi-check-circle"></i> Complete
                                                </button>
                                            @endif
                                            @if($visit && $visitStatus === 'Active')
                                                <button type="button"
                                                        class="btn btn-outline-warning"
                                                        wire:click="closeVisit({{ $visit->id }})"
                                                        wire:confirm="Close this patient visit?">
                                                    <i class="bi bi-x-circle"></i> Close
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No nursing patient requests match your current filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                    <small class="text-muted">
                        Auto-refreshes every 15 seconds while this page is visible.
                    </small>
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
