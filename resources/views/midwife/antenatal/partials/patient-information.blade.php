<!-- Patient Summary -->
<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Patient Information</h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label text-muted">Hospital Number</label>
                <p class="fs-6">{{ $antenatalCare->patient->hospital_number }}</p>
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted">Patient Name</label>
                <p class="fs-6">{{ $antenatalCare->patient->demographic->first_name }} {{ $antenatalCare->patient->demographic->last_name }}</p>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label text-muted">Age</label>
                <p class="fs-6">{{ $antenatalCare->patient->age() }} years</p>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted">Gender</label>
                <p class="fs-6">{{ $antenatalCare->patient->demographic->gender }}</p>
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted">Contact</label>
                <p class="fs-6">{{ $antenatalCare->patient->demographic->phone }}</p>
            </div>
        </div>
    </div>
</div>