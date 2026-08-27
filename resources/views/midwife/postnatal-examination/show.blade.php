@extends('layouts.app')

@section('title', 'Postnatal Examination Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-clipboard-check"></i> Postnatal Examination Details</h1>
        <div>
            <a href="{{ route('midwife.postnatal-examination.edit', $postnatalExamination) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('midwife.postnatal-examination.index', $postnatalExamination->delivery) }}" class="btn btn-outline-secondary">Back to List</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Mother:</strong> {{ $postnatalExamination->delivery->patient->full_name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Patient ID:</strong> {{ $postnatalExamination->delivery->patient->patient_id }}
                        </div>
                        <div class="col-md-6">
                            <strong>Delivery Date/Time:</strong> {{ optional($postnatalExamination->delivery->delivery_date_time)->format('M d, Y H:i') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Delivery Type:</strong> {{ $postnatalExamination->delivery->delivery_type }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Examination Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Examination Date/Time:</strong><br>
                            {{ $postnatalExamination->examination_date_time?->format('M d, Y H:i') }}
                        </div>
                        <div class="col-md-4">
                            <strong>Hours Post Delivery:</strong><br>
                            {{ $postnatalExamination->hours_post_delivery }} hours
                        </div>
                        <div class="col-md-4">
                            <strong>Examination Time:</strong><br>
                            @if($postnatalExamination->examination_time === 'immediate')
                                <span class="badge bg-primary">Immediate (within 1 hour)</span>
                            @elseif($postnatalExamination->examination_time === 'early')
                                <span class="badge bg-info">Early (1-24 hours)</span>
                            @elseif($postnatalExamination->examination_time === 'late')
                                <span class="badge bg-secondary">Late (after 24 hours)</span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <strong>Recovery Status:</strong><br>
                            @if($postnatalExamination->recovery_status === 'normal')
                                <span class="badge bg-success">Normal Recovery</span>
                            @elseif($postnatalExamination->recovery_status === 'needs_attention')
                                <span class="badge bg-warning">Needs Attention</span>
                            @else
                                <span class="badge bg-danger">Needs Referral</span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <strong>Recorded By:</strong><br>
                            {{ $postnatalExamination->recordedBy->name ?? 'N/A' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Recorded At:</strong><br>
                            {{ $postnatalExamination->created_at?->format('M d, Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Vital Signs</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Blood Pressure:</strong><br>
                            {{ $postnatalExamination->blood_pressure ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Pulse Rate:</strong><br>
                            {{ $postnatalExamination->pulse_rate ? $postnatalExamination->pulse_rate . ' bpm' : 'Not recorded' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Temperature:</strong><br>
                            {{ $postnatalExamination->temperature ? $postnatalExamination->temperature . ' °C' : 'Not recorded' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Respiration Rate:</strong><br>
                            {{ $postnatalExamination->respiration_rate ?: 'Not recorded' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">General Assessment</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>General Appearance:</strong><br>
                            {{ $postnatalExamination->general_appearance ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Consciousness Level:</strong><br>
                            {{ $postnatalExamination->consciousness_level ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Skin Colour:</strong><br>
                            {{ $postnatalExamination->skin_colour ?: 'Not recorded' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Uterine Assessment</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Uterine Size:</strong><br>
                            {{ $postnatalExamination->uterine_size ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Consistency:</strong><br>
                            {{ $postnatalExamination->uterine_consistency ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Tenderness:</strong><br>
                            {{ $postnatalExamination->uterine_tenderness ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Fundal Height:</strong><br>
                            {{ $postnatalExamination->fundal_height ?: 'Not recorded' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Lochia Assessment</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Lochia Type:</strong><br>
                            @if($postnatalExamination->lochia_type === 'rubra')
                                <span class="badge bg-danger">Rubra (red)</span>
                            @elseif($postnatalExamination->lochia_type === 'serosa')
                                <span class="badge bg-warning">Serosa (pinkish)</span>
                            @elseif($postnatalExamination->lochia_type === 'alba')
                                <span class="badge bg-light text-dark">Alba (white/yellow)</span>
                            @else
                                Not recorded
                            @endif
                        </div>
                        <div class="col-md-3">
                            <strong>Amount:</strong><br>
                            @if($postnatalExamination->lochia_amount === 'scanty')
                                <span class="badge bg-light text-dark">Scanty</span>
                            @elseif($postnatalExamination->lochia_amount === 'moderate')
                                <span class="badge bg-info">Moderate</span>
                            @elseif($postnatalExamination->lochia_amount === 'heavy')
                                <span class="badge bg-danger">Heavy</span>
                            @else
                                Not recorded
                            @endif
                        </div>
                        <div class="col-md-3">
                            <strong>Odour:</strong><br>
                            {{ $postnatalExamination->lochia_odour ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Clots Present:</strong><br>
                            {{ $postnatalExamination->clot_presence ? 'Yes' : 'No' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Perineal Assessment</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Perineal Assessment:</strong><br>
                            {{ $postnatalExamination->perineal_assessment ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Wound Status:</strong><br>
                            {{ $postnatalExamination->perineal_wound_status ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Perineal Pain:</strong><br>
                            {{ $postnatalExamination->perineal_pain ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Vaginal Examination:</strong><br>
                            {{ $postnatalExamination->vaginal_examination ?: 'Not recorded' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Breastfeeding Assessment</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Breast Examination:</strong><br>
                            {{ $postnatalExamination->breast_examination ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Nipple Condition:</strong><br>
                            {{ $postnatalExamination->nipple_condition ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Breast Engorgement:</strong><br>
                            {{ $postnatalExamination->breast_engorgement ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Milk Expression:</strong><br>
                            {{ $postnatalExamination->breast_milk_expression ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Breastfeeding Successful:</strong><br>
                            {{ $postnatalExamination->breastfeeding_successful ? 'Yes' : 'No' }}
                        </div>
                        <div class="col-md-12">
                            <strong>Breastfeeding Problems:</strong><br>
                            {{ $postnatalExamination->breastfeeding_problems ?: 'None recorded' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Additional Assessments</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Abdominal Examination:</strong><br>
                            {{ $postnatalExamination->abdominal_examination ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Wound Assessment:</strong><br>
                            {{ $postnatalExamination->wound_assessment ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Lower Limbs Examination:</strong><br>
                            {{ $postnatalExamination->lower_limbs_examination ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Oedema Assessment:</strong><br>
                            {{ $postnatalExamination->oedema_assessment ?: 'Not recorded' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Mental Health & Bonding</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Maternal Mood:</strong><br>
                            {{ $postnatalExamination->maternal_mood ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Emotional State:</strong><br>
                            {{ $postnatalExamination->emotional_state ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Signs of Depression:</strong><br>
                            {{ $postnatalExamination->signs_of_depression ? 'Present' : 'Not present' }}
                        </div>
                        <div class="col-md-12">
                            <strong>Bonding with Baby:</strong><br>
                            {{ $postnatalExamination->bonding_with_baby ?: 'Not recorded' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Complications & Counseling</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Complications Identified:</strong><br>
                            {{ $postnatalExamination->complications_identified ?: 'None recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Signs of Infection:</strong><br>
                            {{ $postnatalExamination->infection_signs ?: 'None recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Bleeding Assessment:</strong><br>
                            {{ $postnatalExamination->bleeding_assessment ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Hypertension Assessment:</strong><br>
                            {{ $postnatalExamination->hypertension_assessment ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Sleep Patterns:</strong><br>
                            {{ $postnatalExamination->sleep_patterns ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Pain Level:</strong><br>
                            {{ $postnatalExamination->pain_level ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Activity Tolerance:</strong><br>
                            {{ $postnatalExamination->activity_tolerance ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Perineal Care Ability:</strong><br>
                            {{ $postnatalExamination->perineal_care_ability ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Counseling Topics:</strong><br>
                            {{ $postnatalExamination->counseling_topics ?: 'None recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Contraception Discussed:</strong><br>
                            {{ $postnatalExamination->contraception_discussed ? 'Yes' : 'No' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Contraception Method Chosen:</strong><br>
                            {{ $postnatalExamination->contraception_method_chosen ?: 'None' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Hygiene Taught:</strong><br>
                            {{ $postnatalExamination->hygiene_taught ? 'Yes' : 'No' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Danger Signs Explained:</strong><br>
                            {{ $postnatalExamination->danger_signs_explained ? 'Yes' : 'No' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Summary & Plan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Clinical Summary:</strong><br>
                            {{ $postnatalExamination->clinical_summary ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Management Plan:</strong><br>
                            {{ $postnatalExamination->management_plan ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Medications Prescribed:</strong><br>
                            {{ $postnatalExamination->medications_prescribed ?: 'None' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Follow-up Plan:</strong><br>
                            {{ $postnatalExamination->follow_up_plan ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Next Follow-up Date:</strong><br>
                            {{ $postnatalExamination->next_follow_up_date?->format('M d, Y') ?: 'Not scheduled' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('midwife.postnatal-examination.edit', $postnatalExamination) }}" class="btn btn-warning">Edit Examination</a>
                        <a href="{{ route('midwife.delivery.show', $postnatalExamination->delivery) }}" class="btn btn-info">View Delivery</a>
                        <a href="{{ route('midwife.newborn.index', $postnatalExamination->delivery) }}" class="btn btn-success">View Newborns</a>
                        <a href="{{ route('midwife.postnatal-examination.index', $postnatalExamination->delivery) }}" class="btn btn-outline-secondary">Back to Examinations</a>
                    </div>
                </div>
            </div>

            @if($postnatalExamination->recovery_status === 'needs_attention' || $postnatalExamination->recovery_status === 'needs_referral')
            <div class="card mt-3">
                <div class="card-header bg-warning">
                    <h5 class="mb-0 text-white">⚠️ Attention Required</h5>
                </div>
                <div class="card-body">
                    <p>This patient requires special attention or referral.</p>
                    <strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $postnatalExamination->recovery_status)) }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection