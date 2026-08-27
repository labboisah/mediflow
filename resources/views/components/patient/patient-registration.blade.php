<div>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-person-plus-fill text-success" style="font-size: 2rem;"></i>
            <div>
                <h1 class="h3 mb-1">Register New Patient</h1>
                <p class="mb-0 text-muted">Capture patient details, open a visit, and generate the registration bill.</p>
            </div>
        </div>

        <a href="{{ route('record.patients.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Back to Patients
        </a>
    </div>

    <form wire:submit="save">
        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-person-vcard me-2"></i>Patient Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">File Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('fileType') is-invalid @enderror" wire:model.live="fileType">
                                    <option value="">Select File Type</option>
                                    @foreach($fileTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }} (&#8358;{{ number_format($type->price, 2) }})</option>
                                    @endforeach
                                </select>
                                @error('fileType')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label d-block">ANC Patient</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="ancPatient" wire:model.live="anc">
                                    <label class="form-check-label" for="ancPatient">Mark ANC</label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label d-block">Walk-in</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="walkInPatient" wire:model.live="isWalkIn">
                                    <label class="form-check-label" for="walkInPatient">Walk-in file</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Discount (%)</label>
                                <select class="form-select @error('discount') is-invalid @enderror" wire:model.live="discount">
                                    @for($i = 0; $i <= 100; $i++)
                                        <option value="{{ $i }}">{{ $i }}%</option>
                                    @endfor
                                </select>
                                @error('discount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('firstName') is-invalid @enderror" wire:model.blur="firstName" placeholder="Patient first name">
                                @error('firstName')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('lastName') is-invalid @enderror" wire:model.blur="lastName" placeholder="Patient last name">
                                @error('lastName')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select @error('gender') is-invalid @enderror" wire:model.live="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('dateOfBirth') is-invalid @enderror" wire:model.live="dateOfBirth">
                                @error('dateOfBirth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Estimated Age</label>
                                <input type="text" class="form-control" value="{{ $estimatedAge !== null ? $estimatedAge . ' years' : 'Select date of birth' }}" disabled>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">State of Origin</label>
                                <select class="form-select" wire:model.live="stateId">
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Local Government</label>
                                <select class="form-select @error('lga') is-invalid @enderror" wire:model.live="lga" @disabled($stateId === '')>
                                    <option value="">Select LGA</option>
                                    @foreach($lgas as $localGovernment)
                                        <option value="{{ $localGovernment->id }}">{{ $localGovernment->name }}</option>
                                    @endforeach
                                </select>
                                @error('lga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Occupation</label>
                                <input type="text" class="form-control @error('occupation') is-invalid @enderror" wire:model.blur="occupation" placeholder="Patient occupation">
                                @error('occupation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Marital Status</label>
                                <select class="form-select @error('maritalStatus') is-invalid @enderror" wire:model.live="maritalStatus">
                                    <option value="">Select Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                                @error('maritalStatus')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phoneNumber') is-invalid @enderror" wire:model.live.debounce.500ms="phoneNumber">
                                @error('phoneNumber')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model.blur="email" placeholder="patient@example.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" rows="3" wire:model.blur="address" placeholder="Full address"></textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-success"><i class="bi bi-people-fill me-2"></i>Next of Kin Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nokName') is-invalid @enderror" wire:model.blur="nokName">
                                @error('nokName')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Relationship <span class="text-danger">*</span></label>
                                <select class="form-select @error('nokRelationship') is-invalid @enderror" wire:model.live="nokRelationship">
                                    <option value="">Select Relationship</option>
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Husband">Husband</option>
                                    <option value="Wife">Wife</option>
                                    <option value="Brother">Brother</option>
                                    <option value="Sister">Sister</option>
                                    <option value="Son">Son</option>
                                    <option value="Daughter">Daughter</option>
                                    <option value="Guardian">Guardian</option>
                                </select>
                                @error('nokRelationship')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Telephone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('nokTelephone') is-invalid @enderror" wire:model.blur="nokTelephone">
                                @error('nokTelephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Contact Address</label>
                                <input type="text" class="form-control @error('nokContactAddress') is-invalid @enderror" wire:model.blur="nokContactAddress">
                                @error('nokContactAddress')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Billing Preview</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">File Type</span>
                            <strong>{{ $selectedFileType->name ?? 'Not selected' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Base Fee</span>
                            <strong>&#8358;{{ number_format($selectedFileType->price ?? 0, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Discount</span>
                            <strong>{{ $discount }}%</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Consultation</span>
                            <strong>&#8358;{{ number_format($anc ? 500 : 1000, 2) }}</strong>
                        </div>
                        <p class="text-muted small mt-3 mb-0">Final bills are generated by the existing file-opening logic after registration.</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Registration Flow</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3"><i class="bi bi-check-circle text-success me-2"></i>Hospital number is auto-generated.</li>
                            <li class="mb-3"><i class="bi bi-check-circle text-success me-2"></i>Age is calculated from date of birth.</li>
                            <li class="mb-3"><i class="bi bi-check-circle text-success me-2"></i>Visit and activity records are created.</li>
                            <li class="mb-3"><i class="bi bi-check-circle text-success me-2"></i>File opening and consultation bills are generated.</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Errors are shown before saving incomplete records.</li>
                        </ul>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-success btn-lg" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">
                            <i class="bi bi-check-circle me-2"></i>
                            Register Patient
                        </span>
                        <span wire:loading wire:target="save">
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Registering...
                        </span>
                    </button>
                    <a href="{{ route('record.patients.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
