@extends('layouts.app')

@section('title', 'Edit User: ' . $user->name)

@section('header')
<h1 class="h3 d-flex align-items-center mb-0">
    <i class="bi bi-pencil me-2 text-info"></i>
    Manage Roles for: <span class="ms-2">{{ $user->name }}</span>
</h1>
@endsection

@section('content')

@if ($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-subtitle mb-3 text-muted">
                    <strong>{{ $user->email }}</strong>
                </h5>

                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Set Password (optional)</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Leave blank to keep existing">
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Assign Roles</label>
                        <div class="border rounded p-3">
                            @forelse ($roles as $role)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="role_{{ $role->id }}" name="roles[]" value="{{ $role->id }}" 
                                        @if(in_array($role->id, $userRoles)) checked @endif>
                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                        <strong>{{ $role->name }}</strong>
                                        <small class="text-muted">{{ $role->description }}</small>
                                    </label>
                                </div>
                            @empty
                                <p class="text-muted">No roles available.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select id="department_id" name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                            <option value="{{$user->department->id ?? ''}}">{{$user->department->name ?? 'Select Department'}}</option>
                            @foreach(App\Models\Department::all() as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="alert alert-info alert-sm" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Select one or more roles to assign permissions to this user.</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-check-circle me-1"></i>
                            Update User Roles
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body">
                <h5 class="card-title">Current Roles</h5>
                @if (count($userRoles) > 0)
                    <ul class="list-unstyled">
                        @foreach ($user->roles as $role)
                            <li class="mb-2">
                                <span class="badge bg-info">{{ $role->name }}</span>
                                <small class="text-muted">{{ $role->description }}</small>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No roles assigned.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
