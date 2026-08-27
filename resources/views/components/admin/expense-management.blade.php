<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Expense Management</h1>
            <p class="text-muted mb-0">Record and monitor hospital spending across departments.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ $pdfUrl }}" target="_blank" class="btn btn-outline-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i>
                Download PDF
            </a>

            @if(auth()->user()?->hasRole('administrator'))
                <a href="{{ route('admin.expense-categories.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-tags me-1"></i>
                    Manage Categories
                </a>
            @endif

            @if($canManageFinance)
                <button type="button" class="btn btn-primary" wire:click="resetForm">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add Expense
                </button>
            @endif
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="border rounded p-3 bg-white h-100">
                <p class="text-muted small mb-1">Total Expense</p>
                <h4 class="text-danger mb-0">{{ number_format($totalExpense, 2) }}</h4>
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
                <p class="text-muted small mb-1">Average Expense</p>
                <h4 class="text-primary mb-0">{{ number_format($averageExpense, 2) }}</h4>
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
                    <input type="search" class="form-control" wire:model.live.debounce.400ms="search" placeholder="Title, category, department, user">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Category</label>
                    <select class="form-select" wire:model.live="category">
                        <option value="">All Categories</option>
                        @foreach($categories as $expenseCategory)
                            <option value="{{ $expenseCategory->id }}">{{ $expenseCategory->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Department</label>
                    <select class="form-select" wire:model.live="department">
                        <option value="">All Departments</option>
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
                @if($editingExpenseId)
                    Edit Expense
                @else
                    Add Expense
                @endif
            </h5>

            @if($editingExpenseId)
                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="resetForm">
                    <i class="bi bi-x-lg"></i>
                </button>
            @endif
        </div>

        <div class="card-body">
            <form wire:submit="save">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Department</label>
                        <select class="form-select @error('departmentId') is-invalid @enderror" wire:model="departmentId">
                            <option value="">Select Department</option>
                            @foreach($departments as $departmentOption)
                                <option value="{{ $departmentOption->id }}">{{ $departmentOption->name }}</option>
                            @endforeach
                        </select>
                        @error('departmentId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Category</label>
                        <select class="form-select @error('expenseCategoryId') is-invalid @enderror" wire:model="expenseCategoryId">
                            <option value="">Select Category</option>
                            @foreach($categories as $expenseCategory)
                                <option value="{{ $expenseCategory->id }}">{{ $expenseCategory->name }}</option>
                            @endforeach
                        </select>
                        @error('expenseCategoryId') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        <label class="form-label">Expense Date</label>
                        <input type="date" class="form-control @error('expenseDate') is-invalid @enderror" wire:model="expenseDate">
                        @error('expenseDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-9">
                        <label class="form-label">Description</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" wire:model="description">
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">
                                <i class="bi bi-check-circle me-1"></i>
                                @if($editingExpenseId)
                                    Save Changes
                                @else
                                    Save Expense
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
            <h5 class="mb-0">All Expenses</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <button type="button" class="btn btn-link p-0 text-decoration-none text-dark fw-bold" wire:click="sortBy('department')">
                                    Department <i class="bi {{ $this->sortIcon('department') }}"></i>
                                </button>
                            </th>
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
                                <button type="button" class="btn btn-link p-0 text-decoration-none text-dark fw-bold" wire:click="sortBy('expense_date')">
                                    Date <i class="bi {{ $this->sortIcon('expense_date') }}"></i>
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
                        @forelse($expenses as $expense)
                            <tr wire:key="expense-{{ $expense->id }}">
                                <td>{{ $expense->department->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-info">{{ $expense->category->name ?? 'N/A' }}</span></td>
                                <td>
                                    <strong>{{ $expense->title }}</strong>
                                    @if($expense->description)
                                        <div class="small text-muted">{{ \Illuminate\Support\Str::limit($expense->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-danger">{{ number_format($expense->amount, 2) }}</td>
                                <td>{{ $expense->expense_date?->format('M d, Y') ?? 'N/A' }}</td>
                                <td>{{ $expense->createdBy->name ?? 'System' }}</td>
                                @if($canManageFinance)
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-warning" title="Edit" wire:click="edit({{ $expense->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <button type="button" class="btn btn-outline-danger" title="Delete" wire:click="delete({{ $expense->id }})" wire:confirm="Delete this expense?">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $canManageFinance ? 7 : 6 }}" class="text-center text-muted py-4">
                                    No expenses found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($expenses->hasPages())
            <div class="card-footer">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</div>
