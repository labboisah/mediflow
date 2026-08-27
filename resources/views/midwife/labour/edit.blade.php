@extends('layouts.app')

@section('title', 'Edit Labour Record - ' . $patient->full_name)

@section('content')
<div class="container-fluid">

    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="bi bi-pencil-square"></i> Edit Labour Record
            </h1>

            <small class="text-muted">
                Update labour information for {{ $patient->name() }}
            </small>
        </div>

        <div class="col-md-4 text-end">
            <a href="{{ route('midwife.labour.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <form action="{{ route('midwife.labour.update', $labour) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="row">

            <!-- MAIN CONTENT -->
            <div class="col-lg-9">

                <!-- Patient Information -->
                <div class="card mb-3 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-person-badge"></i>
                            Patient Information
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-3">
                                <small class="text-muted">Hospital No.</small>
                                <p class="fw-bold">
                                    {{ $patient->hospital_number }}
                                </p>
                            </div>

                            <div class="col-md-3">
                                <small class="text-muted">Patient Name</small>
                                <p class="fw-bold">
                                    {{ $patient->name() }}
                                </p>
                            </div>

                            <div class="col-md-3">
                                <small class="text-muted">Age</small>
                                <p class="fw-bold">
                                    {{ $patient->age() }} years
                                </p>
                            </div>

                            <div class="col-md-3">
                                <small class="text-muted">Gender</small>
                                <p class="fw-bold">
                                    {{ $patient->demographic->gender }}
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Labour Information -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-calendar-event"></i>
                            Labour Information
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Labour Onset Time
                                </label>

                                <input type="datetime-local"
                                       name="labour_onset_time"
                                       class="form-control @error('labour_onset_time') is-invalid @enderror"
                                       value="{{ old('labour_onset_time', optional($labour->labour_onset_time)->format('Y-m-d\TH:i')) }}">

                                @error('labour_onset_time')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Mode of Onset
                                </label>

                                <select name="mode_of_onset"
                                        class="form-select @error('mode_of_onset') is-invalid @enderror">

                                    <option value="">Select</option>

                                    <option value="spontaneous"
                                        {{ old('mode_of_onset', $labour->mode_of_onset) == 'spontaneous' ? 'selected' : '' }}>
                                        Spontaneous
                                    </option>

                                    <option value="induced"
                                        {{ old('mode_of_onset', $labour->mode_of_onset) == 'induced' ? 'selected' : '' }}>
                                        Induced
                                    </option>
                                </select>

                                @error('mode_of_onset')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Gestational Weeks
                                </label>

                                <input type="number"
                                       name="gestational_weeks"
                                       class="form-control @error('gestational_weeks') is-invalid @enderror"
                                       value="{{ old('gestational_weeks', $labour->gestational_weeks) }}"
                                       placeholder="e.g. 38">

                                @error('gestational_weeks')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Labour Type
                                </label>

                                <input type="text"
                                       name="labour_type"
                                       class="form-control @error('labour_type') is-invalid @enderror"
                                       value="{{ old('labour_type', $labour->labour_type) }}"
                                       placeholder="Primigravida / Multigravida">

                                @error('labour_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    Reason for Induction
                                </label>

                                <textarea name="reason_for_induction"
                                          rows="2"
                                          class="form-control @error('reason_for_induction') is-invalid @enderror">{{ old('reason_for_induction', $labour->reason_for_induction) }}</textarea>

                                @error('reason_for_induction')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    Previous Obstetric History
                                </label>

                                <textarea name="previous_obstetric_history"
                                          rows="3"
                                          class="form-control @error('previous_obstetric_history') is-invalid @enderror">{{ old('previous_obstetric_history', $labour->previous_obstetric_history) }}</textarea>

                                @error('previous_obstetric_history')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Pre-Labour Assessment -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-clipboard-pulse"></i>
                            Pre-Labour Assessment
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Cervical State
                                </label>

                                <input type="text"
                                       name="cervical_state"
                                       class="form-control"
                                       value="{{ old('cervical_state', $labour->cervical_state) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Show
                                </label>

                                <select name="show"
                                        class="form-select">

                                    <option value="">Select</option>

                                    <option value="present"
                                        {{ old('show', $labour->show) == 'present' ? 'selected' : '' }}>
                                        Present
                                    </option>

                                    <option value="absent"
                                        {{ old('show', $labour->show) == 'absent' ? 'selected' : '' }}>
                                        Absent
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Rupture of Membranes
                                </label>

                                <select name="rupture_of_membranes"
                                        class="form-select">

                                    <option value="">Select</option>

                                    <option value="intact"
                                        {{ old('rupture_of_membranes', $labour->rupture_of_membranes) == 'intact' ? 'selected' : '' }}>
                                        Intact
                                    </option>

                                    <option value="spontaneous rupture"
                                        {{ old('rupture_of_membranes', $labour->rupture_of_membranes) == 'spontaneous rupture' ? 'selected' : '' }}>
                                        Spontaneous Rupture
                                    </option>

                                    <option value="artificial rupture"
                                        {{ old('rupture_of_membranes', $labour->rupture_of_membranes) == 'artificial rupture' ? 'selected' : '' }}>
                                        Artificial Rupture
                                    </option>

                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    Liquor
                                </label>

                                <textarea name="liquor"
                                          rows="2"
                                          class="form-control">{{ old('liquor', $labour->liquor) }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Maternal Vitals -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-heart-pulse"></i>
                            Maternal Vital Signs
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-3 mb-3">
                                <label class="form-label">
                                    Blood Pressure
                                </label>

                                <input type="text"
                                       name="blood_pressure"
                                       class="form-control"
                                       value="{{ old('blood_pressure', $labour->blood_pressure) }}"
                                       placeholder="120/80">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">
                                    Pulse Rate
                                </label>

                                <input type="number"
                                       name="pulse_rate"
                                       class="form-control"
                                       value="{{ old('pulse_rate', $labour->pulse_rate) }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">
                                    Temperature
                                </label>

                                <input type="text"
                                       name="temperature"
                                       class="form-control"
                                       value="{{ old('temperature', $labour->temperature) }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">
                                    Respiration Rate
                                </label>

                                <input type="text"
                                       name="respiration_rate"
                                       class="form-control"
                                       value="{{ old('respiration_rate', $labour->respiration_rate) }}">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Labour Progress -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-diagram-3"></i>
                            Labour Progress
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Stage
                                </label>

                                <select name="stage"
                                        class="form-select">

                                    <option value="not_started"
                                        {{ old('stage', $labour->stage) == 'not_started' ? 'selected' : '' }}>
                                        Not Started
                                    </option>

                                    <option value="first_stage"
                                        {{ old('stage', $labour->stage) == 'first_stage' ? 'selected' : '' }}>
                                        First Stage
                                    </option>

                                    <option value="second_stage"
                                        {{ old('stage', $labour->stage) == 'second_stage' ? 'selected' : '' }}>
                                        Second Stage
                                    </option>

                                    <option value="third_stage"
                                        {{ old('stage', $labour->stage) == 'third_stage' ? 'selected' : '' }}>
                                        Third Stage
                                    </option>

                                    <option value="completed"
                                        {{ old('stage', $labour->stage) == 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>

                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Status
                                </label>

                                <select name="status"
                                        class="form-select">

                                    <option value="ongoing"
                                        {{ old('status', $labour->status) == 'ongoing' ? 'selected' : '' }}>
                                        Ongoing
                                    </option>

                                    <option value="completed"
                                        {{ old('status', $labour->status) == 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>

                                    <option value="complicated"
                                        {{ old('status', $labour->status) == 'complicated' ? 'selected' : '' }}>
                                        Complicated
                                    </option>

                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    First Stage Started
                                </label>

                                <input type="datetime-local"
                                       name="first_stage_started_at"
                                       class="form-control"
                                       value="{{ old('first_stage_started_at', optional($labour->first_stage_started_at)->format('Y-m-d\TH:i')) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Second Stage Started
                                </label>

                                <input type="datetime-local"
                                       name="second_stage_started_at"
                                       class="form-control"
                                       value="{{ old('second_stage_started_at', optional($labour->second_stage_started_at)->format('Y-m-d\TH:i')) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Third Stage Started
                                </label>

                                <input type="datetime-local"
                                       name="third_stage_started_at"
                                       class="form-control"
                                       value="{{ old('third_stage_started_at', optional($labour->third_stage_started_at)->format('Y-m-d\TH:i')) }}">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Fetal Monitoring -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-heart-fill"></i>
                            Fetal Monitoring
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Fetal Heart Rate
                                </label>

                                <input type="number"
                                       name="fetal_heart_rate"
                                       class="form-control"
                                       value="{{ old('fetal_heart_rate', $labour->fetal_heart_rate) }}">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    Fetal Monitoring Notes
                                </label>

                                <textarea name="fetal_monitoring_notes"
                                          rows="3"
                                          class="form-control">{{ old('fetal_monitoring_notes', $labour->fetal_monitoring_notes) }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Complications -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-exclamation-triangle"></i>
                            Complications
                        </h6>
                    </div>

                    <div class="card-body">

                        <textarea name="complications"
                                  rows="4"
                                  class="form-control">{{ old('complications', $labour->complications) }}</textarea>

                    </div>
                </div>

                <!-- Clinical Notes -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-journal-medical"></i>
                            Clinical Notes
                        </h6>
                    </div>

                    <div class="card-body">

                        <textarea name="clinical_notes"
                                  rows="5"
                                  class="form-control">{{ old('clinical_notes', $labour->clinical_notes) }}</textarea>

                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2 mb-5">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-circle"></i>
                        Update Labour Record
                    </button>

                    <a href="{{ route('midwife.labour.index') }}"
                       class="btn btn-outline-secondary">

                        Cancel
                    </a>

                </div>

            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-3">

                <div class="card sticky-top shadow-sm"
                     style="top:20px;">

                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle"></i>
                            Labour Record Info
                        </h6>
                    </div>

                    <div class="card-body">

                        <p>
                            <strong>Record ID:</strong>
                            {{ $labour->id }}
                        </p>

                        <hr>

                        <small class="text-muted">

                            Created:
                            {{ $labour->created_at->format('M d, Y h:i A') }}

                            <br><br>

                            Updated:
                            {{ $labour->updated_at->format('M d, Y h:i A') }}

                        </small>

                    </div>
                </div>

            </div>

        </div>

    </form>

</div>
@endsection