@extends('layouts.app')

@section('title', 'Create User')

@section('header')
<h1 class="h3 d-flex align-items-center mb-0">
    <i class="bi bi-person-plus-fill me-2 text-success"></i>
    Create New User
</h1>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Leave blank to auto-generate">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Assign Roles</label>
                        <div class="border rounded p-3">
                            @forelse($roles as $role)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="role_{{ $role->id }}" name="roles[]" value="{{ $role->id }}">
                                    <label class="form-check-label" for="role_{{ $role->id }}">{{ $role->name }}</label>
                                </div>
                            @empty
                                <p class="text-muted">No roles available.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- department -->
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Assign Department</label>
                        <select id="department_id" name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                            <option value="">Select Department (optional)</option>
                            @foreach(App\Models\Department::all() as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-success" type="submit"><i class="bi bi-check-circle me-1"></i>Create User</button>
                        <a class="btn btn-secondary" href="{{ route('admin.users.index') }}">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
