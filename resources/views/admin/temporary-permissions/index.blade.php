@extends('layouts.app')

@section('title', 'Temporary Permissions')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 d-flex align-items-center mb-0">
        <i class="bi bi-clock-history me-2 text-info"></i>
        Temporary Permissions
    </h1>
    <a href="{{ route('admin.temporary-permissions.create') }}" class="btn btn-info">
        <i class="bi bi-plus-circle me-1"></i>
        Grant Permission
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
                    <th>User</th>
                    <th>Permission</th>
                    <th>Granted By</th>
                    <th>Reason</th>
                    <th>Expires</th>
                    <th>Status</th>
                    <th class="no-export">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tempPermissions as $tempPerm)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-circle me-2 text-secondary"></i>
                                <strong>{{ $tempPerm->user->name }}</strong><br>
                                <small class="text-muted">{{ $tempPerm->user->email }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $tempPerm->permission->name }}</span>
                        </td>
                        <td>
                            <small>{{ $tempPerm->grantedBy?->name ?? '-' }}</small>
                        </td>
                        <td>
                            <small class="text-muted">{{ $tempPerm->reason ?? '-' }}</small>
                        </td>
                        <td>
                            <small>
                                {{ $tempPerm->expires_at->format('M d, Y H:i') }}
                                <br>
                                <span class="badge bg-light text-dark">
                                    @if($tempPerm->isValid())
                                        {{ $tempPerm->expires_at->diffForHumans() }}
                                    @else
                                        Expired
                                    @endif
                                </span>
                            </small>
                        </td>
                        <td>
                            @if($tempPerm->is_active && $tempPerm->isValid())
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($tempPerm->is_active && $tempPerm->isValid())
                                <form action="{{ route('admin.temporary-permissions.revoke', $tempPerm->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Revoke now">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.temporary-permissions.destroy', $tempPerm->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this record?');">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No temporary permissions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $tempPermissions->links() }}
</div>

<div class="alert alert-info mt-4" role="alert">
    <i class="bi bi-info-circle me-2"></i>
    <small><strong>Note:</strong> Temporary permissions automatically expire after the specified duration. You can manually revoke them before expiry if needed.</small>
</div>

@endsection
