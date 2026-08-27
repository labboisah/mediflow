@extends('layouts.app')

@section('title', 'Record Discharge - ' . $patient->demographic->full_name)

@section('header')
<div class="d-flex align-items-center gap-3">
    <i class="bi bi-door-closed text-success" style="font-size: 2rem;"></i>
    <div>
        <h1 class="h3 mb-1">Record Patient Discharge</h1>
        <p class="mb-0 text-muted">For: <strong class="text-success">{{ $patient->demographic->full_name ?? 'Unknown' }}</strong></p>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-check-all me-2"></i>Discharge Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('record_officer.discharges.store', $patient->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="discharge_date" class="form-label">Discharge Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('discharge_date') is-invalid @enderror" 
                               id="discharge_date" name="discharge_date" value="{{ old('discharge_date', date('Y-m-d')) }}" required>
                        @error('discharge_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="discharge_status" class="form-label">Discharge Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('discharge_status') is-invalid @enderror" id="discharge_status" name="discharge_status" required>
                            <option value="">Select Status</option>
                            <option value="Recovered" {{ old('discharge_status') == 'Recovered' ? 'selected' : '' }}>Recovered</option>
                            <option value="Improved" {{ old('discharge_status') == 'Improved' ? 'selected' : '' }}>Improved</option>
                            <option value="Referred" {{ old('discharge_status') == 'Referred' ? 'selected' : '' }}>Referred</option>
                            <option value="Against Medical Advice" {{ old('discharge_status') == 'Against Medical Advice' ? 'selected' : '' }}>Against Medical Advice</option>
                        </select>
                        @error('discharge_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="discharge_notes" class="form-label">Discharge Notes <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('discharge_notes') is-invalid @enderror" 
                                  id="discharge_notes" name="discharge_notes" rows="4" 
                                  placeholder="Summary of treatment and recommendations">{{ old('discharge_notes') }}</textarea>
                        @error('discharge_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="follow_up_date" class="form-label">Follow-up Date</label>
                        <input type="date" class="form-control @error('follow_up_date') is-invalid @enderror" 
                               id="follow_up_date" name="follow_up_date" value="{{ old('follow_up_date') }}">
                        @error('follow_up_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="bi bi-check-circle me-2"></i>Record Discharge
                        </button>
                        <a href="{{ route('record_officer.patients.show', $patient->id) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
