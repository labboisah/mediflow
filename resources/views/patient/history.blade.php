@extends('layouts.app')

@section('title', 'Vital Signs History - ' . ($patient->demographic->full_name ?? 'Patient'))

@section('header')
<div class="d-flex align-items-center gap-3">
    <i class="bi bi-graph-up text-danger" style="font-size: 2rem;"></i>
    <div>
        <h1 class="h3 mb-1">Vital Signs History</h1>
        <p class="mb-0 text-muted">Patient: <strong>{{ $patient->demographic->full_name ?? 'Unknown' }}</strong></p>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        @if($visits->count() > 0)

            @foreach($visits as $visit)
            <div class="card-body">

@foreach($visit->vitalSignsRequests as $vsRequest)
@foreach($vsRequest->patientVisitVitalSigns as $vs)

<div class="card mb-3 border-start border-4 border-danger shadow-sm">
    <div class="card-body">

        <div class="d-flex justify-content-between mb-3">
            <h6 class="fw-bold text-danger">
                <i class="bi bi-calendar-event me-2"></i>
                {{ $vs->recorded_date->format('M d, Y H:i') }}
            </h6>
            <small class="text-muted">
                Recorded By: <strong>{{ $vs->recordedBy->name ?? 'N/A' }}</strong>
            </small>
        </div>

        <div class="row">

            <!-- Temperature -->
            <div class="col-md-4 mb-3">
                <p class="mb-1"><strong>Body Temperature:</strong></p>
                <p>
                    {{ $vs->body_temperature }} °C
                    @if($vs->body_temperature < 36.5 || $vs->body_temperature > 37.5)
                        <span class="badge bg-warning">Abnormal</span>
                    @else
                        <span class="badge bg-success">Normal</span>
                    @endif
                </p>
            </div>

            <!-- Blood Pressure -->
            <div class="col-md-4 mb-3">
                <p class="mb-1"><strong>Blood Pressure:</strong></p>
                <p>
                    {{ $vs->blood_pressure_systolic }}/{{ $vs->blood_pressure_diastolic }} mmHg
                    @if($vs->blood_pressure_systolic > 140 || $vs->blood_pressure_diastolic > 90)
                        <span class="badge bg-warning">High</span>
                    @else
                        <span class="badge bg-success">Normal</span>
                    @endif
                </p>
            </div>

            <!-- Heart Rate -->
            <div class="col-md-4 mb-3">
                <p class="mb-1"><strong>Heart Rate:</strong></p>
                <p>
                    {{ $vs->heart_rate }} bpm
                    @if($vs->heart_rate < 60 || $vs->heart_rate > 100)
                        <span class="badge bg-warning">Abnormal</span>
                    @else
                        <span class="badge bg-success">Normal</span>
                    @endif
                </p>
            </div>

            <!-- Respiratory Rate -->
            <div class="col-md-4 mb-3">
                <p class="mb-1"><strong>Respiratory Rate:</strong></p>
                <p>
                    {{ $vs->respiratory_rate }} /min
                    @if($vs->respiratory_rate < 12 || $vs->respiratory_rate > 20)
                        <span class="badge bg-warning">Abnormal</span>
                    @else
                        <span class="badge bg-success">Normal</span>
                    @endif
                </p>
            </div>

            <!-- Oxygen Saturation -->
            <div class="col-md-4 mb-3">
                <p class="mb-1"><strong>O₂ Saturation:</strong></p>
                <p>
                    {{ $vs->oxygen_saturation }} %
                    @if($vs->oxygen_saturation < 95)
                        <span class="badge bg-danger">Low</span>
                    @else
                        <span class="badge bg-success">Normal</span>
                    @endif
                </p>
            </div>

            <!-- Blood Glucose -->
            <div class="col-md-4 mb-3">
                <p class="mb-1"><strong>Blood Glucose:</strong></p>
                <p>
                    @if($vs->blood_glucose)
                        {{ $vs->blood_glucose }} mg/dL
                        @if($vs->blood_glucose > 126)
                            <span class="badge bg-warning">High</span>
                        @else
                            <span class="badge bg-success">Normal</span>
                        @endif
                    @else
                        <span class="text-muted">Not Recorded</span>
                    @endif
                </p>
            </div>

        </div>

        <div class="text-end">
            <a href="#" class="btn btn-sm btn-outline-danger"
                data-bs-toggle="modal"
                data-bs-target="#vitalSignsModal"
                onclick="showVitalSignsDetails({{ $vs->id }})">
                <i class="bi bi-eye"></i> View Full Details
            </a>
        </div>

    </div>
</div>

@endforeach
@endforeach

</div>

            @endforeach
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No Vital Signs Recorded</h5>
                    <p class="text-muted">No vital signs have been recorded for this patient yet.</p> <a href="{{ route('nurse.patients.show', $patient) }}" class="btn btn-outline-primary mt-2"> <i class="bi bi-arrow-left me-2"></i>Back to Patient Details </a> </div> </div> @endif </div> </div>
                 </div>
            </div>
    </div>
</div>


<!-- Details Modal -->
<div class="modal fade" id="vitalSignsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-heart-pulse me-2"></i>Vital Signs Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modal-body">
                Loading...
            </div>
        </div>
    </div>
</div>

<script>
    function showVitalSignsDetails(id) {
        // Simple placeholder - in production you'd fetch the details via AJAX
        document.getElementById('modal-body').innerHTML = 'Vital signs details would load here.';
    }
</script>
@endsection
