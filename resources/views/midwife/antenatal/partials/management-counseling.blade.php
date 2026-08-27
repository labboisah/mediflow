<!-- Management & Counseling -->
@if($antenatalCare->management_plan || $antenatalCare->counseling_topics)
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-chat-dots"></i> Management & Counseling</h5>
        </div>
        <div class="card-body">
            @if($antenatalCare->management_plan)
                <div class="mb-3">
                    <label class="form-label text-muted">Management Plan</label>
                    <p>{{ $antenatalCare->management_plan }}</p>
                </div>
            @endif
            @if($antenatalCare->counseling_topics)
                <div class="mb-3">
                    <label class="form-label text-muted">Counseling Topics</label>
                    <p>{{ $antenatalCare->counseling_topics }}</p>
                </div>
            @endif
            @if($antenatalCare->took_supplements)
                <div class="alert alert-success small">
                    <i class="bi bi-check-circle"></i> Patient is taking supplements (Iron, Folic Acid, Vitamins)
                </div>
            @endif
        </div>
    </div>
@endif