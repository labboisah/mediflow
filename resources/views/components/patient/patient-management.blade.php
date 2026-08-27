<div>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1 d-flex align-items-center">
                <i class="bi bi-people-fill text-success me-2"></i>
                Patient Management
            </h1>
            <p class="text-muted mb-0">Search, filter, and open patient records quickly.</p>
        </div>

        @if($canManageRecords)
            <a href="{{ route('record.patients.register.form') }}" class="btn btn-success">
                <i class="bi bi-person-plus me-2"></i>
                Register Patient
            </a>
        @endif
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Patients</div>
                    <div class="h4 mb-0">{{ number_format($totalPatients) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Filtered Result</div>
                    <div class="h4 mb-0">{{ number_format($filteredCount) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Registered Today</div>
                    <div class="h4 mb-0">{{ number_format($registeredToday) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Walk-in Patients</div>
                    <div class="h4 mb-0">{{ number_format($walkInCount) }}</div>
                </div>
            </div>
        </div>
    </div>

    @if($feedbackMessage)
        <div class="alert alert-{{ $feedbackMessage['type'] }} d-flex align-items-center" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <div>{{ $feedbackMessage['message'] }}</div>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-xl-4">
                    <label class="form-label">Search Patient</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="search" class="form-control" placeholder="Hospital no, name, phone, email" wire:model.live.debounce.400ms="search">
                    </div>
                </div>

                <div class="col-6 col-xl-2">
                    <label class="form-label">Gender</label>
                    <select class="form-select" wire:model.live="gender">
                        <option value="">All</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="col-6 col-xl-2">
                    <label class="form-label">Type</label>
                    <select class="form-select" wire:model.live="patientType">
                        <option value="">All</option>
                        <option value="registered">Registered</option>
                        <option value="walk_in">Walk-in</option>
                    </select>
                </div>

                <div class="col-6 col-xl-2">
                    <label class="form-label">From</label>
                    <input type="date" class="form-control" wire:model.live="dateFrom">
                </div>

                <div class="col-6 col-xl-2">
                    <label class="form-label">To</label>
                    <input type="date" class="form-control" wire:model.live="dateTo">
                </div>

                <div class="col-6 col-xl-2">
                    <label class="form-label">Rows</label>
                    <select class="form-select" wire:model.live="perPage">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>

                <div class="col-6 col-xl-2">
                    <button type="button" class="btn btn-outline-secondary w-100" wire:click="clearFilters" @disabled(! $hasActiveFilters)>
                        <i class="bi bi-x-circle me-1"></i>
                        Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h5 class="mb-0">
                <i class="bi bi-list-ul text-success me-2"></i>
                Patients
            </h5>
            <div class="text-muted small" wire:loading>
                <span class="spinner-border spinner-border-sm me-1"></span>
                Updating list
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>
                            <button type="button" class="btn btn-link p-0 text-decoration-none fw-semibold" wire:click="sortBy('hospital_number')">
                                Hospital No
                                @if($sortField === 'hospital_number')
                                    <i class="bi bi-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </button>
                        </th>
                        <th>Patient</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Phone</th>
                        <th>
                            <button type="button" class="btn btn-link p-0 text-decoration-none fw-semibold" wire:click="sortBy('registration_date')">
                                Registered
                                @if($sortField === 'registration_date')
                                    <i class="bi bi-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </button>
                        </th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                        <tr wire:key="patient-{{ $patient->id }}">
                            <td>
                                <span class="badge bg-primary">{{ $patient->hospital_number }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $patient->demographic->full_name ?? 'N/A' }}</div>
                                <div class="text-muted small">{{ $patient->fileType->name ?? 'General file' }}</div>
                            </td>
                            <td>{{ $patient->demographic->gender ?? 'N/A' }}</td>
                            <td>{{ $patient->demographic->age ?? 'N/A' }}</td>
                            <td>{{ $patient->demographic->phone_number ?? 'N/A' }}</td>
                            <td>{{ optional($patient->registration_date)->format('M d, Y') ?? 'N/A' }}</td>
                            <td>
                                @if($patient->is_walkIn)
                                    <span class="badge bg-warning text-dark">Walk-in</span>
                                @else
                                    <span class="badge bg-success">Registered</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    @if($canManageRecords)
                                        <a href="{{ route('record.patients.show', $patient) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('record.patients.edit.form', $patient) }}" class="btn btn-outline-success">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('patient.show', $patient) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>
                                            View
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2 mb-3">No patients match the current filters.</p>
                                @if($hasActiveFilters)
                                    <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="clearFilters">
                                        Clear filters
                                    </button>
                                @elseif($canManageRecords)
                                    <a href="{{ route('record.patients.register.form') }}" class="btn btn-success btn-sm">
                                        Register First Patient
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($patients->hasPages())
            <div class="card-footer bg-light">
                {{ $patients->links() }}
            </div>
        @endif
    </div>
</div>
