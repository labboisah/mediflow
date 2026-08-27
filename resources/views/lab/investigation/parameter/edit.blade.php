@extends('layouts.app')

@section('title', 'Edit Investigation Parameter')
@section('header')
    <div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 d-flex align-items-center mb-0">
        <i class="bi bi-clipboard2-plus me-2 text-primary"></i>
        Edit Investigation Parameter
    </h1>
    <a href="{{ route('lab.investigations.parameters.index',$investigation) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Back to Parameters
    </a>    
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('lab.investigations.parameters.update',[$investigation, $parameter]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Parameter Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ $parameter->name }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="unit" class="form-label">Parameter Unit</label>
                        <input type="text" name="unit" id="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ $parameter->unit }}">
                        @error('unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="reference_range" class="form-label">Reference Range</label>
                        <input type="text" name="reference_range" id="reference_range" class="form-control @error('reference_range') is-invalid @enderror" value="{{ $parameter->reference_range }}">
                        @error('reference_range')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Save Parameter</button>
                </form>
            </div>
        </div>  
    </div>
</div>
@endsection