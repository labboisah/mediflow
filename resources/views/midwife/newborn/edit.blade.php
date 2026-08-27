@extends('layouts.app')

@section('title', 'Edit Newborn Record')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-pencil-square"></i>
                Edit Newborn Record
            </h1>

            <small class="text-muted">
                Update newborn details and neonatal observations
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

    <form action="{{ route('midwife.newborn.update', $newborn) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="row">

            <!-- Main Content -->
            <div class="col-lg-9">

                <!-- Delivery Information -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-heart-pulse"></i>
                            Delivery Information
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Mother
                                </small>

                                <p class="fw-bold">
                                    {{ $newborn->patient->name() }}
                                </p>

                            </div>

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Delivery Type
                                </small>

                                <p>

                                    <span class="badge bg-primary">
                                        {{ str_replace('_', ' ', ucfirst($newborn->delivery->delivery_type)) }}
                                    </span>

                                </p>

                            </div>

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Delivery Date
                                </small>

                                <p class="fw-bold">
                                    {{ optional($newborn->delivery->delivery_date_time)->format('M d, Y h:i A') }}
                                </p>

                            </div>

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Record Number
                                </small>

                                <p class="fw-bold">
                                    #{{ $newborn->id }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Newborn Information -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-baby"></i>
                            Newborn Information
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Sex
                                </label>

                                <select name="sex"
                                        class="form-select @error('sex') is-invalid @enderror">

                                    <option value="">
                                        Select
                                    </option>

                                    <option value="male"
                                        {{ old('sex', $newborn->sex) == 'male' ? 'selected' : '' }}>
                                        Male
                                    </option>

                                    <option value="female"
                                        {{ old('sex', $newborn->sex) == 'female' ? 'selected' : '' }}>
                                        Female
                                    </option>

                                </select>

                                @error('sex')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Birth Order
                                </label>

                                <input type="number"
                                       min="1"
                                       name="birth_order"
                                       class="form-control"
                                       value="{{ old('birth_order', $newborn->birth_order) }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Registration Number
                                </label>

                                <input type="text"
                                       name="newborn_registration_number"
                                       class="form-control"
                                       value="{{ old('newborn_registration_number', $newborn->newborn_registration_number) }}">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Birth Date & Time
                                </label>

                                <input type="datetime-local"
                                       name="birth_date_time"
                                       class="form-control"
                                       value="{{ old('birth_date_time', optional($newborn->birth_date_time)->format('Y-m-d\TH:i')) }}">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Presentation
                                </label>

                                <select name="presentation"
                                        class="form-select">

                                    <option value="">
                                        Select
                                    </option>

                                    <option value="cephalic"
                                        {{ old('presentation', $newborn->presentation) == 'cephalic' ? 'selected' : '' }}>
                                        Cephalic
                                    </option>

                                    <option value="breech"
                                        {{ old('presentation', $newborn->presentation) == 'breech' ? 'selected' : '' }}>
                                        Breech
                                    </option>

                                    <option value="transverse"
                                        {{ old('presentation', $newborn->presentation) == 'transverse' ? 'selected' : '' }}>
                                        Transverse
                                    </option>

                                    <option value="face"
                                        {{ old('presentation', $newborn->presentation) == 'face' ? 'selected' : '' }}>
                                        Face
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Measurements -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-rulers"></i>
                            Anthropometric Measurements
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Birth Weight (g)
                                </label>

                                <input type="text"
                                       name="birth_weight"
                                       class="form-control"
                                       value="{{ old('birth_weight', $newborn->birth_weight) }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Birth Length (cm)
                                </label>

                                <input type="text"
                                       name="birth_length"
                                       class="form-control"
                                       value="{{ old('birth_length', $newborn->birth_length) }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Head Circumference (cm)
                                </label>

                                <input type="text"
                                       name="head_circumference"
                                       class="form-control"
                                       value="{{ old('head_circumference', $newborn->head_circumference) }}">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- APGAR -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-clipboard-pulse"></i>
                            APGAR Scores
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    1 Minute
                                </label>

                                <input type="number"
                                       min="0"
                                       max="10"
                                       name="apgar_score_1_minute"
                                       class="form-control"
                                       value="{{ old('apgar_score_1_minute', $newborn->apgar_score_1_minute) }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    5 Minutes
                                </label>

                                <input type="number"
                                       min="0"
                                       max="10"
                                       name="apgar_score_5_minutes"
                                       class="form-control"
                                       value="{{ old('apgar_score_5_minutes', $newborn->apgar_score_5_minutes) }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    10 Minutes
                                </label>

                                <input type="number"
                                       min="0"
                                       max="10"
                                       name="apgar_score_10_minutes"
                                       class="form-control"
                                       value="{{ old('apgar_score_10_minutes', $newborn->apgar_score_10_minutes) }}">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Condition -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-heart"></i>
                            Newborn Condition
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <label class="form-label">
                                General Condition
                            </label>

                            <input type="text"
                                   name="general_condition"
                                   class="form-control"
                                   value="{{ old('general_condition', $newborn->general_condition) }}">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Physical Examination
                            </label>

                            <textarea name="physical_examination"
                                      rows="3"
                                      class="form-control">{{ old('physical_examination', $newborn->physical_examination) }}</textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Birth Defects Noted
                            </label>

                            <textarea name="birth_defects_noted"
                                      rows="2"
                                      class="form-control">{{ old('birth_defects_noted', $newborn->birth_defects_noted) }}</textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Meconium Aspiration
                            </label>

                            <textarea name="meconium_aspiration"
                                      rows="2"
                                      class="form-control">{{ old('meconium_aspiration', $newborn->meconium_aspiration) }}</textarea>

                        </div>

                    </div>

                </div>

                <!-- Feeding & Care -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-cup-hot"></i>
                            Feeding & Care
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="form-check mb-3">

                            <input type="checkbox"
                                   name="breastfeeding_initiated"
                                   value="1"
                                   class="form-check-input"
                                   {{ old('breastfeeding_initiated', $newborn->breastfeeding_initiated) ? 'checked' : '' }}>

                            <label class="form-check-label">
                                Breastfeeding Initiated
                            </label>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                First Breastfeed Time
                            </label>

                            <input type="datetime-local"
                                   name="first_breastfeed_time"
                                   class="form-control"
                                   value="{{ old('first_breastfeed_time', optional($newborn->first_breastfeed_time)->format('Y-m-d\TH:i')) }}">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Feeding Problems
                            </label>

                            <textarea name="feeding_problems"
                                      rows="2"
                                      class="form-control">{{ old('feeding_problems', $newborn->feeding_problems) }}</textarea>

                        </div>

                    </div>

                </div>

                <!-- Early Care -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-shield-check"></i>
                            Early Newborn Care
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="form-check mb-3">

                            <input type="checkbox"
                                   name="vitamin_k_given"
                                   value="1"
                                   class="form-check-input"
                                   {{ old('vitamin_k_given', $newborn->vitamin_k_given) ? 'checked' : '' }}>

                            <label class="form-check-label">
                                Vitamin K Given
                            </label>

                        </div>

                        <div class="form-check mb-3">

                            <input type="checkbox"
                                   name="eye_prophylaxis_given"
                                   value="1"
                                   class="form-check-input"
                                   {{ old('eye_prophylaxis_given', $newborn->eye_prophylaxis_given) ? 'checked' : '' }}>

                            <label class="form-check-label">
                                Eye Prophylaxis Given
                            </label>

                        </div>

                        <div class="form-check mb-3">

                            <input type="checkbox"
                                   name="immunizations_given"
                                   value="1"
                                   class="form-check-input"
                                   {{ old('immunizations_given', $newborn->immunizations_given) ? 'checked' : '' }}>

                            <label class="form-check-label">
                                Immunizations Given
                            </label>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Immunization Details
                            </label>

                            <textarea name="immunizations_details"
                                      rows="2"
                                      class="form-control">{{ old('immunizations_details', $newborn->immunizations_details) }}</textarea>

                        </div>

                    </div>

                </div>

                <!-- Screening & Interventions -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-hospital"></i>
                            Screening & Interventions
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="form-check mb-3">

                            <input type="checkbox"
                                   name="screening_test_done"
                                   value="1"
                                   class="form-check-input"
                                   {{ old('screening_test_done', $newborn->screening_test_done) ? 'checked' : '' }}>

                            <label class="form-check-label">
                                Screening Test Done
                            </label>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Screening Test Results
                            </label>

                            <textarea name="screening_test_results"
                                      rows="2"
                                      class="form-control">{{ old('screening_test_results', $newborn->screening_test_results) }}</textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Special Care Needed
                            </label>

                            <textarea name="special_care_needed"
                                      rows="2"
                                      class="form-control">{{ old('special_care_needed', $newborn->special_care_needed) }}</textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Referred To
                            </label>

                            <textarea name="referred_to"
                                      rows="2"
                                      class="form-control">{{ old('referred_to', $newborn->referred_to) }}</textarea>

                        </div>

                    </div>

                </div>

                <!-- Status -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-activity"></i>
                            Status & Observations
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <label class="form-label">
                                Status
                            </label>

                            <select name="status"
                                    class="form-select">

                                <option value="alive"
                                    {{ old('status', $newborn->status) == 'alive' ? 'selected' : '' }}>
                                    Alive
                                </option>

                                <option value="stillborn"
                                    {{ old('status', $newborn->status) == 'stillborn' ? 'selected' : '' }}>
                                    Stillborn
                                </option>

                                <option value="early_neonatal_death"
                                    {{ old('status', $newborn->status) == 'early_neonatal_death' ? 'selected' : '' }}>
                                    Early Neonatal Death
                                </option>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Neonatal Observations
                            </label>

                            <textarea name="neonatal_observations"
                                      rows="3"
                                      class="form-control">{{ old('neonatal_observations', $newborn->neonatal_observations) }}</textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Delivery Notes
                            </label>

                            <textarea name="delivery_notes"
                                      rows="3"
                                      class="form-control">{{ old('delivery_notes', $newborn->delivery_notes) }}</textarea>

                        </div>

                    </div>

                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2 mb-5">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-circle"></i>
                        Update Newborn Record

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
                            Record Information
                        </h6>

                    </div>

                    <div class="card-body">

                        <small class="text-muted">

                            <strong>Created:</strong>
                            <br>
                            {{ $newborn->created_at->format('M d, Y h:i A') }}

                            <hr>

                            <strong>Last Updated:</strong>
                            <br>
                            {{ $newborn->updated_at->format('M d, Y h:i A') }}

                            <hr>

                            <strong>Recorded By:</strong>
                            <br>
                            {{ $newborn->recordedBy->name ?? 'N/A' }}

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection