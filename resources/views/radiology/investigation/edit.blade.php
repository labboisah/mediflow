@extends('layouts.app')

@section('title', 'Edit Investigation')
@section('header')
    <div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 d-flex align-items-center mb-0">
        <i class="bi bi-pencil-square me-2 text-primary"></i>
        Edit Investigation
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
                <form action="{{ route('lab.investigations.update', $investigation->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Investigation Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $investigation->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Investigation Code</label>
                        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $investigation->code) }}">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Investigation</button>
                </form>
            </div>
        </div>  
    </div>
</div>
@endsection