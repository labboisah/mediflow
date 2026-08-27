@extends('layouts.app')

@section('title', 'Record Admission - ' . $patient->demographic->full_name)

@section('header')
<div class="d-flex align-items-center gap-3">
    <i class="bi bi-door-open text-success" style="font-size: 2rem;"></i>
    <div>
        <h1 class="h3 mb-1">Record Patient Admission</h1>
        <p class="mb-0 text-muted">For: <strong class="text-success">{{ $patient->demographic->full_name ?? 'Unknown' }}</strong></p>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i>Admission Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('record_officer.admissions.store', $patient->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="admission_date" class="form-label">Admission Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('admission_date') is-invalid @enderror" 
                               id="admission_date" name="admission_date" value="{{ old('admission_date', date('Y-m-d')) }}" required>
                        @error('admission_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="ward" class="form-label">Ward Assignment <span class="text-danger">*</span></label>
                        <select class="form-select @error('ward') is-invalid @enderror" id="ward" name="ward" required>
                            <option value="">Select Ward</option>
                            <option value="General Ward" {{ old('ward') == 'General Ward' ? 'selected' : '' }}>General Ward</option>
                            <option value="ICU" {{ old('ward') == 'ICU' ? 'selected' : '' }}>ICU</option>
                            <option value="Pediatrics" {{ old('ward') == 'Pediatrics' ? 'selected' : '' }}>Pediatrics</option>
                            <option value="Maternity" {{ old('ward') == 'Maternity' ? 'selected' : '' }}>Maternity</option>
                            <option value="Surgery" {{ old('ward') == 'Surgery' ? 'selected' : '' }}>Surgery</option>
                        </select>
                        @error('ward')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="reason_for_admission" class="form-label">Reason for Admission <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('reason_for_admission') is-invalid @enderror" 
                                  id="reason_for_admission" name="reason_for_admission" rows="3" 
                                  placeholder="Medical reason for admission">{{ old('reason_for_admission') }}</textarea>
                        @error('reason_for_admission')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="bed_number" class="form-label">Bed Number</label>
                        <input type="text" class="form-control @error('bed_number') is-invalid @enderror" 
                               id="bed_number" name="bed_number" value="{{ old('bed_number') }}" placeholder="e.g., A-101">
                        @error('bed_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label">Additional Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3" 
                                  placeholder="Any additional information">{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="bi bi-check-circle me-2"></i>Record Admission
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
