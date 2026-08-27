<div class="{{ $compact ? '' : 'container-fluid py-3' }}">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="{{ $compact ? 'h5' : 'h4' }} mb-1">{{ $pageTitle }}</h1>
            <p class="text-muted mb-0">{{ $pageDescription }}</p>
        </div>
        @if($patient && ! $compact)
            <a href="{{ route('midwife.patient.show', $patient) }}" class="btn btn-outline-secondary">
                <i class="bi bi-person-lines-fill"></i> Patient Profile
            </a>
        @endif
    </div>

    @error('patient')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @error('save')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="row g-3">
        <div class="{{ $compact ? 'col-lg-3' : 'col-lg-4' }}">
            @unless($compact && $patient)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white">
                        <h2 class="h6 mb-0">Find Patient</h2>
                    </div>
                    <div class="card-body">
                        <input type="search" class="form-control" wire:model.live.debounce.400ms="search" placeholder="Hospital number, name, or phone">

                        <div class="list-group mt-3">
                            @forelse($patients as $searchPatient)
                                <button type="button" class="list-group-item list-group-item-action {{ $patient?->id === $searchPatient->id ? 'active' : '' }}" wire:click="selectPatient({{ $searchPatient->id }})">
                                    <div class="fw-semibold">{{ $searchPatient->name() }}</div>
                                    <small>{{ $searchPatient->hospital_number }} {{ $searchPatient->demographic?->phone_number ? '- ' . $searchPatient->demographic->phone_number : '' }}</small>
                                </button>
                            @empty
                                <div class="text-muted small mt-2">
                                    Type at least two characters to search.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endunless

            @if($patient)
                <div class="card border-0 {{ $compact ? 'border shadow-none' : 'shadow-sm' }}">
                    <div class="card-header bg-white">
                        <h2 class="h6 mb-0">Visit Summary</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="fw-semibold">{{ $patient->name() }}</div>
                            <small class="text-muted">{{ $patient->hospital_number }}</small>
                        </div>
                        @if(strtolower((string) $patient->demographic?->gender) !== 'female')
                            <div class="alert alert-danger small mb-3">
                                Maternity activity is only available for female patients.
                            </div>
                        @endif
                        @if(! $visit)
                            <div class="alert alert-warning small mb-3">
                                No active visit found. A maternity visit will be created automatically when you save.
                            </div>
                        @endif
                        <div class="mb-3">
                            <span class="badge bg-primary">{{ $visit?->visit_type ?? 'Maternity Care' }}</span>
                            <span class="badge bg-light text-dark">{{ $visit?->visit_date?->format('M d, Y h:i A') }}</span>
                        </div>
                        <div class="row g-2">
                            @foreach([
                                'antenatal' => 'ANC',
                                'labour' => 'Labour',
                                'delivery' => 'Delivery',
                                'newborn' => 'Newborn',
                                'postnatal' => 'Postnatal',
                                'child_follow_up' => 'Follow-up',
                            ] as $key => $label)
                                <div class="col-6">
                                    <div class="border rounded p-2">
                                        <small class="text-muted d-block">{{ $label }}</small>
                                        <strong>{{ $activityCounts[$key] ?? 0 }}</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="{{ $compact ? 'col-lg-9' : 'col-lg-8' }}">
            <div class="card border-0 {{ $compact ? 'border shadow-none' : 'shadow-sm' }}">
                @unless($fixedActivity)
                    <div class="card-header bg-white">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach([
                                'antenatal' => ['ANC', 'bi-heart-pulse'],
                                'labour' => ['Labour', 'bi-activity'],
                                'delivery' => ['Delivery', 'bi-hospital'],
                                'newborn' => ['Newborn', 'bi-bandaid'],
                                'postnatal' => ['Postnatal', 'bi-journal-medical'],
                                'child_follow_up' => ['Child Follow-up', 'bi-arrow-repeat'],
                            ] as $key => [$label, $icon])
                                <button type="button" class="btn btn-sm {{ $activity === $key ? 'btn-primary' : 'btn-outline-primary' }}" wire:click="setActivity('{{ $key }}')">
                                    <i class="bi {{ $icon }}"></i> {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endunless

                <form wire:submit="save">
                    <div class="card-body">
                        @if(! $patient)
                            <div class="alert alert-info mb-0">
                                Search and select a patient to start recording maternity activity.
                            </div>
                        @else
                            @if($activity === 'antenatal')
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">LMP</label>
                                        <input type="date" class="form-control @error('form.last_menstrual_period') is-invalid @enderror" wire:model="form.last_menstrual_period">
                                        @error('form.last_menstrual_period') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">EDD</label>
                                        <input type="date" class="form-control @error('form.expected_delivery_date') is-invalid @enderror" wire:model="form.expected_delivery_date">
                                        @error('form.expected_delivery_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Gestational Weeks</label>
                                        <input type="number" class="form-control @error('form.gestational_weeks') is-invalid @enderror" wire:model="form.gestational_weeks">
                                        @error('form.gestational_weeks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Blood Pressure</label>
                                        <input type="text" class="form-control @error('form.blood_pressure') is-invalid @enderror" wire:model="form.blood_pressure">
                                        @error('form.blood_pressure') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Weight</label>
                                        <input type="number" step="0.01" class="form-control @error('form.weight') is-invalid @enderror" wire:model="form.weight">
                                        @error('form.weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Status</label>
                                        <select class="form-select @error('form.status') is-invalid @enderror" wire:model="form.status">
                                            <option value="">Optional</option>
                                            <option value="normal">Normal</option>
                                            <option value="complicated">Complicated</option>
                                            <option value="high_risk">High Risk</option>
                                        </select>
                                        @error('form.status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            @elseif($activity === 'labour')
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Onset Time</label>
                                        <input type="datetime-local" class="form-control @error('form.labour_onset_time') is-invalid @enderror" wire:model="form.labour_onset_time">
                                        @error('form.labour_onset_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Gestational Weeks</label>
                                        <input type="number" class="form-control @error('form.gestational_weeks') is-invalid @enderror" wire:model="form.gestational_weeks">
                                        @error('form.gestational_weeks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Blood Pressure</label>
                                        <input type="text" class="form-control @error('form.blood_pressure') is-invalid @enderror" wire:model="form.blood_pressure">
                                        @error('form.blood_pressure') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Stage</label>
                                        <select class="form-select" wire:model="form.stage">
                                            <option value="">Optional</option>
                                            <option value="not_started">Not Started</option>
                                            <option value="first_stage">First Stage</option>
                                            <option value="second_stage">Second Stage</option>
                                            <option value="third_stage">Third Stage</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" wire:model="form.status">
                                            <option value="">Optional</option>
                                            <option value="ongoing">Ongoing</option>
                                            <option value="completed">Completed</option>
                                            <option value="complicated">Complicated</option>
                                        </select>
                                    </div>
                                </div>
                            @elseif($activity === 'delivery')
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Link Labour Record</label>
                                        <select class="form-select @error('form.labour_id') is-invalid @enderror" wire:model="form.labour_id">
                                            <option value="">No labour record</option>
                                            @foreach($labours as $labour)
                                                <option value="{{ $labour->id }}">{{ $labour->labour_onset_time?->format('M d, Y h:i A') }} - {{ ucfirst($labour->status) }}</option>
                                            @endforeach
                                        </select>
                                        @error('form.labour_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Delivery Date & Time</label>
                                        <input type="datetime-local" class="form-control @error('form.delivery_date_time') is-invalid @enderror" wire:model="form.delivery_date_time">
                                        @error('form.delivery_date_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Delivery Type</label>
                                        <select class="form-select" wire:model="form.delivery_type">
                                            <option value="">Optional</option>
                                            <option value="vaginal">Vaginal</option>
                                            <option value="assisted_vaginal">Assisted Vaginal</option>
                                            <option value="caesarean">Caesarean</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Babies</label>
                                        <input type="number" min="1" max="10" class="form-control @error('form.number_of_babies') is-invalid @enderror" wire:model="form.number_of_babies">
                                        @error('form.number_of_babies') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" wire:model="form.delivery_status">
                                            <option value="">Optional</option>
                                            <option value="successful">Successful</option>
                                            <option value="complicated">Complicated</option>
                                            <option value="maternal_death">Maternal Death</option>
                                            <option value="fetal_death">Fetal Death</option>
                                        </select>
                                    </div>
                                </div>
                            @elseif($activity === 'newborn')
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Link Delivery Record</label>
                                        <select class="form-select @error('form.delivery_id') is-invalid @enderror" wire:model="form.delivery_id">
                                            <option value="">No delivery record</option>
                                            @foreach($deliveries as $delivery)
                                                <option value="{{ $delivery->id }}">{{ $delivery->delivery_date_time?->format('M d, Y h:i A') }} - {{ ucfirst(str_replace('_', ' ', $delivery->delivery_type)) }}</option>
                                            @endforeach
                                        </select>
                                        @error('form.delivery_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Birth Date & Time</label>
                                        <input type="datetime-local" class="form-control @error('form.birth_date_time') is-invalid @enderror" wire:model="form.birth_date_time">
                                        @error('form.birth_date_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Sex</label>
                                        <select class="form-select" wire:model="form.sex">
                                            <option value="">Optional</option>
                                            <option value="female">Female</option>
                                            <option value="male">Male</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Weight</label>
                                        <input type="text" class="form-control" wire:model="form.birth_weight">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Length</label>
                                        <input type="text" class="form-control" wire:model="form.birth_length">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" wire:model="form.status">
                                            <option value="">Optional</option>
                                            <option value="alive">Alive</option>
                                            <option value="stillborn">Stillborn</option>
                                            <option value="early_neonatal_death">Early Neonatal Death</option>
                                        </select>
                                    </div>
                                </div>
                            @elseif($activity === 'postnatal')
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Link Delivery Record</label>
                                        <select class="form-select @error('form.delivery_id') is-invalid @enderror" wire:model="form.delivery_id">
                                            <option value="">No delivery record</option>
                                            @foreach($deliveries as $delivery)
                                                <option value="{{ $delivery->id }}">{{ $delivery->delivery_date_time?->format('M d, Y h:i A') }} - {{ ucfirst(str_replace('_', ' ', $delivery->delivery_type)) }}</option>
                                            @endforeach
                                        </select>
                                        @error('form.delivery_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Examination Date & Time</label>
                                        <input type="datetime-local" class="form-control @error('form.examination_date_time') is-invalid @enderror" wire:model="form.examination_date_time">
                                        @error('form.examination_date_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Blood Pressure</label>
                                        <input type="text" class="form-control" wire:model="form.blood_pressure">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Temperature</label>
                                        <input type="text" class="form-control" wire:model="form.temperature">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Recovery Status</label>
                                        <select class="form-select" wire:model="form.recovery_status">
                                            <option value="">Optional</option>
                                            <option value="normal">Normal</option>
                                            <option value="complicated">Complicated</option>
                                            <option value="needs_referral">Needs Referral</option>
                                        </select>
                                    </div>
                                </div>
                            @elseif($activity === 'child_follow_up')
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Link Newborn Record</label>
                                        <select class="form-select @error('form.newborn_id') is-invalid @enderror" wire:model="form.newborn_id">
                                            <option value="">No newborn record</option>
                                            @foreach($newborns as $newborn)
                                                <option value="{{ $newborn->id }}">{{ $newborn->newborn_registration_number ?? 'Newborn #' . $newborn->id }} - {{ ucfirst($newborn->sex) }}</option>
                                            @endforeach
                                        </select>
                                        @error('form.newborn_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Follow-up Date & Time</label>
                                        <input type="datetime-local" class="form-control @error('form.follow_up_date_time') is-invalid @enderror" wire:model="form.follow_up_date_time">
                                        @error('form.follow_up_date_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Days of Life</label>
                                        <input type="number" min="0" class="form-control" wire:model="form.days_of_life">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Period</label>
                                        <select class="form-select" wire:model="form.follow_up_period">
                                            <option value="">Select period</option>
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
                                    <div class="col-md-3">
                                        <label class="form-label">Weight</label>
                                        <input type="text" class="form-control" wire:model="form.weight">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Health Status</label>
                                        <select class="form-select" wire:model="form.health_status">
                                            <option value="">Optional</option>
                                            <option value="normal">Normal</option>
                                            <option value="at_risk">At Risk</option>
                                            <option value="needs_referral">Needs Referral</option>
                                            <option value="referred">Referred</option>
                                        </select>
                                    </div>
                                </div>
                            @endif

                            @php
                                $extraGroups = match ($activity) {
                                    'antenatal' => [
                                        'Pregnancy Details' => [
                                            ['number_of_fetuses', 'Number of Fetuses', 'number'],
                                            ['pregnancy_type', 'Pregnancy Type', 'text'],
                                        ],
                                        'Examination Findings' => [
                                            ['height', 'Height', 'number'],
                                            ['abdominal_examination', 'Abdominal Examination', 'textarea'],
                                            ['fundal_height', 'Fundal Height', 'text'],
                                            ['fetal_heart_rate', 'Fetal Heart Rate', 'text'],
                                            ['fetal_movement', 'Fetal Movement', 'textarea'],
                                            ['vaginal_examination', 'Vaginal Examination', 'textarea'],
                                        ],
                                        'Investigations and Plan' => [
                                            ['urine_analysis', 'Urine Analysis', 'textarea'],
                                            ['blood_tests', 'Blood Tests', 'textarea'],
                                            ['ultrasound_findings', 'Ultrasound Findings', 'textarea'],
                                            ['risk_factors', 'Risk Factors', 'textarea'],
                                            ['complications', 'Complications', 'textarea'],
                                            ['management_plan', 'Management Plan', 'textarea'],
                                            ['counseling_topics', 'Counseling Topics', 'textarea'],
                                            ['took_supplements', 'Took Supplements', 'checkbox'],
                                        ],
                                    ],
                                    'labour' => [
                                        'Labour Onset and History' => [
                                            ['mode_of_onset', 'Mode of Onset', 'select', ['' => 'Select', 'spontaneous' => 'Spontaneous', 'induced' => 'Induced']],
                                            ['reason_for_induction', 'Reason for Induction', 'textarea'],
                                            ['labour_type', 'Labour Type', 'text'],
                                            ['previous_obstetric_history', 'Previous Obstetric History', 'textarea'],
                                        ],
                                        'Pre-labour Assessment' => [
                                            ['cervical_state', 'Cervical State', 'text'],
                                            ['show', 'Show', 'select', ['' => 'Select', 'present' => 'Present', 'absent' => 'Absent']],
                                            ['rupture_of_membranes', 'Rupture of Membranes', 'select', ['' => 'Select', 'intact' => 'Intact', 'spontaneous rupture' => 'Spontaneous Rupture', 'artificial rupture' => 'Artificial Rupture']],
                                            ['liquor', 'Liquor', 'textarea'],
                                        ],
                                        'Vitals and Progress' => [
                                            ['pulse_rate', 'Pulse Rate', 'text'],
                                            ['temperature', 'Temperature', 'text'],
                                            ['respiration_rate', 'Respiration Rate', 'text'],
                                            ['first_stage_started_at', 'First Stage Started At', 'datetime-local'],
                                            ['second_stage_started_at', 'Second Stage Started At', 'datetime-local'],
                                            ['third_stage_started_at', 'Third Stage Started At', 'datetime-local'],
                                            ['fetal_heart_rate', 'Fetal Heart Rate', 'text'],
                                            ['fetal_monitoring_notes', 'Fetal Monitoring Notes', 'textarea'],
                                            ['complications', 'Complications', 'textarea'],
                                        ],
                                    ],
                                    'delivery' => [
                                        'Delivery Details' => [
                                            ['reason_for_delivery_type', 'Reason for Delivery Type', 'textarea'],
                                            ['assisted_with', 'Assisted With', 'select', ['' => 'Select', 'vacuum' => 'Vacuum', 'forceps' => 'Forceps']],
                                            ['indication_for_assistance', 'Indication for Assistance', 'textarea'],
                                            ['caesarean_type', 'Caesarean Type', 'select', ['' => 'Select', 'elective' => 'Elective', 'emergency' => 'Emergency']],
                                            ['indication_for_caesarean', 'Indication for Caesarean', 'textarea'],
                                        ],
                                        'Perineum and Placenta' => [
                                            ['perineal_trauma', 'Perineal Trauma', 'select', ['' => 'Select', 'intact' => 'Intact', '1st degree' => '1st Degree', '2nd degree' => '2nd Degree', '3rd degree' => '3rd Degree', '4th degree' => '4th Degree']],
                                            ['episiotomy', 'Episiotomy', 'textarea'],
                                            ['perineal_repair', 'Perineal Repair', 'textarea'],
                                            ['placenta_delivery_method', 'Placenta Delivery Method', 'select', ['' => 'Select', 'spontaneous' => 'Spontaneous', 'manual removal' => 'Manual Removal']],
                                            ['placenta_delivered_at', 'Placenta Delivered At', 'datetime-local'],
                                            ['placental_examination', 'Placental Examination', 'textarea'],
                                        ],
                                        'Maternal Condition and Complications' => [
                                            ['estimated_blood_loss', 'Estimated Blood Loss', 'text'],
                                            ['blood_loss_assessment', 'Blood Loss Assessment', 'textarea'],
                                            ['uterine_tone', 'Uterine Tone', 'text'],
                                            ['per_vaginal_bleeding', 'Per Vaginal Bleeding', 'text'],
                                            ['blood_pressure', 'Blood Pressure', 'text'],
                                            ['pulse_rate', 'Pulse Rate', 'text'],
                                            ['general_condition', 'General Condition', 'text'],
                                            ['complications', 'Complications', 'textarea'],
                                            ['management_of_complications', 'Management of Complications', 'textarea'],
                                        ],
                                    ],
                                    'newborn' => [
                                        'Birth Details' => [
                                            ['birth_order', 'Birth Order', 'number'],
                                            ['head_circumference', 'Head Circumference', 'text'],
                                            ['presentation', 'Presentation', 'select', ['' => 'Select', 'cephalic' => 'Cephalic', 'breech' => 'Breech', 'transverse' => 'Transverse', 'face' => 'Face']],
                                            ['delivery_notes', 'Delivery Notes', 'textarea'],
                                        ],
                                        'APGAR' => [
                                            ['apgar_score_1_minute', 'APGAR 1 Minute', 'number'],
                                            ['apgar_score_5_minutes', 'APGAR 5 Minutes', 'number'],
                                            ['apgar_score_10_minutes', 'APGAR 10 Minutes', 'number'],
                                            ['apgar_appearance_1min', 'Appearance 1 Min', 'number'],
                                            ['apgar_pulse_1min', 'Pulse 1 Min', 'number'],
                                            ['apgar_grimace_1min', 'Grimace 1 Min', 'number'],
                                            ['apgar_activity_1min', 'Activity 1 Min', 'number'],
                                            ['apgar_respiration_1min', 'Respiration 1 Min', 'number'],
                                        ],
                                        'Condition and Care' => [
                                            ['physical_examination', 'Physical Examination', 'textarea'],
                                            ['birth_defects_noted', 'Birth Defects Noted', 'textarea'],
                                            ['meconium_aspiration', 'Meconium Aspiration', 'textarea'],
                                            ['breastfeeding_initiated', 'Breastfeeding Initiated', 'checkbox'],
                                            ['first_breastfeed_time', 'First Breastfeed Time', 'datetime-local'],
                                            ['feeding_problems', 'Feeding Problems', 'textarea'],
                                            ['vitamin_k_given', 'Vitamin K Given', 'checkbox'],
                                            ['eye_prophylaxis_given', 'Eye Prophylaxis Given', 'checkbox'],
                                            ['immunizations_given', 'Immunizations Given', 'checkbox'],
                                            ['immunizations_details', 'Immunizations Details', 'textarea'],
                                            ['screening_test_done', 'Screening Test Done', 'checkbox'],
                                            ['screening_test_results', 'Screening Test Results', 'textarea'],
                                            ['special_care_needed', 'Special Care Needed', 'textarea'],
                                            ['referred_to', 'Referred To', 'textarea'],
                                        ],
                                    ],
                                    'postnatal' => [
                                        'Timing and Vitals' => [
                                            ['hours_post_delivery', 'Hours Post Delivery', 'number'],
                                            ['examination_time', 'Examination Time', 'select', ['' => 'Select', 'immediate_0-2h' => 'Immediate 0-2h', '6-12h' => '6-12h', '24h' => '24h', '48h' => '48h', 'day4_6' => 'Day 4-6', 'week1' => 'Week 1', 'week2' => 'Week 2', 'week6' => 'Week 6']],
                                            ['pulse_rate', 'Pulse Rate', 'text'],
                                            ['respiration_rate', 'Respiration Rate', 'text'],
                                            ['general_appearance', 'General Appearance', 'textarea'],
                                            ['consciousness_level', 'Consciousness Level', 'select', ['' => 'Select', 'alert' => 'Alert', 'drowsy' => 'Drowsy', 'unconscious' => 'Unconscious']],
                                            ['skin_colour', 'Skin Colour', 'textarea'],
                                        ],
                                        'Maternal Physical Assessment' => [
                                            ['uterine_size', 'Uterine Size', 'text'],
                                            ['uterine_consistency', 'Uterine Consistency', 'text'],
                                            ['uterine_tenderness', 'Uterine Tenderness', 'text'],
                                            ['fundal_height', 'Fundal Height', 'text'],
                                            ['lochia_type', 'Lochia Type', 'text'],
                                            ['lochia_amount', 'Lochia Amount', 'text'],
                                            ['lochia_odour', 'Lochia Odour', 'text'],
                                            ['clot_presence', 'Clot Presence', 'text'],
                                            ['perineal_assessment', 'Perineal Assessment', 'textarea'],
                                            ['perineal_wound_status', 'Perineal Wound Status', 'text'],
                                            ['perineal_pain', 'Perineal Pain', 'textarea'],
                                            ['vaginal_examination', 'Vaginal Examination', 'textarea'],
                                            ['abdominal_examination', 'Abdominal Examination', 'textarea'],
                                            ['wound_assessment', 'Wound Assessment', 'textarea'],
                                            ['drain_status', 'Drain Status', 'textarea'],
                                            ['lower_limbs_examination', 'Lower Limbs Examination', 'textarea'],
                                            ['oedema_assessment', 'Oedema Assessment', 'textarea'],
                                            ['calf_tenderness', 'Calf Tenderness', 'textarea'],
                                            ['signs_of_dvt', 'Signs of DVT', 'textarea'],
                                        ],
                                        'Breast, Mood, Complications and Plan' => [
                                            ['breast_examination', 'Breast Examination', 'textarea'],
                                            ['nipple_condition', 'Nipple Condition', 'textarea'],
                                            ['breast_engorgement', 'Breast Engorgement', 'textarea'],
                                            ['breast_milk_expression', 'Breast Milk Expression', 'textarea'],
                                            ['breastfeeding_successful', 'Breastfeeding Successful', 'checkbox'],
                                            ['breastfeeding_problems', 'Breastfeeding Problems', 'textarea'],
                                            ['maternal_mood', 'Maternal Mood', 'textarea'],
                                            ['emotional_state', 'Emotional State', 'textarea'],
                                            ['signs_of_depression', 'Signs of Depression', 'checkbox'],
                                            ['bonding_with_baby', 'Bonding with Baby', 'textarea'],
                                            ['complications_identified', 'Complications Identified', 'textarea'],
                                            ['infection_signs', 'Infection Signs', 'textarea'],
                                            ['bleeding_assessment', 'Bleeding Assessment', 'textarea'],
                                            ['hypertension_assessment', 'Hypertension Assessment', 'textarea'],
                                            ['sleep_patterns', 'Sleep Patterns', 'textarea'],
                                            ['pain_level', 'Pain Level', 'textarea'],
                                            ['activity_tolerance', 'Activity Tolerance', 'textarea'],
                                            ['perineal_care_ability', 'Perineal Care Ability', 'textarea'],
                                            ['counseling_topics', 'Counseling Topics', 'textarea'],
                                            ['contraception_discussed', 'Contraception Discussed', 'checkbox'],
                                            ['contraception_method_chosen', 'Contraception Method Chosen', 'textarea'],
                                            ['hygiene_taught', 'Hygiene Taught', 'checkbox'],
                                            ['danger_signs_explained', 'Danger Signs Explained', 'checkbox'],
                                            ['medications_prescribed', 'Medications Prescribed', 'textarea'],
                                            ['follow_up_plan', 'Follow-up Plan', 'textarea'],
                                            ['next_follow_up_date', 'Next Follow-up Date', 'datetime-local'],
                                        ],
                                    ],
                                    'child_follow_up' => [
                                        'Location and Growth' => [
                                            ['location', 'Location', 'select', ['' => 'Optional', 'hospital' => 'Hospital', 'clinic' => 'Clinic', 'home' => 'Home', 'other' => 'Other']],
                                            ['location_details', 'Location Details', 'textarea'],
                                            ['temperature', 'Temperature', 'text'],
                                            ['heart_rate', 'Heart Rate', 'text'],
                                            ['respiratory_rate', 'Respiratory Rate', 'text'],
                                            ['length', 'Length', 'text'],
                                            ['head_circumference', 'Head Circumference', 'text'],
                                            ['weight_percentile', 'Weight Percentile', 'number'],
                                            ['weight_change_since_birth', 'Weight Change Since Birth', 'text'],
                                            ['weight_gain_rate', 'Weight Gain Rate', 'text'],
                                            ['weight_assessment', 'Weight Assessment', 'textarea'],
                                        ],
                                        'Feeding, Elimination and Examination' => [
                                            ['how_baby_is_feeding', 'How Baby Is Feeding', 'textarea'],
                                            ['mother_observations', 'Mother Observations', 'textarea'],
                                            ['general_appearance', 'General Appearance', 'textarea'],
                                            ['activity_level', 'Activity Level', 'textarea'],
                                            ['alertness', 'Alertness', 'textarea'],
                                            ['skin_examination', 'Skin Examination', 'textarea'],
                                            ['umbilical_cord_status', 'Umbilical Cord Status', 'textarea'],
                                            ['umbilical_discharge', 'Umbilical Discharge', 'textarea'],
                                            ['signs_of_infection', 'Signs of Infection', 'textarea'],
                                            ['jaundice_present', 'Jaundice Present', 'text'],
                                            ['jaundice_level', 'Jaundice Level', 'textarea'],
                                            ['jaundice_management', 'Jaundice Management', 'textarea'],
                                            ['breast_examination', 'Breast Examination', 'textarea'],
                                            ['latching_quality', 'Latching Quality', 'textarea'],
                                            ['suckling_pattern', 'Suckling Pattern', 'textarea'],
                                            ['milk_transfer', 'Milk Transfer', 'textarea'],
                                            ['bottle_feeding_if_applicable', 'Bottle Feeding if Applicable', 'textarea'],
                                            ['feeding_frequency', 'Feeding Frequency', 'text'],
                                            ['feeding_duration', 'Feeding Duration', 'text'],
                                            ['feeding_problems', 'Feeding Problems', 'textarea'],
                                            ['mother_nipple_problems', 'Mother Nipple Problems', 'textarea'],
                                            ['urinary_output', 'Urinary Output', 'textarea'],
                                            ['stool_output', 'Stool Output', 'textarea'],
                                            ['stool_characteristics', 'Stool Characteristics', 'textarea'],
                                            ['elimination_problems', 'Elimination Problems', 'textarea'],
                                        ],
                                        'Development, Screening and Plan' => [
                                            ['responsiveness', 'Responsiveness', 'textarea'],
                                            ['cry_quality', 'Cry Quality', 'textarea'],
                                            ['reflex_assessment', 'Reflex Assessment', 'textarea'],
                                            ['muscle_tone', 'Muscle Tone', 'textarea'],
                                            ['immunizations_up_to_date', 'Immunizations Up to Date', 'checkbox'],
                                            ['immunizations_given', 'Immunizations Given', 'textarea'],
                                            ['immunizations_planned', 'Immunizations Planned', 'textarea'],
                                            ['newborn_screening_done', 'Newborn Screening Done', 'checkbox'],
                                            ['newborn_screening_results', 'Newborn Screening Results', 'textarea'],
                                            ['hearing_screening_done', 'Hearing Screening Done', 'checkbox'],
                                            ['hearing_screening_results', 'Hearing Screening Results', 'textarea'],
                                            ['developmental_milestones', 'Developmental Milestones', 'textarea'],
                                            ['developmental_concerns', 'Developmental Concerns', 'textarea'],
                                            ['mother_recovery_status', 'Mother Recovery Status', 'textarea'],
                                            ['mother_emotional_wellbeing', 'Mother Emotional Wellbeing', 'textarea'],
                                            ['mother_breastfeeding_support', 'Mother Breastfeeding Support', 'textarea'],
                                            ['baby_concerns', 'Baby Concerns', 'textarea'],
                                            ['mother_concerns', 'Mother Concerns', 'textarea'],
                                            ['complications_identified', 'Complications Identified', 'textarea'],
                                            ['counseling_topics', 'Counseling Topics', 'textarea'],
                                            ['infant_care_advice_given', 'Infant Care Advice Given', 'checkbox'],
                                            ['feeding_guidance_given', 'Feeding Guidance Given', 'checkbox'],
                                            ['cord_care_advice_given', 'Cord Care Advice Given', 'checkbox'],
                                            ['hygiene_safety_advice_given', 'Hygiene Safety Advice Given', 'checkbox'],
                                            ['danger_signs_explained', 'Danger Signs Explained', 'checkbox'],
                                            ['referral_reason', 'Referral Reason', 'textarea'],
                                            ['referral_destination', 'Referral Destination', 'textarea'],
                                            ['next_follow_up_date', 'Next Follow-up Date', 'datetime-local'],
                                            ['next_follow_up_reason', 'Next Follow-up Reason', 'textarea'],
                                        ],
                                    ],
                                    default => [],
                                };
                            @endphp

                            @if(! empty($extraGroups))
                                <div class="accordion mt-3" id="maternityExtraFields">
                                    @foreach($extraGroups as $groupTitle => $fields)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#extra-{{ Str::slug($activity . '-' . $groupTitle) }}">
                                                    {{ $groupTitle }}
                                                </button>
                                            </h2>
                                            <div id="extra-{{ Str::slug($activity . '-' . $groupTitle) }}" class="accordion-collapse collapse show">
                                                <div class="accordion-body">
                                                    <div class="row g-3">
                                                        @foreach($fields as $field)
                                                            @php
                                                                [$name, $label, $type] = $field;
                                                                $options = $field[3] ?? [];
                                                                $wide = in_array($type, ['textarea', 'checkbox'], true);
                                                            @endphp
                                                            <div class="{{ $wide ? 'col-12' : 'col-md-4' }}">
                                                                @if($type === 'checkbox')
                                                                    <div class="form-check mt-2">
                                                                        <input class="form-check-input" type="checkbox" id="field-{{ $activity }}-{{ $name }}" wire:model="form.{{ $name }}">
                                                                        <label class="form-check-label" for="field-{{ $activity }}-{{ $name }}">{{ $label }}</label>
                                                                    </div>
                                                                @else
                                                                    <label class="form-label">{{ $label }}</label>
                                                                    @if($type === 'textarea')
                                                                        <textarea class="form-control @error('form.' . $name) is-invalid @enderror" rows="3" wire:model="form.{{ $name }}"></textarea>
                                                                    @elseif($type === 'select')
                                                                        <select class="form-select @error('form.' . $name) is-invalid @enderror" wire:model="form.{{ $name }}">
                                                                            @foreach($options as $value => $text)
                                                                                <option value="{{ $value }}">{{ $text }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    @else
                                                                        <input type="{{ $type }}" class="form-control @error('form.' . $name) is-invalid @enderror" wire:model="form.{{ $name }}">
                                                                    @endif
                                                                    @error('form.' . $name) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-3">
                                <label class="form-label">Clinical Summary / Notes</label>
                                @if(in_array($activity, ['delivery', 'newborn'], true))
                                    <textarea class="form-control" rows="4" wire:model="form.{{ $activity === 'delivery' ? 'delivery_summary' : 'neonatal_observations' }}"></textarea>
                                @elseif($activity === 'child_follow_up')
                                    <textarea class="form-control" rows="4" wire:model="form.clinical_summary"></textarea>
                                @elseif($activity === 'postnatal')
                                    <textarea class="form-control" rows="4" wire:model="form.clinical_summary"></textarea>
                                @else
                                    <textarea class="form-control" rows="4" wire:model="form.clinical_notes"></textarea>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if($patient)
                        <div class="card-footer bg-white d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" @disabled(strtolower((string) $patient->demographic?->gender) !== 'female')>
                                <span wire:loading.remove wire:target="save"><i class="bi bi-save"></i> Save Activity</span>
                                <span wire:loading wire:target="save">Saving...</span>
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
