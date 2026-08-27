@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Create New Service</h1>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Service Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.services.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="code" class="form-label">Service Code <span class="text-danger">*</span></label>
                            <input type="text" id="code" name="code" 
                                class="form-control @error('code') is-invalid @enderror" 
                                placeholder="e.g., GH-001" value="{{ old('code') }}" required>
                            <small class="text-muted">Unique identifier for this service</small>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" 
                                class="form-control @error('name') is-invalid @enderror" 
                                placeholder="e.g., General Consultation" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" 
                                class="form-control @error('description') is-invalid @enderror" 
                                rows="3" placeholder="Service description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                    <input type="number" id="price" name="price" step="0.01" 
                                        class="form-control @error('price') is-invalid @enderror" 
                                        placeholder="0.00" value="{{ old('price') }}" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select id="category" name="category" 
                                        class="form-select @error('category') is-invalid @enderror" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}" @selected(old('category') == $cat)>
                                                {{ $cat }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="department_id" class="form-label">Department</label>
                            <select id="department_id" name="department_id" 
                                class="form-select @error('department_id') is-invalid @enderror">
                                <option value="">-- Select Department --</option>
                                @foreach(App\Models\Department::all() as $dept)
                                    <option value="{{ $dept->id }}" @selected(old('department_id') == $dept->id)>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                    value="1" @checked(old('is_active', true))>
                                <label class="form-check-label" for="is_active">
                                    Active (Available for billing)
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create Service
                            </button>
                            <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
