@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Service Details</h1>
                <div>
                    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}" style="display:inline;"
                        onsubmit="return confirm('Delete this service? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">{{ $service->code }} - {{ $service->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Service Code</p>
                            <p class="fw-bold">
                                <span class="badge bg-secondary">{{ $service->code }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Status</p>
                            <p>
                                @if($service->is_active)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Active
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="bi bi-dash-circle"></i> Inactive
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Service Name</p>
                            <p>{{ $service->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Category</p>
                            <p>
                                <span class="badge bg-info">{{ $service->category }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Price</p>
                            <p class="h5 text-primary fw-bold">{{ number_format($service->price, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Created At</p>
                            <p>{{ $service->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    @if($service->description)
                        <div class="mb-4">
                            <p class="text-muted small mb-1">Description</p>
                            <p>{{ $service->description }}</p>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Last Updated</p>
                            <p>{{ $service->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Usage Information</h5>
                </div>
                <div class="card-body">
                    @php
                        $billCount = $service->bills()->count();
                    @endphp
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="alert alert-info mb-0">
                                <p class="text-muted small mb-1">Number of Bills</p>
                                <p class="h4 mb-0">{{ $billCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
