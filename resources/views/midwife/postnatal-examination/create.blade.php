@extends('layouts.app')

@section('title', 'New Postnatal Examination')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">
                <i class="bi bi-heart-pulse"></i>
                Record Postnatal Examination
            </h1>

            <small class="text-muted">

                Mother:
                <strong>
                    {{ $delivery->patient->full_name }}
                </strong>

                |

                Delivery Type:
                <strong class="text-capitalize">
                    {{ str_replace('_', ' ', $delivery->delivery_type) }}
                </strong>

            </small>

        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('midwife.postnatal-examination.index', $delivery) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

    </div>

    <form action="{{ route('midwife.postnatal-examination.store', $delivery) }}"
          method="POST">

        @csrf

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
                                       value="{{ old('examination_date_time') }}"
                                       placeholder="Select postnatal exam date and time">

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
                                       value="{{ old('hours_post_delivery') }}"
                                       placeholder="e.g. 2">

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

                                    <option value="immediate_0-2h">
                                        Immediate (0–2 Hours)
                                    </option>

                                    <option value="6-12h">
                                        6–12 Hours
                                    </option>

                                    <option value="24h">
                                        24 Hours
                                    </option>

                                    <option value="48h">
                                        48 Hours
                                    </option>

                                    <option value="day4_6">
                                        Day 4–6
                                    </option>

                                    <option value="week1">
                                        Week 1
                                    </option>

                                    <option value="week2">
                                        Week 2
                                    </option>

                                    <option value="week6">
                                        Week 6
                                    </option>

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
                                       placeholder="120/80"
                                       value="{{ old('blood_pressure') }}">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Pulse Rate
                                </label>

                                <input type="number"
                                       name="pulse_rate"
                                       class="form-control"
                                       value="{{ old('pulse_rate') }}">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Temperature (°C)
                                </label>

                                <input type="number"
                                       step="0.1"
                                       name="temperature"
                                       class="form-control"
                                       value="{{ old('temperature') }}">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Respiration Rate
                                </label>

                                <input type="number"
                                       name="respiration_rate"
                                       class="form-control"
                                       value="{{ old('respiration_rate') }}">

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
                                          class="form-control">{{ old('general_appearance') }}</textarea>

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

                                    <option value="alert">
                                        Alert
                                    </option>

                                    <option value="drowsy">
                                        Drowsy
                                    </option>

                                    <option value="unconscious">
                                        Unconscious
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Skin Colour
                                </label>

                                <input type="text"
                                       name="skin_colour"
                                       class="form-control"
                                       value="{{ old('skin_colour') }}">

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
                                       value="{{ old('uterine_size') }}">

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

                                    <option value="firm">
                                        Firm
                                    </option>

                                    <option value="soft">
                                        Soft
                                    </option>

                                    <option value="boggy">
                                        Boggy
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Uterine Tenderness
                                </label>

                                <input type="text"
                                       name="uterine_tenderness"
                                       class="form-control"
                                       value="{{ old('uterine_tenderness') }}">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Fundal Height
                                </label>

                                <input type="text"
                                       name="fundal_height"
                                       class="form-control"
                                       value="{{ old('fundal_height') }}">

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

                                    <option value="rubra">
                                        Rubra
                                    </option>

                                    <option value="serosa">
                                        Serosa
                                    </option>

                                    <option value="alba">
                                        Alba
                                    </option>

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

                                    <option value="minimal">
                                        Minimal
                                    </option>

                                    <option value="moderate">
                                        Moderate
                                    </option>

                                    <option value="heavy">
                                        Heavy
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Lochia Odour
                                </label>

                                <input type="text"
                                       name="lochia_odour"
                                       class="form-control"
                                       value="{{ old('lochia_odour') }}">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Clots Present
                                </label>

                                <select name="clot_presence"
                                        class="form-select">

                                    <option value="0">
                                        No
                                    </option>

                                    <option value="1">
                                        Yes
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Perineal Assessment -->
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-shield-check"></i>
                            Perineal Assessment
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    Perineal Assessment
                                </label>

                                <textarea name="perineal_assessment"
                                          rows="3"
                                          class="form-control">{{ old('perineal_assessment') }}</textarea>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Perineal Wound Status
                                </label>

                                <select name="perineal_wound_status"
                                        class="form-select">

                                    <option value="">
                                        Choose...
                                    </option>

                                    <option value="intact">
                                        Intact
                                    </option>

                                    <option value="sutured">
                                        Sutured
                                    </option>

                                    <option value="healing">
                                        Healing
                                    </option>

                                    <option value="healed">
                                        Healed
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Perineal Pain
                                </label>

                                <input type="text"
                                       name="perineal_pain"
                                       class="form-control"
                                       value="{{ old('perineal_pain') }}">

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Vaginal Examination
                                </label>

                                <textarea name="vaginal_examination"
                                          rows="3"
                                          class="form-control">{{ old('vaginal_examination') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Breastfeeding -->
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-heart"></i>
                            Breastfeeding Assessment
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    Breast Examination
                                </label>

                                <textarea name="breast_examination"
                                          rows="3"
                                          class="form-control">{{ old('breast_examination') }}</textarea>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Nipple Condition
                                </label>

                                <input type="text"
                                       name="nipple_condition"
                                       class="form-control"
                                       value="{{ old('nipple_condition') }}">

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Breast Engorgement
                                </label>

                                <input type="text"
                                       name="breast_engorgement"
                                       class="form-control"
                                       value="{{ old('breast_engorgement') }}">

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Milk Expression
                                </label>

                                <input type="text"
                                       name="breast_milk_expression"
                                       class="form-control"
                                       value="{{ old('breast_milk_expression') }}">

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Breastfeeding Successful
                                </label>

                                <select name="breastfeeding_successful"
                                        class="form-select">

                                    <option value="0">
                                        No
                                    </option>

                                    <option value="1">
                                        Yes
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-12">

                                <label class="form-label">
                                    Breastfeeding Problems
                                </label>

                                <textarea name="breastfeeding_problems"
                                          rows="3"
                                          class="form-control">{{ old('breastfeeding_problems') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Additional Assessments -->
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-clipboard2-check"></i>
                            Additional Assessments
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    Abdominal Examination
                                </label>

                                <textarea name="abdominal_examination"
                                          rows="3"
                                          class="form-control">{{ old('abdominal_examination') }}</textarea>

                            </div>

                            @if($delivery->delivery_type == 'caesarean')

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Wound Assessment
                                    </label>

                                    <textarea name="wound_assessment"
                                              rows="3"
                                              class="form-control">{{ old('wound_assessment') }}</textarea>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Drain Status
                                    </label>

                                    <textarea name="drain_status"
                                              rows="3"
                                              class="form-control">{{ old('drain_status') }}</textarea>

                                </div>

                            @endif

                            <div class="col-md-6">

                                <label class="form-label">
                                    Lower Limbs Examination
                                </label>

                                <textarea name="lower_limbs_examination"
                                          rows="3"
                                          class="form-control">{{ old('lower_limbs_examination') }}</textarea>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Oedema Assessment
                                </label>

                                <input type="text"
                                       name="oedema_assessment"
                                       class="form-control"
                                       value="{{ old('oedema_assessment') }}">

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Calf Tenderness
                                </label>

                                <textarea name="calf_tenderness"
                                          rows="2"
                                          class="form-control">{{ old('calf_tenderness') }}</textarea>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Signs of DVT
                                </label>

                                <textarea name="signs_of_dvt"
                                          rows="2"
                                          class="form-control">{{ old('signs_of_dvt') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Mental Health -->
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-light">

                        <h6 class="mb-0">
                            <i class="bi bi-emoji-smile"></i>
                            Mental Health & Bonding
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">
                                    Maternal Mood
                                </label>

                                <input type="text"
                                       name="maternal_mood"
                                       class="form-control"
                                       value="{{ old('maternal_mood') }}">

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Emotional State
                                </label>

                                <textarea name="emotional_state"
                                          rows="3"
                                          class="form-control">{{ old('emotional_state') }}</textarea>

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Signs of Depression
                                </label>

                                <select name="signs_of_depression"
                                        class="form-select">

                                    <option value="0">
                                        No
                                    </option>

                                    <option value="1">
                                        Yes
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-12">

                                <label class="form-label">
                                    Bonding with Baby
                                </label>

                                <textarea name="bonding_with_baby"
                                          rows="3"
                                          class="form-control">{{ old('bonding_with_baby') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Summary -->
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
                                      class="form-control">{{ old('clinical_summary') }}</textarea>

                        </div>

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">
                                    Recovery Status
                                </label>

                                <select name="recovery_status"
                                        class="form-select">

                                    <option value="">
                                        Choose...
                                    </option>

                                    <option value="normal">
                                        Normal Recovery
                                    </option>

                                    <option value="complicated">
                                        Complicated Recovery
                                    </option>

                                    <option value="needs_referral">
                                        Needs Referral
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Next Follow-up Date
                                </label>

                                <input type="date"
                                       name="next_follow_up_date"
                                       class="form-control"
                                       value="{{ old('next_follow_up_date') }}">

                            </div>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Contraception Discussed
                                </label>

                                <select name="contraception_discussed"
                                        class="form-select">

                                    <option value="0">
                                        No
                                    </option>

                                    <option value="1">
                                        Yes
                                    </option>

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
                                    Medications Prescribed
                                </label>

                                <textarea name="medications_prescribed"
                                          rows="3"
                                          class="form-control">{{ old('medications_prescribed') }}</textarea>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Follow-up Plan
                                </label>

                                <textarea name="follow_up_plan"
                                          rows="3"
                                          class="form-control">{{ old('follow_up_plan') }}</textarea>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Contraception Method Chosen
                                </label>

                                <input type="text"
                                       name="contraception_method_chosen"
                                       class="form-control"
                                       value="{{ old('contraception_method_chosen') }}">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2 mb-5">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-save"></i>
                        Save Postnatal Examination

                    </button>

                    <a href="{{ route('midwife.postnatal-examination.index', $delivery) }}"
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
                            Clinical Guide
                        </h6>

                    </div>

                    <div class="card-body">

                        <small class="text-muted">

                            <strong>Normal Pulse</strong>
                            <br>
                            60–100 bpm

                            <hr>

                            <strong>Normal Temperature</strong>
                            <br>
                            36.5–37.5 °C

                            <hr>

                            <strong>Watch For:</strong>

                            <ul class="mt-2">

                                <li>
                                    Heavy bleeding
                                </li>

                                <li>
                                    Fever
                                </li>

                                <li>
                                    Offensive lochia
                                </li>

                                <li>
                                    Severe pain
                                </li>

                                <li>
                                    Depression signs
                                </li>

                            </ul>

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection