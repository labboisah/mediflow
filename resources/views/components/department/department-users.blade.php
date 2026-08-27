<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h4 mb-1">Users</h1>
            <p class="text-muted mb-0">Manage login details for users assigned to {{ auth()->user()->department?->name ?? 'your department' }}.</p>
        </div>
    </div>

    @if($editingUserId)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                    <div>
                        <h2 class="h6 mb-1">Update User Login</h2>
                        <p class="text-muted mb-0">Change the email or set a new password. Leave password blank to keep the current password.</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="cancelEdit">
                        Cancel
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model.live="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" wire:model.live="password" autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control @error('passwordConfirmation') is-invalid @enderror" wire:model.live="passwordConfirmation" autocomplete="new-password">
                            @error('passwordConfirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success" wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">Save Changes</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row g-2 align-items-end mb-3">
                <div class="col-md-8">
                    <label class="form-label">Search</label>
                    <input type="search" class="form-control" wire:model.live.debounce.400ms="search" placeholder="Name or email">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Rows</label>
                    <select class="form-select" wire:model.live="perPage">
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Department</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr wire:key="department-user-{{ $user->id }}">
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @forelse($user->roles as $role)
                                        <span class="badge bg-light text-dark">{{ $role->display_name ?: str($role->name)->headline() }}</span>
                                    @empty
                                        <span class="text-muted">No role</span>
                                    @endforelse
                                </td>
                                <td>{{ $user->department?->name ?? 'N/A' }}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-success" wire:click="edit({{ $user->id }})">
                                        Edit Login
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No users found in your department.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $users->links() }}
        </div>
    </div>
</div>
