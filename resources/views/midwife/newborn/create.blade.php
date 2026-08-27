@extends('layouts.app')

@section('title', 'Newborn Registration - ' . $delivery->patient->full_name)

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-baby"></i>
                Newborn Registration
            </h1>

            <small class="text-muted">
                Register newborn details for {{ $delivery->patient->name() }}
            </small>

        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('midwife.delivery.show', $delivery) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

    </div>

    <form action="{{ route('midwife.newborn.store', $delivery) }}"
          method="POST">

        @csrf

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
                                    {{ $delivery->patient->name() }}
                                </p>

                            </div>

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Delivery Type
                                </small>

                                <p>
                                    <span class="badge bg-primary">
                                        {{ str_replace('_', ' ', ucfirst($delivery->delivery_type)) }}
                                    </span>
                                </p>

                            </div>

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Delivery Date
                                </small>

                                <p class="fw-bold">
                                    {{ optional($delivery->delivery_date_time)->format('M d, Y h:i A') }}
                                </p>

                            </div>

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Number of Babies
                                </small>

                                <p class="fw-bold">
                                    {{ $delivery->number_of_babies }}
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
                                        {{ old('sex') == 'male' ? 'selected' : '' }}>
                                        Male
                                    </option>

                                    <option value="female"
                                        {{ old('sex') == 'female' ? 'selected' : '' }}>
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
                                       value="{{ old('birth_order', 1) }}"
                                       placeholder="e.g. 1">

                            </div>

                        

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Birth Date & Time
                                </label>

                                <input type="datetime-local"
                                       name="birth_date_time"
                                       class="form-control"
                                       value="{{ old('birth_date_time', optional($delivery->delivery_date_time)->format('Y-m-d\TH:i')) }}"
                                       placeholder="Select birth date and time">

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

                                    <option value="cephalic">
                                        Cephalic
                                    </option>

                                    <option value="breech">
                                        Breech
                                    </option>

                                    <option value="transverse">
                                        Transverse
                                    </option>

                                    <option value="face">
                                        Face
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Anthropometric Measurements -->
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
                                    Birth Weight (grams)
                                </label>

                                <input type="text"
                                       name="birth_weight"
                                       class="form-control"
                                       value="{{ old('birth_weight') }}"
                                       placeholder="e.g. 3200">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Birth Length (cm)
                                </label>

                                <input type="text"
                                       name="birth_length"
                                       class="form-control"
                                       value="{{ old('birth_length') }}"
                                       placeholder="e.g. 50">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Head Circumference (cm)
                                </label>

                                <input type="text"
                                       name="head_circumference"
                                       class="form-control"
                                       value="{{ old('head_circumference') }}"
                                       placeholder="e.g. 35">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- APGAR Scores -->
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
                                       value="{{ old('apgar_score_1_minute') }}">

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
                                       value="{{ old('apgar_score_5_minutes') }}">

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
                                       value="{{ old('apgar_score_10_minutes') }}">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Newborn Condition -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-heart"></i>
                            Newborn Condition
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    General Condition
                                </label>

                                <input type="text"
                                       name="general_condition"
                                       class="form-control"
                                       value="{{ old('general_condition') }}">

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Physical Examination
                                </label>

                                <textarea name="physical_examination"
                                          rows="3"
                                          class="form-control">{{ old('physical_examination') }}</textarea>

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Birth Defects Noted
                                </label>

                                <textarea name="birth_defects_noted"
                                          rows="2"
                                          class="form-control">{{ old('birth_defects_noted') }}</textarea>

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Meconium Aspiration
                                </label>

                                <textarea name="meconium_aspiration"
                                          rows="2"
                                          class="form-control">{{ old('meconium_aspiration') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Feeding and Care -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-cup-hot"></i>
                            Feeding and Care
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="form-check mb-3">

                            <input type="checkbox"
                                   name="breastfeeding_initiated"
                                   value="1"
                                   class="form-check-input"
                                   {{ old('breastfeeding_initiated') ? 'checked' : '' }}>

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
                                   value="{{ old('first_breastfeed_time') }}">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Feeding Problems
                            </label>

                            <textarea name="feeding_problems"
                                      rows="2"
                                      class="form-control">{{ old('feeding_problems') }}</textarea>

                        </div>

                    </div>

                </div>

                <!-- Early Newborn Care -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-shield-check"></i>
                            Early Newborn Care
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4">

                                <div class="form-check mb-3">

                                    <input type="checkbox"
                                           name="vitamin_k_given"
                                           value="1"
                                           class="form-check-input"
                                           {{ old('vitamin_k_given') ? 'checked' : '' }}>

                                    <label class="form-check-label">
                                        Vitamin K Given
                                    </label>

                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="form-check mb-3">

                                    <input type="checkbox"
                                           name="eye_prophylaxis_given"
                                           value="1"
                                           class="form-check-input"
                                           {{ old('eye_prophylaxis_given') ? 'checked' : '' }}>

                                    <label class="form-check-label">
                                        Eye Prophylaxis Given
                                    </label>

                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="form-check mb-3">

                                    <input type="checkbox"
                                           name="immunizations_given"
                                           value="1"
                                           class="form-check-input"
                                           {{ old('immunizations_given') ? 'checked' : '' }}>

                                    <label class="form-check-label">
                                        Immunizations Given
                                    </label>

                                </div>

                            </div>

                            <div class="col-md-12 mt-3">

                                <label class="form-label">
                                    Immunization Details
                                </label>

                                <textarea name="immunizations_details"
                                          rows="2"
                                          class="form-control">{{ old('immunizations_details') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Screening and Interventions -->
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
                                   {{ old('screening_test_done') ? 'checked' : '' }}>

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
                                      class="form-control">{{ old('screening_test_results') }}</textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Special Care Needed
                            </label>

                            <textarea name="special_care_needed"
                                      rows="2"
                                      class="form-control">{{ old('special_care_needed') }}</textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Referred To
                            </label>

                            <textarea name="referred_to"
                                      rows="2"
                                      class="form-control">{{ old('referred_to') }}</textarea>

                        </div>

                    </div>

                </div>

                <!-- Outcome -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-activity"></i>
                            Status & Observations
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Status
                                </label>

                                <select name="status"
                                        class="form-select">

                                    <option value="alive">
                                        Alive
                                    </option>

                                    <option value="stillborn">
                                        Stillborn
                                    </option>

                                    <option value="early_neonatal_death">
                                        Early Neonatal Death
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Neonatal Observations
                                </label>

                                <textarea name="neonatal_observations"
                                          rows="4"
                                          class="form-control">{{ old('neonatal_observations') }}</textarea>

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Delivery Notes
                                </label>

                                <textarea name="delivery_notes"
                                          rows="3"
                                          class="form-control">{{ old('delivery_notes') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2 mb-5">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-circle"></i>
                        Register Newborn

                    </button>

                    <a href="{{ route('midwife.delivery.show', $delivery) }}"
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
                            <i class="bi bi-lightbulb"></i>
                            Neonatal Guide
                        </h6>

                    </div>

                    <div class="card-body">

                        <small class="text-muted">

                            <strong>Normal Birth Weight</strong>
                            <br>
                            2500g - 4000g

                            <hr>

                            <strong>Normal APGAR</strong>
                            <br>
                            7 - 10

                            <hr>

                            <strong>Early Breastfeeding</strong>
                            <br>
                            Within first hour of life.

                            <hr>

                            <strong>Vitamin K</strong>
                            <br>
                            Recommended at birth.

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection