@extends('layouts.app')

@section('title', 'Create Labour Record - ' . $patient->full_name)

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="bi bi-plus-circle"></i>
                New Labour Record
            </h1>

            <small class="text-muted">
                Record labour admission for {{ $patient->name() }}
            </small>
        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('midwife.labour.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back
            </a>

        </div>

    </div>

    <form action="{{ route('midwife.labour.store', $patient) }}"
          method="POST">

        @csrf

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
                                <small class="text-muted">
                                    Hospital Number
                                </small>

                                <p class="fw-bold">
                                    {{ $patient->hospital_number }}
                                </p>
                            </div>

                            <div class="col-md-3">
                                <small class="text-muted">
                                    Patient Name
                                </small>

                                <p class="fw-bold">
                                    {{ $patient->name() }}
                                </p>
                            </div>

                            <div class="col-md-3">
                                <small class="text-muted">
                                    Age
                                </small>

                                <p class="fw-bold">
                                    {{ $patient->age() }} years
                                </p>
                            </div>

                            <div class="col-md-3">
                                <small class="text-muted">
                                    Gender
                                </small>

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
                                       value="{{ old('labour_onset_time', now()->format('Y-m-d\TH:i')) }}"
                                       placeholder="Select onset date and time">

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

                                    <option value="">
                                        Select
                                    </option>

                                    <option value="spontaneous"
                                        {{ old('mode_of_onset') == 'spontaneous' ? 'selected' : '' }}>
                                        Spontaneous
                                    </option>

                                    <option value="induced"
                                        {{ old('mode_of_onset') == 'induced' ? 'selected' : '' }}>
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
                                       value="{{ old('gestational_weeks') }}"
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
                                       value="{{ old('labour_type') }}"
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
                                          class="form-control @error('reason_for_induction') is-invalid @enderror"
                                          placeholder="If induction planned, provide the reason">{{ old('reason_for_induction') }}</textarea>

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
                                          class="form-control @error('previous_obstetric_history') is-invalid @enderror"
                                          placeholder="E.g. prior deliveries, complications, C-sections">{{ old('previous_obstetric_history') }}</textarea>

                                @error('previous_obstetric_history')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Pre Labour Assessment -->
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
                                       value="{{ old('cervical_state') }}"
                                       placeholder="E.g. 3cm / 70% / 0 station">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Show
                                </label>

                                <select name="show"
                                        class="form-select">

                                    <option value="">
                                        Select
                                    </option>

                                    <option value="present"
                                        {{ old('show') == 'present' ? 'selected' : '' }}>
                                        Present
                                    </option>

                                    <option value="absent"
                                        {{ old('show') == 'absent' ? 'selected' : '' }}>
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

                                    <option value="">
                                        Select
                                    </option>

                                    <option value="intact">
                                        Intact
                                    </option>

                                    <option value="spontaneous rupture">
                                        Spontaneous Rupture
                                    </option>

                                    <option value="artificial rupture">
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
                                          class="form-control"
                                          placeholder="E.g. clear, meconium-stained, blood-stained">{{ old('liquor') }}</textarea>

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
                                       value="{{ old('blood_pressure') }}"
                                       placeholder="120/80">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Pulse Rate
                                </label>

                                <input type="number"
                                       name="pulse_rate"
                                       class="form-control"
                                       value="{{ old('pulse_rate') }}"
                                       placeholder="e.g. 70">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Temperature
                                </label>

                                <input type="number"
                                       step="0.1"
                                       name="temperature"
                                       class="form-control"
                                       value="{{ old('temperature') }}"
                                       placeholder="36.5 - 37.5">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Respiration Rate
                                </label>

                                <input type="number"
                                       name="respiration_rate"
                                       class="form-control"
                                       value="{{ old('respiration_rate') }}"
                                       placeholder="12 - 20">

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

                                    <option value="not_started">
                                        Not Started
                                    </option>

                                    <option value="first_stage">
                                        First Stage
                                    </option>

                                    <option value="second_stage">
                                        Second Stage
                                    </option>

                                    <option value="third_stage">
                                        Third Stage
                                    </option>

                                    <option value="completed">
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

                                    <option value="ongoing">
                                        Ongoing
                                    </option>

                                    <option value="completed">
                                        Completed
                                    </option>

                                    <option value="complicated">
                                        Complicated
                                    </option>

                                </select>

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
                                       value="{{ old('fetal_heart_rate') }}">

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Fetal Monitoring Notes
                                </label>

                                <textarea name="fetal_monitoring_notes"
                                          rows="3"
                                          class="form-control">{{ old('fetal_monitoring_notes') }}</textarea>

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
                                  class="form-control">{{ old('complications') }}</textarea>

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
                                  class="form-control">{{ old('clinical_notes') }}</textarea>

                    </div>

                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2 mb-5">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-circle"></i>
                        Create Labour Record

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
                            <i class="bi bi-lightbulb"></i>
                            Labour Guide
                        </h6>
                    </div>

                    <div class="card-body">

                        <small class="text-muted">

                            <strong>Normal Fetal HR</strong>
                            <br>
                            120 - 160 bpm

                            <hr>

                            <strong>Normal Temperature</strong>
                            <br>
                            36.5°C - 37.5°C

                            <hr>

                            <strong>Labour Stages</strong>
                            <br>
                            First Stage
                            <br>
                            Second Stage
                            <br>
                            Third Stage

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection