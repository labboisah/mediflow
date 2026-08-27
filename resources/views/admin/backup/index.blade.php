@extends('layouts.app')

@section('title', 'Data Backup')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1 d-flex align-items-center">
                <i class="bi bi-database-down text-success me-2"></i>
                Data Backup
            </h1>
            <p class="text-muted mb-0">Backup tools and database protection options.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-1"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Database Size</div>
                    <div class="display-6 fw-semibold mb-0">{{ $databaseSize['formatted'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="rounded bg-success-subtle text-success d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-1">Create Database Backup</h5>
                    <p class="text-muted mb-0">The system saves to a connected USB drive first. If no USB drive is available, it saves to the current user Downloads folder.</p>
                </div>
                <form method="POST" action="{{ route('admin.backup.store') }}">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-database-down me-1"></i>
                        Backup Database
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
