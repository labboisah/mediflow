@extends('layouts.app')

@section('title', 'Grant Temporary Permission')

@section('header')
<h1 class="h3 d-flex align-items-center mb-0">
    <i class="bi bi-plus-circle me-2 text-info"></i>
    Grant Temporary Permission
</h1>
@endsection

@section('content')

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.temporary-permissions.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="user_id" class="form-label">Select User <span class="text-danger">*</span></label>
                        <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                            <option value="">-- Choose a user --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if(old('user_id') == $user->id) selected @endif>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="permission_id" class="form-label">Select Permission <span class="text-danger">*</span></label>
                        <select id="permission_id" name="permission_id" class="form-select @error('permission_id') is-invalid @enderror" required>
                            <option value="">-- Choose a permission --</option>
                            @foreach ($permissions as $permission)
                                <option value="{{ $permission->id }}" @if(old('permission_id') == $permission->id) selected @endif>
                                    {{ $permission->name }} 
                                    @if($permission->description)
                                        - {{ $permission->description }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('permission_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="duration_hours" class="form-label">Duration <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" id="duration_hours" name="duration_hours" class="form-control @error('duration_hours') is-invalid @enderror" min="1" max="168" value="{{ old('duration_hours', 24) }}" required>
                            <span class="input-group-text">hours</span>
                        </div>
                        <small class="form-text text-muted">1 hour to 7 days (168 hours)</small>
                        @error('duration_hours')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Granting Permission</label>
                        <textarea id="reason" name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" placeholder="e.g., Covering for shift, Emergency access" maxlength="500">{{ old('reason') }}</textarea>
                        <small class="form-text text-muted">Optional - helps with audit trail</small>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <small><strong>Important:</strong> This permission will automatically expire after the specified duration. The user will no longer have this permission after expiry.</small>
                    </div>

                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <small><strong>Example:</strong> Grant "manage-pharmacy-records" to a nurse for 8 hours to cover for the pharmacist during their break.</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-check-circle me-1"></i>
                            Grant Permission
                        </button>
                        <a href="{{ route('admin.temporary-permissions.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body">
                <h5 class="card-title">How it works</h5>
                <ol class="small">
                    <li>Select the user to grant permission to</li>
                    <li>Choose which permission to grant</li>
                    <li>Set duration (auto-expires)</li>
                    <li>Add a reason (optional)</li>
                    <li>Permission is active immediately</li>
                </ol>
                <hr>
                <p class="small text-muted"><strong>Use Cases:</strong></p>
                <ul class="small">
                    <li>Medical staff covering shifts</li>
                    <li>Emergency temporary access</li>
                    <li>Training/onboarding periods</li>
                    <li>Administrative tasks</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
