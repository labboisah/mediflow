@extends('layouts.app')

@section('title', 'Users')

@section('header')
<h1 class="h3 d-flex align-items-center mb-0">
    <i class="bi bi-people-fill me-2 text-warning"></i>
    Manage Users
</h1>
<div class="ms-auto d-flex">
    <form method="GET" class="d-flex align-items-center me-2">
        <input type="search" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search name or email">
    </form>
    <form method="GET" class="d-flex align-items-center me-2">
        <select name="role" class="form-select form-select-sm">
            <option value="">All roles</option>
            @foreach($roles as $r)
                <option value="{{ $r->id }}" @if(request('role') == $r->id) selected @endif>{{ $r->name }}</option>
            @endforeach
        </select>
    </form>
    <form method="GET" class="d-flex align-items-center me-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="trashed" value="1" id="trashedCheckbox" @if(request()->boolean('trashed')) checked @endif onchange="this.form.submit()">
            <label class="form-check-label small" for="trashedCheckbox">Show deleted</label>
        </div>
    </form>
    <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-success ms-3">
        <i class="bi bi-plus-circle me-1"></i>New User
    </a>
</div>
@endsection

@section('content')

@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 datatable">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Department</th>
                    <th class="no-export">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-circle me-2 text-secondary"></i>
                                {{ $user->name }}
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <small>
                                @forelse ($user->roles as $role)
                                    <span class="badge bg-info">{{ $role->name }}</span>
                                @empty
                                    <span class="text-muted">No roles</span>
                                @endforelse
                            </small>
                        </td>
                        <td>{{$user->department->name ?? 'Not assigned'}}</td>
                        <td>
                            @if($user->trashed())
                                <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Restore">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Permanently delete this user?');">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @else
                                @if ($user->id !== auth()->id())
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this user?');">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Your account</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $users->links() }}
</div>
@endsection
