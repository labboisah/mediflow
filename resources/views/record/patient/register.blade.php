@extends('layouts.app')

@section('title', 'Register Patient')

@section('header')
<div class="d-flex align-items-center gap-3">
    <i class="bi bi-person-plus-fill text-success" style="font-size: 2rem;"></i>
    <div>
        <h1 class="h3 mb-1">Register New Patient</h1>
        <p class="mb-0 text-muted">Fill in the patient's information to register them in the system</p>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-person-vcard me-2"></i>Patient Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('record.patients.register') }}" method="POST">
                    @csrf

                    <div class="row mb-4">
                        <!-- select file type individualor family -->
                        <div class="col-md-6">
                            <label for="file_type" class="form-label">File Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('file_type') is-invalid @enderror" id="file_type" name="file_type" required>
                                <option value="">Select File Type</option>
                                @foreach(App\Models\FileType::all() as $fileType)
                                    <option value="{{ $fileType->id }}" {{ old('file_type') == $fileType->id ? 'selected' : '' }}>
                                        {{ $fileType->name }} (₦{{ number_format($fileType->price, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('file_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <!-- service applyingfor -->
                        <div class="col-md-3">
                            <label for="service" class="form-label">Mark as ANC Patient<span class="text-danger"></span></label>
                            <input type="checkbox" name="anc" id="anc" value="1" {{ old('anc') ? 'checked' : '' }} class="form-check-input ms-2">
                        </div>
                        <!-- discount on registration -->
                        <div class="col-md-3">
                            <label for="discount" class="form-label">Discount (%)</label>
                            <select class="form-select @error('discount') is-invalid @enderror" id="discount" name="discount">
                                @for($i = 0; $i <= 100; $i++)
                                    <option value="{{ $i }}" {{ old('discount', 0) == $i ? 'selected' : '' }}>{{ $i }}%</option>
                                @endfor
                            </select>
                            @error('discount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" value="{{ old('first_name') }}" 
                                   placeholder="Patient First Name Eg: Isah" required>
                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" name="last_name" value="{{ old('last_name') }}" 
                                   placeholder="Patient Last Name Eg: Labbo" required>
                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        
                        <div class="col-md-6">
                            <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                            @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="state" class="form-label">State of Origin</label>
                            <select class="form-select @error('state') is-invalid @enderror" id="state" name="state">
                                <option value="">Select State</option>
                                @foreach(App\Models\State::all() as $state)
                                <option value="{{$state->id}}" {{ old('state') == $state->name ? 'selected' : '' }}>{{$state->name}}</option>
                                @endforeach
                            </select>
                            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="lga" class="form-label">Local Government of Origin</label>
                            <select class="form-select @error('lga') is-invalid @enderror" id="lga" name="lga">
                                <option value="">Select LGA</option>
                                
                            </select>
                            @error('lga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="occupation" class="form-label">Occupation</label>
                            <input type="text" class="form-control @error('occupation') is-invalid @enderror" 
                                   id="occupation" name="occupation" value="{{ old('occupation') }}" placeholder="Patient's occupation">
                            @error('occupation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="marital_status" class="form-label">Marital Status</label>
                            <select class="form-select @error('marital_status') is-invalid @enderror" id="marital_status" name="marital_status">
                                <option value="">Select Status</option>
                                <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="Widowed" {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                            @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                   id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                            @error('phone_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" placeholder="patient@example.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" placeholder="Full address">{{ old('address') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-4 text-success"><i class="bi bi-people-fill me-2"></i>Next of Kin Information</h5>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="nok_name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nok_name') is-invalid @enderror" 
                                   id="nok_name" name="nok_name" value="{{ old('nok_name') }}" required>
                            @error('nok_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="nok_relationship" class="form-label">Relationship <span class="text-danger">*</span></label>
                            <select class="form-select @error('nok_relationship') is-invalid @enderror" id="nok_relationship" name="nok_relationship">
                                <option value="">Select Relationship</option>
                                <option value="Father" {{ old('nok_relationship') == 'Father' ? 'selected' : '' }}>Father</option>
                                <option value="Mother" {{ old('nok_relationship') == 'Mother' ? 'selected' : '' }}>Mother</option>
                                <option value="Husband" {{ old('nok_relationship') == 'Husband' ? 'selected' : '' }}>Husband</option>
                                <option value="Wife" {{ old('nok_relationship') == 'Wife' ? 'selected' : '' }}>Wife</option>
                                <option value="Brother" {{ old('nok_relationship') == 'Brother' ? 'selected' : '' }}>Brother</option>
                                <option value="Sister" {{ old('nok_relationship') == 'Sister' ? 'selected' : '' }}>Sister</option>
                                <option value="Son" {{ old('nok_relationship') == 'Son' ? 'selected' : '' }}>Son</option>
                                <option value="Daughter" {{ old('nok_relationship') == 'Daughter' ? 'selected' : '' }}>Daughter</option>
                            </select>
                            @error('nok_relationship')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="nok_telephone" class="form-label">Telephone <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('nok_telephone') is-invalid @enderror" 
                                   id="nok_telephone" name="nok_telephone" value="{{ old('nok_telephone') }}" required>
                            @error('nok_telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nok_contact_address" class="form-label">Contact Address</label>
                            <input type="text" class="form-control @error('nok_contact_address') is-invalid @enderror" 
                                   id="nok_contact_address" name="nok_contact_address" value="{{ old('nok_contact_address') }}">
                            @error('nok_contact_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="bi bi-check-circle me-2"></i>Register Patient
                        </button>
                        <a href="{{ route('record.patients.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Instructions</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <strong>Required Fields:</strong> Marked with <span class="text-danger">*</span>
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-person-check text-success me-2"></i>
                        <strong>Hospital Number</strong> will be auto-generated
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-calendar3 text-success me-2"></i>
                        <strong>Age</strong> calculated from date of birth
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-phone text-success me-2"></i>
                        <strong>Contact Details</strong> for patient communication
                    </li>
                    <li>
                        <i class="bi bi-people text-success me-2"></i>
                        <strong>Next of Kin</strong> for emergency contact
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@push('vite')
    @vite('resources/js/address.js')
@endpush
@endsection
