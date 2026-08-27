@extends('layouts.app')

@section('title', 'Edit Newborn Examination')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-pencil-square"></i>
                Edit Newborn Examination
            </h1>

            <small class="text-muted">
                Update neonatal examination details
            </small>

        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('midwife.newborn-examination.show', $newbornExamination) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

    </div>

    <form action="{{ route('midwife.newborn-examination.update', $newbornExamination) }}"
          method="POST">

        @csrf
        @method('PUT')

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
                                       value="{{ old('examination_date_time', optional($newbornExamination->examination_date_time)->format('Y-m-d\TH:i')) }}">

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
                                       value="{{ old('hours_after_birth', $newbornExamination->hours_after_birth) }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Examination Status
                                </label>

                                <select name="exam_status"
                                        class="form-select">

                                    <option value="normal"
                                        {{ old('exam_status', $newbornExamination->exam_status) == 'normal' ? 'selected' : '' }}>
                                        Normal
                                    </option>

                                    <option value="abnormal"
                                        {{ old('exam_status', $newbornExamination->exam_status) == 'abnormal' ? 'selected' : '' }}>
                                        Abnormal
                                    </option>

                                    <option value="needs_follow_up"
                                        {{ old('exam_status', $newbornExamination->exam_status) == 'needs_follow_up' ? 'selected' : '' }}>
                                        Needs Follow-up
                                    </option>

                                    <option value="referral_needed"
                                        {{ old('exam_status', $newbornExamination->exam_status) == 'referral_needed' ? 'selected' : '' }}>
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
                                       value="{{ old('temperature', $newbornExamination->temperature) }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Heart Rate (bpm)
                                </label>

                                <input type="number"
                                       name="heart_rate"
                                       class="form-control"
                                       value="{{ old('heart_rate', $newbornExamination->heart_rate) }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Respiratory Rate
                                </label>

                                <input type="number"
                                       name="respiratory_rate"
                                       class="form-control"
                                       value="{{ old('respiratory_rate', $newbornExamination->respiratory_rate) }}">

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
                                       value="{{ old('weight', $newbornExamination->weight) }}">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Length (cm)
                                </label>

                                <input type="number"
                                       step="0.1"
                                       name="length"
                                       class="form-control"
                                       value="{{ old('length', $newbornExamination->length) }}">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Head Circumference
                                </label>

                                <input type="number"
                                       step="0.1"
                                       name="head_circumference"
                                       class="form-control"
                                       value="{{ old('head_circumference', $newbornExamination->head_circumference) }}">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Chest Circumference
                                </label>

                                <input type="number"
                                       step="0.1"
                                       name="chest_circumference"
                                       class="form-control"
                                       value="{{ old('chest_circumference', $newbornExamination->chest_circumference) }}">

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

                        <div class="mb-3">

                            <label class="form-label">
                                General Appearance
                            </label>

                            <textarea name="general_appearance"
                                      rows="2"
                                      class="form-control">{{ old('general_appearance', $newbornExamination->general_appearance) }}</textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Skin Examination
                            </label>

                            <textarea name="skin_examination"
                                      rows="2"
                                      class="form-control">{{ old('skin_examination', $newbornExamination->skin_examination) }}</textarea>

                        </div>

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Head & Neck
                                </label>

                                <textarea name="head_and_neck"
                                          rows="2"
                                          class="form-control">{{ old('head_and_neck', $newbornExamination->head_and_neck) }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Eyes Examination
                                </label>

                                <textarea name="eyes_examination"
                                          rows="2"
                                          class="form-control">{{ old('eyes_examination', $newbornExamination->eyes_examination) }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Ear Examination
                                </label>

                                <textarea name="ear_examination"
                                          rows="2"
                                          class="form-control">{{ old('ear_examination', $newbornExamination->ear_examination) }}</textarea>

                            </div>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Mouth & Throat
                            </label>

                            <textarea name="mouth_and_throat"
                                      rows="2"
                                      class="form-control">{{ old('mouth_and_throat', $newbornExamination->mouth_and_throat) }}</textarea>

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
                                          class="form-control">{{ old('heart_sounds', $newbornExamination->heart_sounds) }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Pulses
                                </label>

                                <textarea name="pulses"
                                          rows="2"
                                          class="form-control">{{ old('pulses', $newbornExamination->pulses) }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Capillary Refill
                                </label>

                                <textarea name="capillary_refill"
                                          rows="2"
                                          class="form-control">{{ old('capillary_refill', $newbornExamination->capillary_refill) }}</textarea>

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
                                          class="form-control">{{ old('chest_expansion', $newbornExamination->chest_expansion) }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Breath Sounds
                                </label>

                                <textarea name="breath_sounds"
                                          rows="2"
                                          class="form-control">{{ old('breath_sounds', $newbornExamination->breath_sounds) }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Nasal Breathing
                                </label>

                                <textarea name="nasal_breathing"
                                          rows="2"
                                          class="form-control">{{ old('nasal_breathing', $newbornExamination->nasal_breathing) }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Additional Sections -->
                <!-- You can continue remaining sections exactly same pattern -->

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
                                      class="form-control">{{ old('clinical_summary', $newbornExamination->clinical_summary) }}</textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Follow-up Plans
                            </label>

                            <textarea name="follow_up_plans"
                                      rows="3"
                                      class="form-control">{{ old('follow_up_plans', $newbornExamination->follow_up_plans) }}</textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Next Follow-up Date
                            </label>

                            <input type="datetime-local"
                                   name="next_follow_up_date"
                                   class="form-control"
                                   value="{{ old('next_follow_up_date', optional($newbornExamination->next_follow_up_date)->format('Y-m-d\TH:i')) }}">

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

                    <a href="{{ route('midwife.newborn-examination.show', $newbornExamination) }}"
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
                            {{ $newbornExamination->created_at->format('M d, Y h:i A') }}

                            <hr>

                            <strong>Last Updated:</strong>
                            <br>
                            {{ $newbornExamination->updated_at->format('M d, Y h:i A') }}

                            <hr>

                            <strong>Recorded By:</strong>
                            <br>
                            {{ $newbornExamination->recordedBy->name ?? 'N/A' }}

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection