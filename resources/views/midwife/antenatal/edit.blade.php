@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-0">Edit Antenatal Care Record</h1>
            <p class="text-muted">Update antenatal care record for {{ $antenatalCare->patient->demographic->first_name }} {{ $antenatalCare->patient->demographic->last_name }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('midwife.antenatal.show', $antenatalCare) }}" class="btn btn-secondary">
                <i class="bi bi-chevron-left"></i> Back
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Hospital Number</label>
                            <p>{{ $antenatalCare->patient->hospital_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Patient Name</label>
                            <p>{{ $antenatalCare->patient->demographic->first_name }} {{ $antenatalCare->patient->demographic->last_name }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Age</label>
                            <p>{{ now()->diffInYears($antenatalCare->patient->demographic->date_of_birth) }} years</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Gender</label>
                            <p>{{ $antenatalCare->patient->demographic->gender }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Update Antenatal Care Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('midwife.antenatal.update', $antenatalCare) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Pregnancy Details Section -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-heart-pulse"></i> Pregnancy Details
                            </h6>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="last_menstrual_period" class="form-label">Last Menstrual Period</label>
                                    <input type="date" class="form-control @error('last_menstrual_period') is-invalid @enderror" 
                                           id="last_menstrual_period" name="last_menstrual_period" 
                                           value="{{ old('last_menstrual_period', $antenatalCare->last_menstrual_period?->format('Y-m-d')) }}">
                                    @error('last_menstrual_period')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="expected_delivery_date" class="form-label">Expected Delivery Date</label>
                                    <input type="date" class="form-control @error('expected_delivery_date') is-invalid @enderror" 
                                           id="expected_delivery_date" name="expected_delivery_date"
                                           value="{{ old('expected_delivery_date', $antenatalCare->expected_delivery_date?->format('Y-m-d')) }}">
                                    @error('expected_delivery_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="gestational_weeks" class="form-label">Gestational Weeks</label>
                                    <input type="number" class="form-control @error('gestational_weeks') is-invalid @enderror" 
                                           id="gestational_weeks" name="gestational_weeks" 
                                           min="1" max="42"
                                           value="{{ old('gestational_weeks', $antenatalCare->gestational_weeks) }}">
                                    @error('gestational_weeks')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="number_of_fetuses" class="form-label">Number of Fetuses</label>
                                    <input type="number" class="form-control @error('number_of_fetuses') is-invalid @enderror" 
                                           id="number_of_fetuses" name="number_of_fetuses" 
                                           min="1" max="8"
                                           value="{{ old('number_of_fetuses', $antenatalCare->number_of_fetuses) }}">
                                    @error('number_of_fetuses')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="pregnancy_type" class="form-label">Pregnancy Type</label>
                                    <input type="text" class="form-control @error('pregnancy_type') is-invalid @enderror" 
                                           id="pregnancy_type" name="pregnancy_type" 
                                           placeholder="e.g., singleton, twins"
                                           value="{{ old('pregnancy_type', $antenatalCare->pregnancy_type) }}">
                                    @error('pregnancy_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Vital Signs Section -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-thermometer"></i> Vital Signs
                            </h6>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="blood_pressure" class="form-label">Blood Pressure</label>
                                    <input type="text" class="form-control @error('blood_pressure') is-invalid @enderror" 
                                           id="blood_pressure" name="blood_pressure"
                                           placeholder="e.g., 120/80"
                                           value="{{ old('blood_pressure', $antenatalCare->blood_pressure) }}">
                                    @error('blood_pressure')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="weight" class="form-label">Weight (kg)</label>
                                    <input type="number" class="form-control @error('weight') is-invalid @enderror" 
                                           id="weight" name="weight"
                                           min="30" max="250" step="0.1"
                                           value="{{ old('weight', $antenatalCare->weight) }}">
                                    @error('weight')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="height" class="form-label">Height (cm)</label>
                                    <input type="number" class="form-control @error('height') is-invalid @enderror" 
                                           id="height" name="height"
                                           min="100" max="250" step="0.1"
                                           value="{{ old('height', $antenatalCare->height) }}">
                                    @error('height')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Physical Examination Section -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-clipboard-check"></i> Physical Examination
                            </h6>

                            <div class="mb-3">
                                <label for="abdominal_examination" class="form-label">Abdominal Examination</label>
                                <textarea class="form-control @error('abdominal_examination') is-invalid @enderror" 
                                          id="abdominal_examination" name="abdominal_examination" 
                                          rows="2">{{ old('abdominal_examination', $antenatalCare->abdominal_examination) }}</textarea>
                                @error('abdominal_examination')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="fundal_height" class="form-label">Fundal Height (cm)</label>
                                    <input type="text" class="form-control @error('fundal_height') is-invalid @enderror" 
                                           id="fundal_height" name="fundal_height"
                                           value="{{ old('fundal_height', $antenatalCare->fundal_height) }}">
                                    @error('fundal_height')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="fetal_heart_rate" class="form-label">Fetal Heart Rate (bpm)</label>
                                    <input type="text" class="form-control @error('fetal_heart_rate') is-invalid @enderror" 
                                           id="fetal_heart_rate" name="fetal_heart_rate"
                                           value="{{ old('fetal_heart_rate', $antenatalCare->fetal_heart_rate) }}">
                                    @error('fetal_heart_rate')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="fetal_movement" class="form-label">Fetal Movement</label>
                                <textarea class="form-control @error('fetal_movement') is-invalid @enderror" 
                                          id="fetal_movement" name="fetal_movement"
                                          rows="2">{{ old('fetal_movement', $antenatalCare->fetal_movement) }}</textarea>
                                @error('fetal_movement')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="vaginal_examination" class="form-label">Vaginal Examination Findings</label>
                                <textarea class="form-control @error('vaginal_examination') is-invalid @enderror" 
                                          id="vaginal_examination" name="vaginal_examination"
                                          rows="2">{{ old('vaginal_examination', $antenatalCare->vaginal_examination) }}</textarea>
                                @error('vaginal_examination')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Investigations Section -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-graph-up"></i> Investigations
                            </h6>

                            <div class="mb-3">
                                <label for="urine_analysis" class="form-label">Urine Analysis Results</label>
                                <textarea class="form-control @error('urine_analysis') is-invalid @enderror" 
                                          id="urine_analysis" name="urine_analysis"
                                          rows="2">{{ old('urine_analysis', $antenatalCare->urine_analysis) }}</textarea>
                                @error('urine_analysis')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="blood_tests" class="form-label">Blood Test Results</label>
                                <textarea class="form-control @error('blood_tests') is-invalid @enderror" 
                                          id="blood_tests" name="blood_tests"
                                          rows="2">{{ old('blood_tests', $antenatalCare->blood_tests) }}</textarea>
                                @error('blood_tests')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="ultrasound_findings" class="form-label">Ultrasound Findings</label>
                                <textarea class="form-control @error('ultrasound_findings') is-invalid @enderror" 
                                          id="ultrasound_findings" name="ultrasound_findings"
                                          rows="2">{{ old('ultrasound_findings', $antenatalCare->ultrasound_findings) }}</textarea>
                                @error('ultrasound_findings')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Risk Assessment Section -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-exclamation-triangle"></i> Risk Assessment
                            </h6>

                            <div class="mb-3">
                                <label for="risk_factors" class="form-label">Risk Factors</label>
                                <textarea class="form-control @error('risk_factors') is-invalid @enderror" 
                                          id="risk_factors" name="risk_factors" rows="2">{{ old('risk_factors', $antenatalCare->risk_factors) }}</textarea>
                                @error('risk_factors')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="complications" class="form-label">Complications</label>
                                <textarea class="form-control @error('complications') is-invalid @enderror" 
                                          id="complications" name="complications" rows="2">{{ old('complications', $antenatalCare->complications) }}</textarea>
                                @error('complications')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Overall Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" 
                                        id="status" name="status">
                                    <option value="">-- Select Status --</option>
                                    <option value="normal" {{ old('status', $antenatalCare->status) === 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="complicated" {{ old('status', $antenatalCare->status) === 'complicated' ? 'selected' : '' }}>Complicated</option>
                                    <option value="high_risk" {{ old('status', $antenatalCare->status) === 'high_risk' ? 'selected' : '' }}>High Risk</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Management & Counseling Section -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-chat-dots"></i> Management & Counseling
                            </h6>

                            <div class="mb-3">
                                <label for="management_plan" class="form-label">Management Plan</label>
                                <textarea class="form-control @error('management_plan') is-invalid @enderror" 
                                          id="management_plan" name="management_plan" rows="2">{{ old('management_plan', $antenatalCare->management_plan) }}</textarea>
                                @error('management_plan')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="counseling_topics" class="form-label">Counseling Topics</label>
                                <textarea class="form-control @error('counseling_topics') is-invalid @enderror" 
                                          id="counseling_topics" name="counseling_topics" rows="2">{{ old('counseling_topics', $antenatalCare->counseling_topics) }}</textarea>
                                @error('counseling_topics')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="took_supplements" 
                                           name="took_supplements" value="1"
                                           {{ old('took_supplements', $antenatalCare->took_supplements) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="took_supplements">
                                        Patient taking supplements (Iron, Folic Acid, Vitamins)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Clinical Notes Section -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-file-text"></i> Clinical Notes
                            </h6>

                            <div class="mb-3">
                                <label for="clinical_notes" class="form-label">Additional Clinical Notes</label>
                                <textarea class="form-control @error('clinical_notes') is-invalid @enderror" 
                                          id="clinical_notes" name="clinical_notes" rows="3">{{ old('clinical_notes', $antenatalCare->clinical_notes) }}</textarea>
                                @error('clinical_notes')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('midwife.antenatal.show', $antenatalCare) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit Mode</h5>
                </div>
                <div class="card-body small">
                    <p class="text-muted">You are editing this antenatal care record. Make necessary changes and click "Update Record" to save.</p>

                    <hr>

                    <div class="mb-3">
                        <label class="text-muted"><strong>Record ID</strong></label>
                        <p class="mb-0"><code>{{ $antenatalCare->id }}</code></p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted"><strong>Created</strong></label>
                        <p class="mb-0">{{ $antenatalCare->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted"><strong>Last Updated</strong></label>
                        <p class="mb-0">{{ $antenatalCare->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-calculate gestational weeks from LMP
    document.getElementById('last_menstrual_period').addEventListener('change', function() {
        if(this.value) {
            const lmp = new Date(this.value);
            const today = new Date();
            const weeks = Math.floor((today - lmp) / (7 * 24 * 60 * 60 * 1000));
            document.getElementById('gestational_weeks').value = weeks;
        }
    });

    // Auto-calculate EDD from LMP
    document.getElementById('last_menstrual_period').addEventListener('change', function() {
        if(this.value) {
            const lmp = new Date(this.value);
            const edd = new Date(lmp.getTime() + (280 * 24 * 60 * 60 * 1000));
            const year = edd.getFullYear();
            const month = String(edd.getMonth() + 1).padStart(2, '0');
            const day = String(edd.getDate()).padStart(2, '0');
            document.getElementById('expected_delivery_date').value = `${year}-${month}-${day}`;
        }
    });
</script>
@endsection
