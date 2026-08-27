@extends('layouts.app')

@section('title', 'User: ' . $user->name)

@section('header')
<h1 class="h3 d-flex align-items-center mb-0">
    <i class="bi bi-person-circle me-2 text-info"></i>
    User Details: <span class="ms-2">{{ $user->name }}</span>
</h1>
@endsection

@section('content')

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-4">
                    <label class="form-label text-muted">Full Name</label>
                    <p class="h5">{{ $user->name }}</p>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted">Email</label>
                    <p class="h5">{{ $user->email }}</p>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted">Account Status</label>
                    <p>
                        @if($user->trashed())
                            <span class="badge bg-danger">Deleted</span>
                        @else
                            <span class="badge bg-success">Active</span>
                        @endif
                    </p>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted">Registered</label>
                    <p class="h5">{{ $user->created_at->format('M d, Y \a\t H:i') }}</p>
                </div>

                @if($user->updated_at->ne($user->created_at))
                    <div class="mb-4">
                        <label class="form-label text-muted">Last Updated</label>
                        <p class="h5">{{ $user->updated_at->format('M d, Y \a\t H:i') }}</p>
                    </div>
                @endif

                <div class="mb-4">
                    <label class="form-label text-muted">Assigned Roles</label>
                    <div>
                        @forelse($userRoles as $roleId)
                            @php
                                $role = $roles->find($roleId);
                            @endphp
                            @if($role)
                                <span class="badge bg-info me-2 mb-2">{{ $role->display_name ?? $role->name }}</span>
                            @endif
                        @empty
                            <span class="text-muted">No roles assigned</span>
                        @endforelse
                    </div>
                </div>

                <hr>

                <div class="d-flex gap-2">
                    @if ($user->id !== auth()->id())
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-info">
                            <i class="bi bi-pencil me-1"></i>Edit User
                        </a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this user?');">
                                <i class="bi bi-trash me-1"></i>Delete User
                            </button>
                        </form>
                    @else
                        <span class="text-muted">This is your account</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
