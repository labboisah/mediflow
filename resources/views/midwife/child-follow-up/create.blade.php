@extends('layouts.app')

@section('title', 'Child Follow-up Assessment')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-clipboard2-pulse"></i>
                Child Follow-up Assessment
            </h1>

            <small class="text-muted">

                Baby:
                <strong>
                    {{ $newborn->newborn_registration_number ?? 'N/A' }}
                </strong>

                |

                Mother:
                <strong>
                    {{ $newborn->patient->full_name }}
                </strong>

            </small>

        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('midwife.child-follow-up.record', $newborn) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

    </div>

    <form action="{{ route('midwife.child-follow-up.store', $newborn) }}"
          method="POST">

        @csrf

        <div class="row">

            <!-- Main Content -->
            <div class="col-lg-9">

                <!-- Follow-up Details -->
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-calendar-check"></i>
                            Follow-up Details
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">
                                    Follow-up Date & Time
                                </label>

                                <input type="datetime-local"
                                       name="follow_up_date_time"
                                       class="form-control @error('follow_up_date_time') is-invalid @enderror"
                                       value="{{ old('follow_up_date_time') }}" >

                                @error('follow_up_date_time')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Days of Life
                                </label>

                                <input type="number"
                                       name="days_of_life"
                                       class="form-control"
                                       value="{{ old('days_of_life') }}" placeholder="e.g. 3">

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Follow-up Period
                                </label>

                                <select name="follow_up_period"
                                        class="form-select">

                                    <option value="">
                                        Choose...
                                    </option>

                                    <option value="day_3">Day 3</option>
                                    <option value="day_7">Day 7</option>
                                    <option value="day_10">Day 10</option>
                                    <option value="day_14">Day 14</option>
                                    <option value="6weeks">6 Weeks</option>
                                    <option value="3months">3 Months</option>
                                    <option value="6months">6 Months</option>
                                    <option value="year1">1 Year</option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Location -->
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-geo-alt"></i>
                            Follow-up Location
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">
                                    Location
                                </label>

                                <select name="location"
                                        class="form-select">

                                    <option value="hospital">Hospital</option>
                                    <option value="clinic">Clinic</option>
                                    <option value="home">Home</option>
                                    <option value="other">Other</option>

                                </select>

                            </div>

                            <div class="col-md-8">

                                <label class="form-label">
                                    Location Details
                                </label>

                                <textarea name="location_details"
                                          rows="2"
                                          class="form-control">{{ old('location_details') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Feeding -->
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-heart"></i>
                            Feeding Assessment
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">
                                    Feeding Type
                                </label>

                                <select name="feeding_type"
                                        class="form-select">

                                    <option value="">
                                        Choose...
                                    </option>

                                    <option value="exclusive_breastfeeding">
                                        Exclusive Breastfeeding
                                    </option>

                                    <option value="formula">
                                        Formula Feeding
                                    </option>

                                    <option value="mixed">
                                        Mixed Feeding
                                    </option>

                                    <option value="complementary_feeding">
                                        Complementary Feeding
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Feeding Frequency
                                </label>

                                <input type="text"
                                       name="feeding_frequency"
                                       class="form-control"
                                       value="{{ old('feeding_frequency') }}" placeholder="e.g. Every 2-3 hours">

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Feeding Duration
                                </label>

                                <input type="text"
                                       name="feeding_duration"
                                       class="form-control"
                                       value="{{ old('feeding_duration') }}" placeholder="e.g. 10-15 minutes">

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    How Baby is Feeding
                                </label>

                                <textarea name="how_baby_is_feeding"
                                          rows="3"
                                          class="form-control">{{ old('how_baby_is_feeding') }}</textarea>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Feeding Problems
                                </label>

                                <textarea name="feeding_problems"
                                          rows="3"
                                          class="form-control">{{ old('feeding_problems') }}</textarea>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Latching Quality
                                </label>

                                <textarea name="latching_quality"
                                          rows="2"
                                          class="form-control">{{ old('latching_quality') }}</textarea>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Suckling Pattern
                                </label>

                                <textarea name="suckling_pattern"
                                          rows="2"
                                          class="form-control">{{ old('suckling_pattern') }}</textarea>

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

                            <div class="col-md-4">

                                <label class="form-label">
                                    Temperature (°C)
                                </label>

                                <input type="number"
                                       step="0.1"
                                       name="temperature"
                                       class="form-control"
                                       value="{{ old('temperature') }}" placeholder="e.g. 37.5">

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Heart Rate
                                </label>

                                <input type="number"
                                       name="heart_rate"
                                       class="form-control"
                                       value="{{ old('heart_rate') }}" placeholder="beats per minute eg. 120">

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Respiratory Rate
                                </label>

                                <input type="number"
                                       name="respiratory_rate"
                                       class="form-control"
                                       value="{{ old('respiratory_rate') }}" placeholder="breaths per minute eg. 40">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Growth Parameters -->
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-rulers"></i>
                            Growth Parameters
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-3">

                                <label class="form-label">
                                    Weight (kg)
                                </label>

                                <input type="number"
                                       step="0.01"
                                       name="weight"
                                       class="form-control"
                                       value="{{ old('weight') }}" placeholder="e.g. 3.5">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Length (cm)
                                </label>

                                <input type="number"
                                       step="0.01"
                                       name="length"
                                       class="form-control"
                                       value="{{ old('length') }}" placeholder="e.g. 50">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Head Circumference
                                </label>

                                <input type="number"
                                       step="0.01"
                                       name="head_circumference"
                                       class="form-control"
                                       value="{{ old('head_circumference') }}" placeholder="e.g. 35">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Weight Percentile
                                </label>

                                <input type="number"
                                       step="0.01"
                                       name="weight_percentile"
                                       class="form-control"
                                       value="{{ old('weight_percentile') }}" placeholder="e.g. 50">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Jaundice -->
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-sun"></i>
                            Jaundice Assessment
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">
                                    Jaundice Present
                                </label>

                                <select name="jaundice_present"
                                        class="form-select">

                                    <option value="0">No</option>
                                    <option value="1">Yes</option>

                                </select>

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Jaundice Level
                                </label>

                                <textarea name="jaundice_level"
                                          rows="2"
                                          class="form-control">{{ old('jaundice_level') }}</textarea>

                            </div>

                            <div class="col-md-4">

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

                <!-- Immunization -->
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-shield-check"></i>
                            Immunization & Screening
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">
                                    Immunizations Up to Date
                                </label>

                                <select name="immunizations_up_to_date"
                                        class="form-select">

                                    <option value="0">No</option>
                                    <option value="1">Yes</option>

                                </select>

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Newborn Screening Done
                                </label>

                                <select name="newborn_screening_done"
                                        class="form-select">

                                    <option value="0">No</option>
                                    <option value="1">Yes</option>

                                </select>

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Hearing Screening Done
                                </label>

                                <select name="hearing_screening_done"
                                        class="form-select">

                                    <option value="0">No</option>
                                    <option value="1">Yes</option>

                                </select>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Immunizations Given
                                </label>

                                <textarea name="immunizations_given"
                                          rows="3"
                                          class="form-control">{{ old('immunizations_given') }}</textarea>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Immunizations Planned
                                </label>

                                <textarea name="immunizations_planned"
                                          rows="3"
                                          class="form-control">{{ old('immunizations_planned') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Clinical Summary -->
                <div class="card shadow-sm mb-4">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-journal-medical"></i>
                            Clinical Summary & Plan
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

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">
                                    Health Status
                                </label>

                                <select name="health_status"
                                        class="form-select">

                                    <option value="normal">
                                        Normal
                                    </option>

                                    <option value="at_risk">
                                        At Risk
                                    </option>

                                    <option value="needs_referral">
                                        Needs Referral
                                    </option>

                                    <option value="referred">
                                        Referred
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Next Follow-up Date
                                </label>

                                <input type="datetime-local"
                                       name="next_follow_up_date"
                                       class="form-control"
                                       value="{{ old('next_follow_up_date') }}">

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Danger Signs Explained
                                </label>

                                <select name="danger_signs_explained"
                                        class="form-select">

                                    <option value="0">No</option>
                                    <option value="1">Yes</option>

                                </select>

                            </div>

                        </div>

                        <div class="row g-3 mt-2">

                            <div class="col-md-6">

                                <label class="form-label">
                                    Management Plan
                                </label>

                                <textarea name="management_plan"
                                          rows="3"
                                          class="form-control">{{ old('management_plan') }}</textarea>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Referral Reason
                                </label>

                                <textarea name="referral_reason"
                                          rows="3"
                                          class="form-control">{{ old('referral_reason') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2 mb-5">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-save"></i>
                        Save Follow-up

                    </button>

                    <a href="{{ route('midwife.child-follow-up.record', $newborn) }}"
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
                            Child Information
                        </h6>

                    </div>

                    <div class="card-body">

                        <small class="text-muted">

                            <strong>Sex:</strong>
                            <br>
                            {{ ucfirst($newborn->sex) }}

                            <hr>

                            <strong>Birth Weight:</strong>
                            <br>
                            {{ $newborn->birth_weight ?? 'N/A' }}

                            <hr>

                            <strong>Birth Date:</strong>
                            <br>
                            {{ optional($newborn->birth_date_time)->format('M d, Y') }}

                            <hr>

                            <strong>APGAR Score:</strong>
                            <br>
                            1 min:
                            {{ $newborn->apgar_score_1_minute ?? 'N/A' }}

                            |
                            5 min:
                            {{ $newborn->apgar_score_5_minutes ?? 'N/A' }}

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection