@extends('layouts.app')

@section('title', 'Record Patient Visit - ' . $patient->demographic->full_name)

@section('header')
<div class="d-flex align-items-center gap-3">
    <i class="bi bi-hospital text-success" style="font-size: 2rem;"></i>
    <div>
        <h1 class="h3 mb-1">Record Patient Visit</h1>
        <p class="mb-0 text-muted">For: <strong class="text-success">{{ $patient->demographic->full_name ?? 'Unknown' }}</strong></p>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-file-medical me-2"></i>Visit Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('record.visits.store', $patient->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="visit_date" class="form-label">Visit Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('visit_date') is-invalid @enderror" 
                               id="visit_date" name="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" required>
                        @error('visit_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="visit_type" class="form-label">Visit Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('visit_type') is-invalid @enderror" id="visit_type" name="visit_type" required>
                            <option value="">Select Visit Type</option>
                            @foreach(App\Models\Service::all() as $service)
                            <option value="{{$service->id}}" {{ old('visit_type') == $service->id ? 'selected' : '' }}>{{$service->name}}</option>
                            @endforeach
                        </select>
                        @error('visit_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="bi bi-check-circle me-2"></i>Record Visit
                        </button>
                        <a href="{{ route('record.patients.show', $patient->id) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
