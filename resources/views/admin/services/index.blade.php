@extends('layouts.app')

@section('title', 'Services Management')
@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Services Management</h1>
                <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Service
                </a>
            </div>

            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><i class="bi bi-check-circle"></i></strong> {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('admin.services.index') }}" class="mb-0">
                                <input type="text" name="search" class="form-control form-control-sm" 
                                    placeholder="Search by code, name, or category..." 
                                    value="{{ request('search') }}">
                            </form>
                        </div>
                        <div class="col-md-2">
                            <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" @selected(request('category') == $cat)>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="active" @selected(request('status') == 'active')>Active</option>
                                <option value="inactive" @selected(request('status') == 'inactive')>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="trashed" id="trashed" 
                                    @checked(request()->boolean('trashed')) onchange="this.form.submit()">
                                <label class="form-check-label" for="trashed">
                                    Show Deleted
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.services.index') }}" class="btn btn-secondary btn-sm w-100">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $service)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">{{ $service->code }}</span>
                                    </td>
                                    <td>{{ $service->name }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $service->department->name ?? '' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $service->category }}</span>
                                    </td>
                                    <td class="fw-bold">{{ number_format($service->price, 2) }}</td>
                                    <td>
                                        @if($service->trashed())
                                            <span class="badge bg-danger">
                                                <i class="bi bi-trash"></i> Deleted
                                            </span>
                                        @elseif($service->is_active)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Active
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="bi bi-dash-circle"></i> Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.services.show', $service) }}" 
                                                class="btn btn-outline-info" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if(!$service->trashed())
                                                <a href="{{ route('admin.services.edit', $service) }}" 
                                                    class="btn btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" 
                                                    style="display:inline;" 
                                                    onsubmit="return confirm('Delete this service?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.services.restore', $service->id) }}" 
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-outline-success" title="Restore">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox"></i> No services found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
