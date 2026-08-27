@extends('layouts.app')

@section('title', 'departments')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 d-flex align-items-center mb-0">
        <i class="bi bi-buildings me-2 text-warning"></i>
        Manage Departments
    </h1>
    <div class="ms-auto d-flex">
        <a href="{{ route('admin.departments.create') }}" class="btn btn-sm btn-success ms-3">
            <i class="bi bi-plus-circle me-1"></i>New Department
        </a>
    </div>
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
<div class="row">
    <div class="col-md-10 offset-1">
        <div class="card shadow-sm p-4">
            <div class="table-responsive">
                <table class="table table-hover mb-0 datatable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th class="no-export">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $department)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{ $department->name }}</td>
                                
                                <td>
                                
                                    <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this department?');">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                        
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No departments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
