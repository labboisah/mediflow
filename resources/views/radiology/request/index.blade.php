@extends('layouts.app')

@section('title', 'Investigations')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 d-flex align-items-center mb-0">
            <i class="bi bi-clipboard2-data me-2 text-primary"></i>
            Manage Investigations
        </h1>

        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Dashboard
        </a>
    </div>
@endsection

@section('content')
    <div class="container">
        @livewire('radiology.investigation-requests-table')
    </div>
@endsection