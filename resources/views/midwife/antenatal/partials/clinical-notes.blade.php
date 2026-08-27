<!-- Clinical Notes -->
@if($antenatalCare->clinical_notes)
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-file-text"></i> Clinical Notes</h5>
        </div>
        <div class="card-body">
            <p>{{ $antenatalCare->clinical_notes }}</p>
        </div>
    </div>
@endif