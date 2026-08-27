@extends('layouts.app')

@section('title', 'Record Referral - ' . $patient->demographic->full_name)

@section('header')
<div class="d-flex align-items-center gap-3">
    <i class="bi bi-arrow-right-circle text-success" style="font-size: 2rem;"></i>
    <div>
        <h1 class="h3 mb-1">Record Patient Referral</h1>
        <p class="mb-0 text-muted">For: <strong class="text-success">{{ $patient->demographic->full_name ?? 'Unknown' }}</strong></p>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-share me-2"></i>Referral Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('record_officer.referrals.store', $patient->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="referral_date" class="form-label">Referral Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('referral_date') is-invalid @enderror" 
                               id="referral_date" name="referral_date" value="{{ old('referral_date', date('Y-m-d')) }}" required>
                        @error('referral_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="referred_to" class="form-label">Referred To <span class="text-danger">*</span></label>
                        <select class="form-select @error('referred_to') is-invalid @enderror" id="referred_to" name="referred_to" required>
                            <option value="">Select Specialty/Facility</option>
                            <option value="Cardiology" {{ old('referred_to') == 'Cardiology' ? 'selected' : '' }}>Cardiology</option>
                            <option value="Neurology" {{ old('referred_to') == 'Neurology' ? 'selected' : '' }}>Neurology</option>
                            <option value="Orthopedics" {{ old('referred_to') == 'Orthopedics' ? 'selected' : '' }}>Orthopedics</option>
                            <option value="Pediatrics" {{ old('referred_to') == 'Pediatrics' ? 'selected' : '' }}>Pediatrics</option>
                            <option value="General Surgery" {{ old('referred_to') == 'General Surgery' ? 'selected' : '' }}>General Surgery</option>
                            <option value="Other Hospital" {{ old('referred_to') == 'Other Hospital' ? 'selected' : '' }}>Other Hospital</option>
                        </select>
                        @error('referred_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="reason_for_referral" class="form-label">Reason for Referral <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('reason_for_referral') is-invalid @enderror" 
                                  id="reason_for_referral" name="reason_for_referral" rows="3" 
                                  placeholder="Why is the patient being referred?">{{ old('reason_for_referral') }}</textarea>
                        @error('reason_for_referral')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="clinical_summary" class="form-label">Clinical Summary <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('clinical_summary') is-invalid @enderror" 
                                  id="clinical_summary" name="clinical_summary" rows="3" 
                                  placeholder="Brief clinical summary for referral">{{ old('clinical_summary') }}</textarea>
                        @error('clinical_summary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="referred_by" class="form-label">Referred By <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('referred_by') is-invalid @enderror" 
                               id="referred_by" name="referred_by" value="{{ old('referred_by', auth()->user()->name) }}" required>
                        @error('referred_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="bi bi-check-circle me-2"></i>Record Referral
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
