<!-- Physical Examination -->
@if($antenatalCare->abdominal_examination || $antenatalCare->fundal_height || $antenatalCare->fetal_heart_rate)
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Physical Examination</h5>
        </div>
        <div class="card-body">
            @if($antenatalCare->abdominal_examination)
                <div class="mb-3">
                    <label class="form-label text-muted">Abdominal Examination</label>
                    <p>{{ $antenatalCare->abdominal_examination }}</p>
                </div>
            @endif
            <div class="row mb-3">
                @if($antenatalCare->fundal_height)
                    <div class="col-md-6">
                        <label class="form-label text-muted">Fundal Height</label>
                        <p class="fs-6">{{ $antenatalCare->fundal_height }} cm</p>
                    </div>
                @endif
                @if($antenatalCare->fetal_heart_rate)
                    <div class="col-md-6">
                        <label class="form-label text-muted">Fetal Heart Rate</label>
                        <p class="fs-6">{{ $antenatalCare->fetal_heart_rate }} bpm</p>
                    </div>
                @endif
            </div>
            @if($antenatalCare->fetal_movement)
                <div class="mb-3">
                    <label class="form-label text-muted">Fetal Movement</label>
                    <p>{{ $antenatalCare->fetal_movement }}</p>
                </div>
            @endif
            @if($antenatalCare->vaginal_examination)
                <div class="mb-3">
                    <label class="form-label text-muted">Vaginal Examination</label>
                    <p>{{ $antenatalCare->vaginal_examination }}</p>
                </div>
            @endif
        </div>
    </div>
@endif