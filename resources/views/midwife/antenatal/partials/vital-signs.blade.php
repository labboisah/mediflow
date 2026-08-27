<!-- Vital Signs -->
@if($antenatalCare->blood_pressure || $antenatalCare->weight || $antenatalCare->height)
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-thermometer"></i> Vital Signs</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                @if($antenatalCare->blood_pressure)
                    <div class="col-md-4">
                        <label class="form-label text-muted">Blood Pressure</label>
                        <p class="fs-6">{{ $antenatalCare->blood_pressure }} mmHg</p>
                    </div>
                @endif
                @if($antenatalCare->weight)
                    <div class="col-md-4">
                        <label class="form-label text-muted">Weight</label>
                        <p class="fs-6">{{ $antenatalCare->weight }} kg</p>
                    </div>
                @endif
                @if($antenatalCare->height)
                    <div class="col-md-4">
                        <label class="form-label text-muted">Height</label>
                        <p class="fs-6">{{ $antenatalCare->height }} cm</p>
                    </div>
                @endif
            </div>
            @if($antenatalCare->weight && $antenatalCare->height)
                <div class="alert alert-info small">
                    <strong>BMI:</strong> {{ $antenatalCare->getBmi() }} kg/m²
                </div>
            @endif
        </div>
    </div>
@endif