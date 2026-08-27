<!-- Risk Assessment -->
@if($antenatalCare->risk_factors || $antenatalCare->complications || $antenatalCare->status)
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Risk Assessment</h5>
        </div>
        <div class="card-body">
            @if($antenatalCare->status)
                <div class="mb-3">
                    <label class="form-label text-muted">Overall Status</label>
                    <p>
                        @switch($antenatalCare->status)
                            @case('normal')
                                <span class="badge bg-success fs-6">Normal</span>
                                @break
                            @case('complicated')
                                <span class="badge bg-warning fs-6">Complicated</span>
                                @break
                            @case('high_risk')
                                <span class="badge bg-danger fs-6">High Risk</span>
                                @break
                        @endswitch
                    </p>
                </div>
            @endif
            @if($antenatalCare->risk_factors)
                <div class="mb-3">
                    <label class="form-label text-muted">Risk Factors</label>
                    <p>{{ $antenatalCare->risk_factors }}</p>
                </div>
            @endif
            @if($antenatalCare->complications)
                <div class="mb-3">
                    <label class="form-label text-muted">Complications</label>
                    <p>{{ $antenatalCare->complications }}</p>
                </div>
            @endif
        </div>
    </div>
@endif