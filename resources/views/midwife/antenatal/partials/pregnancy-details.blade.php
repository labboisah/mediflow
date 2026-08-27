<!-- Pregnancy Details -->
@if($antenatalCare->gestational_weeks || $antenatalCare->last_menstrual_period)
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-heart-pulse"></i> Pregnancy Details</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                @if($antenatalCare->last_menstrual_period)
                    <div class="col-md-6">
                        <label class="form-label text-muted">Last Menstrual Period</label>
                        <p class="fs-6">{{ $antenatalCare->last_menstrual_period->format('M d, Y') }}</p>
                    </div>
                @endif
                @if($antenatalCare->expected_delivery_date)
                    <div class="col-md-6">
                        <label class="form-label text-muted">Expected Delivery Date</label>
                        <p class="fs-6">
                            {{ $antenatalCare->expected_delivery_date->format('M d, Y') }}
                            @if($antenatalCare->isOverdue())
                                <span class="badge bg-danger ms-2">OVERDUE</span>
                            @endif
                        </p>
                    </div>
                @endif
            </div>
            <div class="row mb-3">
                @if($antenatalCare->gestational_weeks)
                    <div class="col-md-4">
                        <label class="form-label text-muted">Gestational Weeks</label>
                        <p class="fs-6"><strong>{{ $antenatalCare->gestational_weeks }} weeks</strong></p>
                    </div>
                @endif
                @if($antenatalCare->number_of_fetuses)
                    <div class="col-md-4">
                        <label class="form-label text-muted">Number of Fetuses</label>
                        <p class="fs-6">{{ $antenatalCare->number_of_fetuses }}</p>
                    </div>
                @endif
                @if($antenatalCare->pregnancy_type)
                    <div class="col-md-4">
                        <label class="form-label text-muted">Pregnancy Type</label>
                        <p class="fs-6">{{ $antenatalCare->pregnancy_type }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif