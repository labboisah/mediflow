@extends('layouts.app')

@section('title', 'Edit Postnatal Examination')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-pencil-square"></i>
                Edit Postnatal Examination
            </h1>

            <small class="text-muted">

                Mother:
                <strong>
                    {{ $postnatalExamination->patient->full_name }}
                </strong>

                |

                Delivery Type:
                <strong class="text-capitalize">
                    {{ str_replace('_', ' ', $postnatalExamination->delivery->delivery_type) }}
                </strong>

            </small>

        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('midwife.postnatal-examination.show', $postnatalExamination) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

    </div>

    <form action="{{ route('midwife.postnatal-examination.update', $postnatalExamination) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="row">

            <!-- Main Form -->
            <div class="col-lg-9">

                <!-- Examination Details -->
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-calendar-check"></i>
                            Examination Details
                        </h6>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">
                                    Examination Date & Time
                                </label>

                                <input type="datetime-local"
                                       name="examination_date_time"
                                       class="form-control @error('examination_date_time') is-invalid @enderror"
                                       value="{{ old('examination_date_time', optional($postnatalExamination->examination_date_time)->format('Y-m-d\TH:i')) }}">

                                @error('examination_date_time')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Hours Post Delivery
                                </label>

                                <input type="number"
                                       name="hours_post_delivery"
                                       class="form-control"
                                       value="{{ old('hours_post_delivery', $postnatalExamination->hours_post_delivery) }}">

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Examination Time
                                </label>

                                <select name="examination_time"
                                        class="form-select">

                                    <option value="">
                                        Choose...
                                    </option>

                                    @foreach([
                                        'immediate_0-2h' => 'Immediate (0–2 Hours)',
                                        '6-12h' => '6–12 Hours',
                                        '24h' => '24 Hours',
                                        '48h' => '48 Hours',
                                        'day4_6' => 'Day 4–6',
                                        'week1' => 'Week 1',
                                        'week2' => 'Week 2',
                                        'week6' => 'Week 6',
                                    ] as $value => $label)

                                        <option value="{{ $value }}"
                                            {{ old('examination_time', $postnatalExamination->examination_time) == $value ? 'selected' : '' }}>

                                            {{ $label }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Vital Signs -->
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-heart-pulse"></i>
                            Vital Signs
                        </h6>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-3">

                                <label class="form-label">
                                    Blood Pressure
                                </label>

                                <input type="text"
                                       name="blood_pressure"
                                       class="form-control"
                                       value="{{ old('blood_pressure', $postnatalExamination->blood_pressure) }}">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Pulse Rate
                                </label>

                                <input type="number"
                                       name="pulse_rate"
                                       class="form-control"
                                       value="{{ old('pulse_rate', $postnatalExamination->pulse_rate) }}">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Temperature
                                </label>

                                <input type="number"
                                       step="0.1"
                                       name="temperature"
                                       class="form-control"
                                       value="{{ old('temperature', $postnatalExamination->temperature) }}">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Respiration Rate
                                </label>

                                <input type="number"
                                       name="respiration_rate"
                                       class="form-control"
                                       value="{{ old('respiration_rate', $postnatalExamination->respiration_rate) }}">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- General Assessment -->
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-person-check"></i>
                            General Assessment
                        </h6>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    General Appearance
                                </label>

                                <textarea name="general_appearance"
                                          rows="3"
                                          class="form-control">{{ old('general_appearance', $postnatalExamination->general_appearance) }}</textarea>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Consciousness Level
                                </label>

                                <select name="consciousness_level"
                                        class="form-select">

                                    <option value="">
                                        Choose...
                                    </option>

                                    @foreach([
                                        'alert' => 'Alert',
                                        'drowsy' => 'Drowsy',
                                        'unconscious' => 'Unconscious'
                                    ] as $value => $label)

                                        <option value="{{ $value }}"
                                            {{ old('consciousness_level', $postnatalExamination->consciousness_level) == $value ? 'selected' : '' }}>

                                            {{ $label }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Skin Colour
                                </label>

                                <input type="text"
                                       name="skin_colour"
                                       class="form-control"
                                       value="{{ old('skin_colour', $postnatalExamination->skin_colour) }}">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Uterine Assessment -->
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-circle"></i>
                            Uterine Assessment
                        </h6>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-3">

                                <label class="form-label">
                                    Uterine Size
                                </label>

                                <input type="text"
                                       name="uterine_size"
                                       class="form-control"
                                       value="{{ old('uterine_size', $postnatalExamination->uterine_size) }}">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Uterine Consistency
                                </label>

                                <select name="uterine_consistency"
                                        class="form-select">

                                    <option value="">
                                        Choose...
                                    </option>

                                    @foreach([
                                        'firm' => 'Firm',
                                        'soft' => 'Soft',
                                        'boggy' => 'Boggy'
                                    ] as $value => $label)

                                        <option value="{{ $value }}"
                                            {{ old('uterine_consistency', $postnatalExamination->uterine_consistency) == $value ? 'selected' : '' }}>

                                            {{ $label }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Uterine Tenderness
                                </label>

                                <input type="text"
                                       name="uterine_tenderness"
                                       class="form-control"
                                       value="{{ old('uterine_tenderness', $postnatalExamination->uterine_tenderness) }}">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Fundal Height
                                </label>

                                <input type="text"
                                       name="fundal_height"
                                       class="form-control"
                                       value="{{ old('fundal_height', $postnatalExamination->fundal_height) }}">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Lochia Assessment -->
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-droplet"></i>
                            Lochia Assessment
                        </h6>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-3">

                                <label class="form-label">
                                    Lochia Type
                                </label>

                                <select name="lochia_type"
                                        class="form-select">

                                    <option value="">
                                        Choose...
                                    </option>

                                    @foreach([
                                        'rubra' => 'Rubra',
                                        'serosa' => 'Serosa',
                                        'alba' => 'Alba'
                                    ] as $value => $label)

                                        <option value="{{ $value }}"
                                            {{ old('lochia_type', $postnatalExamination->lochia_type) == $value ? 'selected' : '' }}>

                                            {{ $label }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Lochia Amount
                                </label>

                                <select name="lochia_amount"
                                        class="form-select">

                                    <option value="">
                                        Choose...
                                    </option>

                                    @foreach([
                                        'minimal' => 'Minimal',
                                        'moderate' => 'Moderate',
                                        'heavy' => 'Heavy'
                                    ] as $value => $label)

                                        <option value="{{ $value }}"
                                            {{ old('lochia_amount', $postnatalExamination->lochia_amount) == $value ? 'selected' : '' }}>

                                            {{ $label }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Lochia Odour
                                </label>

                                <input type="text"
                                       name="lochia_odour"
                                       class="form-control"
                                       value="{{ old('lochia_odour', $postnatalExamination->lochia_odour) }}">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Clots Present
                                </label>

                                <select name="clot_presence"
                                        class="form-select">

                                    <option value="0"
                                        {{ old('clot_presence', $postnatalExamination->clot_presence) == 0 ? 'selected' : '' }}>
                                        No
                                    </option>

                                    <option value="1"
                                        {{ old('clot_presence', $postnatalExamination->clot_presence) == 1 ? 'selected' : '' }}>
                                        Yes
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Continue same pattern for all remaining sections -->
                <!-- Perineal Assessment -->
                <!-- Breastfeeding -->
                <!-- Additional Assessments -->
                <!-- Mental Health -->
                <!-- Summary -->

                <!-- Summary & Plan -->
                <div class="card shadow-sm mb-4">

                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-journal-medical"></i>
                            Summary & Plan
                        </h6>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <label class="form-label">
                                Clinical Summary
                            </label>

                            <textarea name="clinical_summary"
                                      rows="4"
                                      class="form-control">{{ old('clinical_summary', $postnatalExamination->clinical_summary) }}</textarea>

                        </div>

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">
                                    Recovery Status
                                </label>

                                <select name="recovery_status"
                                        class="form-select">

                                    @foreach([
                                        'normal' => 'Normal Recovery',
                                        'complicated' => 'Complicated Recovery',
                                        'needs_referral' => 'Needs Referral'
                                    ] as $value => $label)

                                        <option value="{{ $value }}"
                                            {{ old('recovery_status', $postnatalExamination->recovery_status) == $value ? 'selected' : '' }}>

                                            {{ $label }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Next Follow-up Date
                                </label>

                                <input type="date"
                                       name="next_follow_up_date"
                                       class="form-control"
                                       value="{{ old('next_follow_up_date', optional($postnatalExamination->next_follow_up_date)->format('Y-m-d')) }}">

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Contraception Discussed
                                </label>

                                <select name="contraception_discussed"
                                        class="form-select">

                                    <option value="0"
                                        {{ old('contraception_discussed', $postnatalExamination->contraception_discussed) == 0 ? 'selected' : '' }}>
                                        No
                                    </option>

                                    <option value="1"
                                        {{ old('contraception_discussed', $postnatalExamination->contraception_discussed) == 1 ? 'selected' : '' }}>
                                        Yes
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2 mb-5">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-circle"></i>
                        Update Examination

                    </button>

                    <a href="{{ route('midwife.postnatal-examination.show', $postnatalExamination) }}"
                       class="btn btn-outline-secondary">

                        Cancel

                    </a>

                </div>

            </div>

            <!-- Sidebar -->
            <div class="col-lg-3">

                <div class="card shadow-sm sticky-top"
                     style="top:20px;">

                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle"></i>
                            Record Information
                        </h6>
                    </div>

                    <div class="card-body">

                        <small class="text-muted">

                            <strong>Created:</strong>
                            <br>
                            {{ $postnatalExamination->created_at->format('M d, Y h:i A') }}

                            <hr>

                            <strong>Last Updated:</strong>
                            <br>
                            {{ $postnatalExamination->updated_at->format('M d, Y h:i A') }}

                            <hr>

                            <strong>Recorded By:</strong>
                            <br>
                            {{ $postnatalExamination->recordedBy->name ?? 'N/A' }}

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection