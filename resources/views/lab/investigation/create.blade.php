@extends('layouts.app')

@section('title', 'Create Investigation')
@section('header')
    <div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 d-flex align-items-center mb-0">
        <i class="bi bi-clipboard2-plus me-2 text-primary"></i>
        Create Investigation
    </h1>
    <a href="{{ route('lab.investigations.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Back to List
    </a>    
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('lab.investigations.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Investigation Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Investigation Code</label>
                        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Create Investigation</button>
                </form>
            </div>
        </div>  
    </div>
</div>
@endsection