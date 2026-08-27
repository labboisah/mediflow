<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Revenue Management</h1>
            <p class="text-muted mb-0">Track hospital income from categories, departments, grants, donations, and other sources.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ $pdfUrl }}" target="_blank" class="btn btn-outline-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i>
                Download PDF
            </a>

            @if(auth()->user()?->hasRole('administrator'))
                <a href="{{ route('admin.revenue-categories.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-tags me-1"></i>
                    Manage Categories
                </a>
            @endif

            @if($canManageFinance)
                <button type="button" class="btn btn-success" wire:click="resetForm">
                    <i class="bi bi-plus-circle me-1"></i>
                    Record Revenue
                </button>
            @endif
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="border rounded p-3 bg-white h-100">
                <p class="text-muted small mb-1">Total Revenue</p>
                <h4 class="text-success mb-0">{{ number_format($totalRevenue, 2) }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="border rounded p-3 bg-white h-100">
                <p class="text-muted small mb-1">Records</p>
                <h4 class="mb-0">{{ number_format($recordCount) }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="border rounded p-3 bg-white h-100">
                <p class="text-muted small mb-1">Average Revenue</p>
                <h4 class="text-primary mb-0">{{ number_format($averageRevenue, 2) }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="border rounded p-3 bg-white h-100">
                <p class="text-muted small mb-1">Categories</p>
                <h4 class="text-warning mb-0">{{ number_format($categoryCount) }}</h4>
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
                    <input type="search" class="form-control" wire:model.live.debounce.400ms="search" placeholder="Title, reference, category, user">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Category</label>
                    <select class="form-select" wire:model.live="category">
                        <option value="">All Categories</option>
                        @foreach($categories as $revenueCategory)
                            <option value="{{ $revenueCategory->id }}">{{ $revenueCategory->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Department</label>
                    <select class="form-select" wire:model.live="department">
                        <option value="">All Departments</option>
                        <option value="general">General</option>
                        @foreach($departments as $departmentOption)
                            <option value="{{ $departmentOption->id }}">{{ $departmentOption->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Recorded By</label>
                    <select class="form-select" wire:model.live="createdBy">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1">
                    <label class="form-label">From</label>
                    <input type="date" class="form-control" wire:model.live="dateFrom">
                </div>

                <div class="col-md-1">
                    <label class="form-label">To</label>
                    <input type="date" class="form-control" wire:model.live="dateTo">
                </div>

                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if($canManageFinance)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                @if($editingRevenueId)
                    Edit Revenue
                @else
                    Record Revenue
                @endif
            </h5>

            @if($editingRevenueId)
                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="resetForm">
                    <i class="bi bi-x-lg"></i>
                </button>
            @endif
        </div>

        <div class="card-body">
            <form wire:submit="save">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Category</label>
                        <select class="form-select @error('revenueCategoryId') is-invalid @enderror" wire:model="revenueCategoryId">
                            <option value="">Select Category</option>
                            @foreach($categories as $revenueCategory)
                                <option value="{{ $revenueCategory->id }}">{{ $revenueCategory->name }}</option>
                            @endforeach
                        </select>
                        @error('revenueCategoryId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Department</label>
                        <select class="form-select @error('departmentId') is-invalid @enderror" wire:model="departmentId">
                            <option value="">General / All Departments</option>
                            @foreach($departments as $departmentOption)
                                <option value="{{ $departmentOption->id }}">{{ $departmentOption->name }}</option>
                            @endforeach
                        </select>
                        @error('departmentId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model="title">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Amount</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('amount') is-invalid @enderror" wire:model="amount">
                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Revenue Date</label>
                        <input type="date" class="form-control @error('revenueDate') is-invalid @enderror" wire:model="revenueDate">
                        @error('revenueDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Reference Number</label>
                        <input type="text" class="form-control @error('referenceNumber') is-invalid @enderror" wire:model="referenceNumber">
                        @error('referenceNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Description</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" wire:model="description">
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">
                                <i class="bi bi-check-circle me-1"></i>
                                @if($editingRevenueId)
                                    Save Changes
                                @else
                                    Record Revenue
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
            <h5 class="mb-0">All Revenue</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <button type="button" class="btn btn-link p-0 text-decoration-none text-dark fw-bold" wire:click="sortBy('category')">
                                    Category <i class="bi {{ $this->sortIcon('category') }}"></i>
                                </button>
                            </th>
                            <th>
                                <button type="button" class="btn btn-link p-0 text-decoration-none text-dark fw-bold" wire:click="sortBy('title')">
                                    Title <i class="bi {{ $this->sortIcon('title') }}"></i>
                                </button>
                            </th>
                            <th class="text-end">
                                <button type="button" class="btn btn-link p-0 text-decoration-none text-dark fw-bold" wire:click="sortBy('amount')">
                                    Amount <i class="bi {{ $this->sortIcon('amount') }}"></i>
                                </button>
                            </th>
                            <th>
                                <button type="button" class="btn btn-link p-0 text-decoration-none text-dark fw-bold" wire:click="sortBy('revenue_date')">
                                    Date <i class="bi {{ $this->sortIcon('revenue_date') }}"></i>
                                </button>
                            </th>
                            <th>
                                <button type="button" class="btn btn-link p-0 text-decoration-none text-dark fw-bold" wire:click="sortBy('reference_number')">
                                    Reference <i class="bi {{ $this->sortIcon('reference_number') }}"></i>
                                </button>
                            </th>
                            <th>
                                <button type="button" class="btn btn-link p-0 text-decoration-none text-dark fw-bold" wire:click="sortBy('department')">
                                    Department <i class="bi {{ $this->sortIcon('department') }}"></i>
                                </button>
                            </th>
                            <th>
                                <button type="button" class="btn btn-link p-0 text-decoration-none text-dark fw-bold" wire:click="sortBy('created_by')">
                                    Recorded By <i class="bi {{ $this->sortIcon('created_by') }}"></i>
                                </button>
                            </th>
                            @if($canManageFinance)
                                <th class="text-end">Actions</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($revenues as $revenue)
                            <tr wire:key="revenue-{{ $revenue->id }}">
                                <td><span class="badge bg-info">{{ $revenue->category->name ?? 'N/A' }}</span></td>
                                <td>
                                    <strong>{{ $revenue->title }}</strong>
                                    @if($revenue->description)
                                        <div class="small text-muted">{{ \Illuminate\Support\Str::limit($revenue->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-success">{{ number_format($revenue->amount, 2) }}</td>
                                <td>{{ $revenue->revenue_date?->format('M d, Y') ?? 'N/A' }}</td>
                                <td>{{ $revenue->reference_number ?? '-' }}</td>
                                <td>{{ $revenue->department->name ?? 'General' }}</td>
                                <td>{{ $revenue->createdBy->name ?? 'System' }}</td>
                                @if($canManageFinance)
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-warning" title="Edit" wire:click="edit({{ $revenue->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <button type="button" class="btn btn-outline-danger" title="Delete" wire:click="delete({{ $revenue->id }})" wire:confirm="Delete this revenue record?">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $canManageFinance ? 8 : 7 }}" class="text-center text-muted py-4">
                                    No revenue records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($revenues->hasPages())
            <div class="card-footer">
                {{ $revenues->links() }}
            </div>
        @endif
    </div>
</div>
