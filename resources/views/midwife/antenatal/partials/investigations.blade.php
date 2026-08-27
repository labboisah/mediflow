<!-- Investigations -->
@if($antenatalCare->urine_analysis || $antenatalCare->blood_tests || $antenatalCare->ultrasound_findings)
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Investigations</h5>
        </div>
        <div class="card-body">
            @if($antenatalCare->urine_analysis)
                <div class="mb-3">
                    <label class="form-label text-muted">Urine Analysis</label>
                    <p>{{ $antenatalCare->urine_analysis }}</p>
                </div>
            @endif
            @if($antenatalCare->blood_tests)
                <div class="mb-3">
                    <label class="form-label text-muted">Blood Tests</label>
                    <p>{{ $antenatalCare->blood_tests }}</p>
                </div>
            @endif
            @if($antenatalCare->ultrasound_findings)
                <div class="mb-3">
                    <label class="form-label text-muted">Ultrasound Findings</label>
                    <p>{{ $antenatalCare->ultrasound_findings }}</p>
                </div>
            @endif
        </div>
    </div>
@endif