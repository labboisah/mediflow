@extends('layouts.app')

@section('title', 'New Newborn Examination')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-clipboard2-pulse"></i>
                Record Newborn Examination
            </h1>

            <small class="text-muted">
                Newborn:
                {{ $newborn->newborn_registration_number ?? 'N/A' }}
            </small>

        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('midwife.newborn.show', $newborn) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

    </div>

    <form action="{{ route('midwife.newborn-examination.store', $newborn) }}"
          method="POST">

        @csrf

        <div class="row">

            <!-- Main Content -->
            <div class="col-lg-9">

                <!-- Examination Details -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-calendar-check"></i>
                            Examination Details
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Examination Date & Time
                                </label>

                                <input type="datetime-local"
                                       name="examination_date_time"
                                       class="form-control @error('examination_date_time') is-invalid @enderror"
                                       value="{{ old('examination_date_time') }}">

                                @error('examination_date_time')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Hours After Birth
                                </label>

                                <input type="number"
                                       name="hours_after_birth"
                                       class="form-control"
                                       value="{{ old('hours_after_birth') }}"
                                       min="0">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Examination Status
                                </label>

                                <select name="exam_status"
                                        class="form-select">

                                    <option value="normal">
                                        Normal
                                    </option>

                                    <option value="abnormal">
                                        Abnormal
                                    </option>

                                    <option value="needs_follow_up">
                                        Needs Follow-up
                                    </option>

                                    <option value="referral_needed">
                                        Referral Needed
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Vital Signs -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-heart-pulse"></i>
                            Vital Signs
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Temperature (°C)
                                </label>

                                <input type="number"
                                       step="0.1"
                                       name="temperature"
                                       class="form-control"
                                       value="{{ old('temperature') }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Heart Rate (bpm)
                                </label>

                                <input type="number"
                                       name="heart_rate"
                                       class="form-control"
                                       value="{{ old('heart_rate') }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Respiratory Rate
                                </label>

                                <input type="number"
                                       name="respiratory_rate"
                                       class="form-control"
                                       value="{{ old('respiratory_rate') }}">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Anthropometry -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-rulers"></i>
                            Anthropometry
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Weight (g)
                                </label>

                                <input type="number"
                                       name="weight"
                                       class="form-control"
                                       value="{{ old('weight') }}">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Length (cm)
                                </label>

                                <input type="number"
                                       step="0.1"
                                       name="length"
                                       class="form-control"
                                       value="{{ old('length') }}">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Head Circumference
                                </label>

                                <input type="number"
                                       step="0.1"
                                       name="head_circumference"
                                       class="form-control"
                                       value="{{ old('head_circumference') }}">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Chest Circumference
                                </label>

                                <input type="number"
                                       step="0.1"
                                       name="chest_circumference"
                                       class="form-control"
                                       value="{{ old('chest_circumference') }}">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- General Examination -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-person-check"></i>
                            General Examination
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    General Appearance
                                </label>

                                <textarea name="general_appearance"
                                          rows="2"
                                          class="form-control">{{ old('general_appearance') }}</textarea>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Skin Examination
                                </label>

                                <textarea name="skin_examination"
                                          rows="2"
                                          class="form-control">{{ old('skin_examination') }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Head & Neck
                                </label>

                                <textarea name="head_and_neck"
                                          rows="2"
                                          class="form-control">{{ old('head_and_neck') }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Eyes Examination
                                </label>

                                <textarea name="eyes_examination"
                                          rows="2"
                                          class="form-control">{{ old('eyes_examination') }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Ear Examination
                                </label>

                                <textarea name="ear_examination"
                                          rows="2"
                                          class="form-control">{{ old('ear_examination') }}</textarea>

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Mouth & Throat
                                </label>

                                <textarea name="mouth_and_throat"
                                          rows="2"
                                          class="form-control">{{ old('mouth_and_throat') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Cardiovascular -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-heart"></i>
                            Cardiovascular Examination
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Heart Sounds
                                </label>

                                <textarea name="heart_sounds"
                                          rows="2"
                                          class="form-control">{{ old('heart_sounds') }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Pulses
                                </label>

                                <textarea name="pulses"
                                          rows="2"
                                          class="form-control">{{ old('pulses') }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Capillary Refill
                                </label>

                                <textarea name="capillary_refill"
                                          rows="2"
                                          class="form-control">{{ old('capillary_refill') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Respiratory -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-lungs"></i>
                            Respiratory Examination
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Chest Expansion
                                </label>

                                <textarea name="chest_expansion"
                                          rows="2"
                                          class="form-control">{{ old('chest_expansion') }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Breath Sounds
                                </label>

                                <textarea name="breath_sounds"
                                          rows="2"
                                          class="form-control">{{ old('breath_sounds') }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Nasal Breathing
                                </label>

                                <textarea name="nasal_breathing"
                                          rows="2"
                                          class="form-control">{{ old('nasal_breathing') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Abdominal Examination -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-circle"></i>
                            Abdominal Examination
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Abdomen Shape
                                </label>

                                <textarea name="abdomen_shape"
                                          rows="2"
                                          class="form-control">{{ old('abdomen_shape') }}</textarea>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Umbilical Cord Check
                                </label>

                                <textarea name="umbilical_cord_check"
                                          rows="2"
                                          class="form-control">{{ old('umbilical_cord_check') }}</textarea>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Hepatomegaly / Splenomegaly
                                </label>

                                <textarea name="hepatomegaly_splenomegaly"
                                          rows="2"
                                          class="form-control">{{ old('hepatomegaly_splenomegaly') }}</textarea>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Bowel Sounds
                                </label>

                                <textarea name="bowel_sounds"
                                          rows="2"
                                          class="form-control">{{ old('bowel_sounds') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Genitourinary -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-droplet"></i>
                            Genitourinary Examination
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Genitalia Examination
                                </label>

                                <textarea name="genitalia_examination"
                                          rows="2"
                                          class="form-control">{{ old('genitalia_examination') }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Urinary Output
                                </label>

                                <textarea name="urinary_output"
                                          rows="2"
                                          class="form-control">{{ old('urinary_output') }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Stool Output
                                </label>

                                <textarea name="stool_output"
                                          rows="2"
                                          class="form-control">{{ old('stool_output') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Neurological -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-cpu"></i>
                            Neurological Examination
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Reflex Assessment
                                </label>

                                <textarea name="reflex_assessment"
                                          rows="2"
                                          class="form-control">{{ old('reflex_assessment') }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Muscle Tone
                                </label>

                                <textarea name="muscle_tone"
                                          rows="2"
                                          class="form-control">{{ old('muscle_tone') }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Developmental Screening
                                </label>

                                <textarea name="developmental_screening"
                                          rows="2"
                                          class="form-control">{{ old('developmental_screening') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Musculoskeletal -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-universal-access"></i>
                            Musculoskeletal Examination
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Extremities Examination
                                </label>

                                <textarea name="extremities_examination"
                                          rows="2"
                                          class="form-control">{{ old('extremities_examination') }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Hip Examination
                                </label>

                                <textarea name="hip_examination"
                                          rows="2"
                                          class="form-control">{{ old('hip_examination') }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Spine Examination
                                </label>

                                <textarea name="spine_examination"
                                          rows="2"
                                          class="form-control">{{ old('spine_examination') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Special Findings -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-exclamation-triangle"></i>
                            Special Findings
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <label class="form-label">
                                Abnormal Findings
                            </label>

                            <textarea name="abnormal_findings"
                                      rows="3"
                                      class="form-control">{{ old('abnormal_findings') }}</textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Congenital Anomalies
                            </label>

                            <textarea name="congenital_anomalies"
                                      rows="3"
                                      class="form-control">{{ old('congenital_anomalies') }}</textarea>

                        </div>

                    </div>

                </div>

                <!-- Jaundice -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-sun"></i>
                            Jaundice Assessment
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Jaundice Present
                                </label>

                                <select name="jaundice_present"
                                        class="form-select">

                                    <option value="0">
                                        No
                                    </option>

                                    <option value="1">
                                        Yes
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Jaundice Level
                                </label>

                                <textarea name="jaundice_level"
                                          rows="2"
                                          class="form-control">{{ old('jaundice_level') }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Jaundice Management
                                </label>

                                <textarea name="jaundice_management"
                                          rows="2"
                                          class="form-control">{{ old('jaundice_management') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Feeding Assessment -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-cup-hot"></i>
                            Feeding Assessment
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Feeding Type
                                </label>

                                <select name="feeding_type"
                                        class="form-select">

                                    <option value="">
                                        Select
                                    </option>

                                    <option value="breast">
                                        Breast Feeding
                                    </option>

                                    <option value="bottle">
                                        Bottle Feeding
                                    </option>

                                    <option value="mixed">
                                        Mixed Feeding
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Feeding Tolerance
                                </label>

                                <textarea name="feeding_tolerance"
                                          rows="2"
                                          class="form-control">{{ old('feeding_tolerance') }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Feeding Challenges
                                </label>

                                <textarea name="feeding_challenges"
                                          rows="2"
                                          class="form-control">{{ old('feeding_challenges') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Overall Assessment -->
                <div class="card mb-4 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-journal-medical"></i>
                            Overall Assessment
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <label class="form-label">
                                Clinical Summary
                            </label>

                            <textarea name="clinical_summary"
                                      rows="4"
                                      class="form-control">{{ old('clinical_summary') }}</textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Follow-up Plans
                            </label>

                            <textarea name="follow_up_plans"
                                      rows="3"
                                      class="form-control">{{ old('follow_up_plans') }}</textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Next Follow-up Date
                            </label>

                            <input type="datetime-local"
                                   name="next_follow_up_date"
                                   class="form-control"
                                   value="{{ old('next_follow_up_date') }}">

                        </div>

                    </div>

                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2 mb-5">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-save"></i>
                        Save Examination

                    </button>

                    <a href="{{ route('midwife.newborn.show', $newborn) }}"
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
                            Neonatal Guide
                        </h6>

                    </div>

                    <div class="card-body">

                        <small class="text-muted">

                            <strong>Normal Temperature</strong>
                            <br>
                            36.5°C - 37.5°C

                            <hr>

                            <strong>Normal Heart Rate</strong>
                            <br>
                            120 - 160 bpm

                            <hr>

                            <strong>Normal Respiratory Rate</strong>
                            <br>
                            30 - 60 breaths/min

                            <hr>

                            <strong>Normal Weight</strong>
                            <br>
                            2500g - 4000g

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection