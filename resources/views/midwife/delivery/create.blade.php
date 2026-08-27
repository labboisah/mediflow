@extends('layouts.app')

@section('title', 'Delivery Registration - ' . $labour->patient->name())

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-heart-pulse"></i>
                Delivery Registration
            </h1>

            <small class="text-muted">
                Register delivery details for {{ $labour->patient->name() }}
            </small>

        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('midwife.labour.show', $labour) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

    </div>

    <form action="{{ route('midwife.delivery.store', $labour) }}"
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
                                    {{ $labour->patient->hospital_number }}
                                </p>

                            </div>

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Patient Name
                                </small>

                                <p class="fw-bold">
                                    {{ $labour->patient->name() }}
                                </p>

                            </div>

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Age
                                </small>

                                <p class="fw-bold">
                                    {{ $labour->patient->age() }} years
                                </p>

                            </div>

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Labour Stage
                                </small>

                                <p>
                                    <span class="badge bg-primary">
                                        {{ str_replace('_', ' ', ucfirst($labour->stage)) }}
                                    </span>
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Delivery Details -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-calendar-heart"></i>
                            Delivery Details
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Delivery Date & Time
                                </label>

                                <input type="datetime-local"
                                       name="delivery_date_time"
                                       class="form-control @error('delivery_date_time') is-invalid @enderror"
                                       value="{{ old('delivery_date_time', now()->format('Y-m-d\TH:i')) }}"
                                       placeholder="Select delivery date and time">

                                @error('delivery_date_time')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Delivery Type
                                </label>

                                <select name="delivery_type"
                                        id="delivery_type"
                                        class="form-select @error('delivery_type') is-invalid @enderror">

                                    <option value="">
                                        Select
                                    </option>

                                    <option value="vaginal"
                                        {{ old('delivery_type') == 'vaginal' ? 'selected' : '' }}>
                                        Vaginal
                                    </option>

                                    <option value="assisted_vaginal"
                                        {{ old('delivery_type') == 'assisted_vaginal' ? 'selected' : '' }}>
                                        Assisted Vaginal
                                    </option>

                                    <option value="caesarean"
                                        {{ old('delivery_type') == 'caesarean' ? 'selected' : '' }}>
                                        Caesarean Section
                                    </option>

                                </select>

                                @error('delivery_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Reason for Delivery Type
                                </label>

                                <textarea name="reason_for_delivery_type"
                                          rows="2"
                                          class="form-control"
                                          placeholder="Optional: why this delivery type was chosen">{{ old('reason_for_delivery_type') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Assisted Vaginal Delivery -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-tools"></i>
                            Assisted Vaginal Delivery
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Assisted With
                                </label>

                                <select name="assisted_with"
                                        class="form-select">

                                    <option value="">
                                        Select
                                    </option>

                                    <option value="vacuum">
                                        Vacuum
                                    </option>

                                    <option value="forceps">
                                        Forceps
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Indication for Assistance
                                </label>

                                <textarea name="indication_for_assistance"
                                          rows="2"
                                          class="form-control"
                                          placeholder="Optional: reason for vacuum or forceps use">{{ old('indication_for_assistance') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Caesarean Section -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-hospital"></i>
                            Caesarean Section
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Caesarean Type
                                </label>

                                <select name="caesarean_type"
                                        class="form-select">

                                    <option value="">
                                        Select
                                    </option>

                                    <option value="elective">
                                        Elective
                                    </option>

                                    <option value="emergency">
                                        Emergency
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Indication for Caesarean
                                </label>

                                <textarea name="indication_for_caesarean"
                                          rows="2"
                                          class="form-control"
                                          placeholder="Optional: reason for caesarean delivery">{{ old('indication_for_caesarean') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Perineal Trauma -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-exclamation-triangle"></i>
                            Perineal Trauma
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Perineal Trauma
                                </label>

                                <select name="perineal_trauma"
                                        class="form-select">

                                    <option value="">
                                        Select
                                    </option>

                                    <option value="intact">
                                        Intact
                                    </option>

                                    <option value="1st degree">
                                        1st Degree
                                    </option>

                                    <option value="2nd degree">
                                        2nd Degree
                                    </option>

                                    <option value="3rd degree">
                                        3rd Degree
                                    </option>

                                    <option value="4th degree">
                                        4th Degree
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Episiotomy
                                </label>

                                <textarea name="episiotomy"
                                          rows="2"
                                          class="form-control"
                                          placeholder="Optional: details of episiotomy procedure">{{ old('episiotomy') }}</textarea>

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Perineal Repair
                                </label>

                                <textarea name="perineal_repair"
                                          rows="2"
                                          class="form-control"
                                          placeholder="Optional: describe repair details">{{ old('perineal_repair') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Third Stage -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-droplet"></i>
                            Third Stage Details
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Placenta Delivery Method
                                </label>

                                <select name="placenta_delivery_method"
                                        class="form-select">

                                    <option value="">
                                        Select
                                    </option>

                                    <option value="spontaneous">
                                        Spontaneous
                                    </option>

                                    <option value="manual removal">
                                        Manual Removal
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Placenta Delivered At
                                </label>

                                <input type="datetime-local"
                                       name="placenta_delivered_at"
                                       class="form-control"
                                       value="{{ old('placenta_delivered_at') }}">

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Placental Examination
                                </label>

                                <textarea name="placental_examination"
                                          rows="2"
                                          class="form-control">{{ old('placental_examination') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Maternal Condition -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-heart-pulse"></i>
                            Maternal Condition After Delivery
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Estimated Blood Loss
                                </label>

                                <input type="text"
                                       name="estimated_blood_loss"
                                       class="form-control"
                                       value="{{ old('estimated_blood_loss') }}"
                                       placeholder="e.g. 500ml">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Blood Pressure
                                </label>

                                <input type="text"
                                       name="blood_pressure"
                                       class="form-control"
                                       value="{{ old('blood_pressure') }}"
                                       placeholder="120/80">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Pulse Rate
                                </label>

                                <input type="text"
                                       name="pulse_rate"
                                       class="form-control"
                                       value="{{ old('pulse_rate') }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Uterine Tone
                                </label>

                                <input type="text"
                                       name="uterine_tone"
                                       class="form-control"
                                       value="{{ old('uterine_tone') }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Per Vaginal Bleeding
                                </label>

                                <input type="text"
                                       name="per_vaginal_bleeding"
                                       class="form-control"
                                       value="{{ old('per_vaginal_bleeding') }}">

                            </div>

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
                                    Blood Loss Assessment
                                </label>

                                <textarea name="blood_loss_assessment"
                                          rows="2"
                                          class="form-control">{{ old('blood_loss_assessment') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Complications -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-exclamation-octagon"></i>
                            Complications
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <label class="form-label">
                                Complications
                            </label>

                            <textarea name="complications"
                                      rows="3"
                                      class="form-control">{{ old('complications') }}</textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Management of Complications
                            </label>

                            <textarea name="management_of_complications"
                                      rows="3"
                                      class="form-control">{{ old('management_of_complications') }}</textarea>

                        </div>

                    </div>

                </div>

                <!-- Delivery Outcome -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-baby"></i>
                            Delivery Outcome
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Number of Babies
                                </label>

                                <input type="number"
                                       min="1"
                                       name="number_of_babies"
                                       class="form-control"
                                       value="{{ old('number_of_babies', 1) }}">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Delivery Status
                                </label>

                                <select name="delivery_status"
                                        class="form-select">

                                    <option value="successful">
                                        Successful
                                    </option>

                                    <option value="complicated">
                                        Complicated
                                    </option>

                                    <option value="maternal_death">
                                        Maternal Death
                                    </option>

                                    <option value="fetal_death">
                                        Fetal Death
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Delivery Summary
                                </label>

                                <textarea name="delivery_summary"
                                          rows="4"
                                          class="form-control">{{ old('delivery_summary') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2 mb-5">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-circle"></i>
                        Register Delivery

                    </button>

                    <a href="{{ route('midwife.labour.show', $labour) }}"
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
                            Delivery Notes
                        </h6>

                    </div>

                    <div class="card-body">

                        <small class="text-muted">

                            <strong>Normal Blood Loss</strong>
                            <br>
                            Vaginal: &lt; 500ml
                            <br>
                            CS: &lt; 1000ml

                            <hr>

                            <strong>Normal Pulse</strong>
                            <br>
                            60 - 100 bpm

                            <hr>

                            <strong>Placenta</strong>
                            <br>
                            Ensure completeness.

                            <hr>

                            <strong>PPH Warning</strong>
                            <br>
                            Heavy bleeding requires urgent intervention.

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection