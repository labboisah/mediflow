@extends('layouts.app')

@section('title', 'Edit Delivery Record - ' . $delivery->patient->full_name)

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-pencil-square"></i>
                Edit Delivery Record
            </h1>

            <small class="text-muted">
                Update delivery information for {{ $delivery->patient->name() }}
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

    <form action="{{ route('midwife.delivery.update', $delivery) }}"
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

                                <small class="text-muted">
                                    Hospital Number
                                </small>

                                <p class="fw-bold">
                                    {{ $delivery->patient->hospital_number }}
                                </p>

                            </div>

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Patient Name
                                </small>

                                <p class="fw-bold">
                                    {{ $delivery->patient->name() }}
                                </p>

                            </div>

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Age
                                </small>

                                <p class="fw-bold">
                                    {{ $delivery->patient->age() }} years
                                </p>

                            </div>

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Labour Status
                                </small>

                                <p>

                                    @if($delivery->labour)
                                        <span class="badge bg-primary">
                                            {{ ucfirst($delivery->labour->status) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Direct visit record</span>
                                    @endif

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
                                       value="{{ old('delivery_date_time', optional($delivery->delivery_date_time)->format('Y-m-d\TH:i')) }}">

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
                                        class="form-select @error('delivery_type') is-invalid @enderror">

                                    <option value="">
                                        Select
                                    </option>

                                    <option value="vaginal"
                                        {{ old('delivery_type', $delivery->delivery_type) == 'vaginal' ? 'selected' : '' }}>
                                        Vaginal
                                    </option>

                                    <option value="assisted_vaginal"
                                        {{ old('delivery_type', $delivery->delivery_type) == 'assisted_vaginal' ? 'selected' : '' }}>
                                        Assisted Vaginal
                                    </option>

                                    <option value="caesarean"
                                        {{ old('delivery_type', $delivery->delivery_type) == 'caesarean' ? 'selected' : '' }}>
                                        Caesarean Section
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Reason for Delivery Type
                                </label>

                                <textarea name="reason_for_delivery_type"
                                          rows="2"
                                          class="form-control">{{ old('reason_for_delivery_type', $delivery->reason_for_delivery_type) }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Assisted Vaginal -->
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

                                    <option value="vacuum"
                                        {{ old('assisted_with', $delivery->assisted_with) == 'vacuum' ? 'selected' : '' }}>
                                        Vacuum
                                    </option>

                                    <option value="forceps"
                                        {{ old('assisted_with', $delivery->assisted_with) == 'forceps' ? 'selected' : '' }}>
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
                                          class="form-control">{{ old('indication_for_assistance', $delivery->indication_for_assistance) }}</textarea>

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

                                    <option value="elective"
                                        {{ old('caesarean_type', $delivery->caesarean_type) == 'elective' ? 'selected' : '' }}>
                                        Elective
                                    </option>

                                    <option value="emergency"
                                        {{ old('caesarean_type', $delivery->caesarean_type) == 'emergency' ? 'selected' : '' }}>
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
                                          class="form-control">{{ old('indication_for_caesarean', $delivery->indication_for_caesarean) }}</textarea>

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

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Perineal Trauma
                                </label>

                                <select name="perineal_trauma"
                                        class="form-select">

                                    <option value="">
                                        Select
                                    </option>

                                    <option value="intact"
                                        {{ old('perineal_trauma', $delivery->perineal_trauma) == 'intact' ? 'selected' : '' }}>
                                        Intact
                                    </option>

                                    <option value="1st degree"
                                        {{ old('perineal_trauma', $delivery->perineal_trauma) == '1st degree' ? 'selected' : '' }}>
                                        1st Degree
                                    </option>

                                    <option value="2nd degree"
                                        {{ old('perineal_trauma', $delivery->perineal_trauma) == '2nd degree' ? 'selected' : '' }}>
                                        2nd Degree
                                    </option>

                                    <option value="3rd degree"
                                        {{ old('perineal_trauma', $delivery->perineal_trauma) == '3rd degree' ? 'selected' : '' }}>
                                        3rd Degree
                                    </option>

                                    <option value="4th degree"
                                        {{ old('perineal_trauma', $delivery->perineal_trauma) == '4th degree' ? 'selected' : '' }}>
                                        4th Degree
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Episiotomy
                                </label>

                                <textarea name="episiotomy"
                                          rows="2"
                                          class="form-control">{{ old('episiotomy', $delivery->episiotomy) }}</textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Perineal Repair
                                </label>

                                <textarea name="perineal_repair"
                                          rows="2"
                                          class="form-control">{{ old('perineal_repair', $delivery->perineal_repair) }}</textarea>

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

                                    <option value="spontaneous"
                                        {{ old('placenta_delivery_method', $delivery->placenta_delivery_method) == 'spontaneous' ? 'selected' : '' }}>
                                        Spontaneous
                                    </option>

                                    <option value="manual removal"
                                        {{ old('placenta_delivery_method', $delivery->placenta_delivery_method) == 'manual removal' ? 'selected' : '' }}>
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
                                       value="{{ old('placenta_delivered_at', optional($delivery->placenta_delivered_at)->format('Y-m-d\TH:i')) }}">

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Placental Examination
                                </label>

                                <textarea name="placental_examination"
                                          rows="2"
                                          class="form-control">{{ old('placental_examination', $delivery->placental_examination) }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Maternal Condition -->
                <div class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-heart-pulse"></i>
                            Maternal Condition
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
                                       value="{{ old('estimated_blood_loss', $delivery->estimated_blood_loss) }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Blood Pressure
                                </label>

                                <input type="text"
                                       name="blood_pressure"
                                       class="form-control"
                                       value="{{ old('blood_pressure', $delivery->blood_pressure) }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Pulse Rate
                                </label>

                                <input type="text"
                                       name="pulse_rate"
                                       class="form-control"
                                       value="{{ old('pulse_rate', $delivery->pulse_rate) }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Uterine Tone
                                </label>

                                <input type="text"
                                       name="uterine_tone"
                                       class="form-control"
                                       value="{{ old('uterine_tone', $delivery->uterine_tone) }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Per Vaginal Bleeding
                                </label>

                                <input type="text"
                                       name="per_vaginal_bleeding"
                                       class="form-control"
                                       value="{{ old('per_vaginal_bleeding', $delivery->per_vaginal_bleeding) }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    General Condition
                                </label>

                                <input type="text"
                                       name="general_condition"
                                       class="form-control"
                                       value="{{ old('general_condition', $delivery->general_condition) }}">

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Blood Loss Assessment
                                </label>

                                <textarea name="blood_loss_assessment"
                                          rows="2"
                                          class="form-control">{{ old('blood_loss_assessment', $delivery->blood_loss_assessment) }}</textarea>

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
                                      class="form-control">{{ old('complications', $delivery->complications) }}</textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Management of Complications
                            </label>

                            <textarea name="management_of_complications"
                                      rows="3"
                                      class="form-control">{{ old('management_of_complications', $delivery->management_of_complications) }}</textarea>

                        </div>

                    </div>

                </div>

                <!-- Outcome -->
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
                                       value="{{ old('number_of_babies', $delivery->number_of_babies) }}">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Delivery Status
                                </label>

                                <select name="delivery_status"
                                        class="form-select">

                                    <option value="successful"
                                        {{ old('delivery_status', $delivery->delivery_status) == 'successful' ? 'selected' : '' }}>
                                        Successful
                                    </option>

                                    <option value="complicated"
                                        {{ old('delivery_status', $delivery->delivery_status) == 'complicated' ? 'selected' : '' }}>
                                        Complicated
                                    </option>

                                    <option value="maternal_death"
                                        {{ old('delivery_status', $delivery->delivery_status) == 'maternal_death' ? 'selected' : '' }}>
                                        Maternal Death
                                    </option>

                                    <option value="fetal_death"
                                        {{ old('delivery_status', $delivery->delivery_status) == 'fetal_death' ? 'selected' : '' }}>
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
                                          class="form-control">{{ old('delivery_summary', $delivery->delivery_summary) }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2 mb-5">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-circle"></i>
                        Update Delivery Record

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
                            <i class="bi bi-info-circle"></i>
                            Record Information
                        </h6>

                    </div>

                    <div class="card-body">

                        <small class="text-muted">

                            <strong>Created:</strong>
                            <br>
                            {{ $delivery->created_at->format('M d, Y h:i A') }}

                            <hr>

                            <strong>Last Updated:</strong>
                            <br>
                            {{ $delivery->updated_at->format('M d, Y h:i A') }}

                            <hr>

                            <strong>Record ID:</strong>
                            <br>
                            #{{ $delivery->id }}

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection
