@extends('layouts.app')

@section('title', 'Schedule Appointment - ' . $patient->demographic->full_name)

@section('header')
<div class="d-flex align-items-center gap-3">
    <i class="bi bi-calendar-check text-success" style="font-size: 2rem;"></i>
    <div>
        <h1 class="h3 mb-1">Schedule Appointment</h1>
        <p class="mb-0 text-muted">For: <strong class="text-success">{{ $patient->demographic->full_name ?? 'Unknown' }}</strong></p>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Appointment Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('record_officer.appointments.store', $patient->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="appointment_date" class="form-label">Appointment Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                               id="appointment_date" name="appointment_date" value="{{ old('appointment_date') }}" required>
                        @error('appointment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="appointment_time" class="form-label">Appointment Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('appointment_time') is-invalid @enderror" 
                               id="appointment_time" name="appointment_time" value="{{ old('appointment_time') }}" required>
                        @error('appointment_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="4" 
                                  placeholder="Any additional notes about the appointment">{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="bi bi-check-circle me-2"></i>Schedule Appointment
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
