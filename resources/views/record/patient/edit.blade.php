@extends('layouts.app')

@section('title', 'Edit Patient')

@section('header')
<div class="d-flex align-items-center gap-3">
    <i class="bi bi-pencil-square text-success" style="font-size: 2rem;"></i>
    <div>
        <h1 class="h3 mb-1">Edit Patient Information</h1>
        <p class="mb-0 text-muted">Update details for: <strong>{{ $patient->demographic->full_name ?? 'Patient' }}</strong></p>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-person-vcard me-2"></i>Patient Demographics</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('record.patients.update', $patient->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" value="{{ $patient->demographic->first_name }}" required>
                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" name="last_name" value="{{ $patient->demographic->last_name }}" required>
                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                <option value="Male" {{ $patient->demographic->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $patient->demographic->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ $patient->demographic->gender == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   id="date_of_birth" name="date_of_birth" value="{{ $patient->demographic->date_of_birth->format('Y-m-d') }}" required>
                            @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="state" class="form-label">State of Origin</label>
                            <select class="form-select @error('state') is-invalid @enderror" id="state" name="state">
                                <option value="{{$patient->demographic->lga->state->id ?? ''}}">{{$patient->demographic->lga->state->name ?? 'Select State'}}</option>
                                @foreach(App\Models\State::all() as $state)
                                <option value="{{$state->id}}" {{ old('state') == $state->name ? 'selected' : '' }}>{{$state->name}}</option>
                                @endforeach
                            </select>
                            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="lga" class="form-label">Local Government of Origin</label>
                            <select class="form-select @error('lga') is-invalid @enderror" id="lga" name="lga">
                                <option value="{{$patient->demographic->lga->id ?? ''}}">{{$patient->demographic->lga->id ?? 'Select LGA'}}</option>
                                
                            </select>
                            @error('lga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="occupation" class="form-label">Occupation</label>
                            <input type="text" class="form-control @error('occupation') is-invalid @enderror" 
                                   id="occupation" name="occupation" value="{{ $patient->demographic->occupation }}">
                            @error('occupation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="marital_status" class="form-label">Marital Status</label>
                            <select class="form-select @error('marital_status') is-invalid @enderror" id="marital_status" name="marital_status">
                                <option value="">Select Status</option>
                                <option value="Single" {{ $patient->demographic->marital_status == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ $patient->demographic->marital_status == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Divorced" {{ $patient->demographic->marital_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="Widowed" {{ $patient->demographic->marital_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                            @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                   id="phone_number" name="phone_number" value="{{ $patient->demographic->phone_number }}" required>
                            @error('phone_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ $patient->demographic->email }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3">{{ $patient->demographic->address }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-4 text-success"><i class="bi bi-people-fill me-2"></i>Next of Kin</h5>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="nok_name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('nok_name') is-invalid @enderror" 
                                   id="nok_name" name="nok_name" value="{{ $patient->nextOfKin->name }}" required>
                            @error('nok_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nok_relationship" class="form-label">Relationship</label>
                            <input type="text" class="form-control @error('nok_relationship') is-invalid @enderror" 
                                   id="nok_relationship" name="nok_relationship" value="{{ $patient->nextOfKin->relationship }}" required>
                            @error('nok_relationship')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="nok_telephone" class="form-label">Telephone</label>
                            <input type="tel" class="form-control @error('nok_telephone') is-invalid @enderror" 
                                   id="nok_telephone" name="nok_telephone" value="{{ $patient->nextOfKin->telephone }}" required>
                            @error('nok_telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nok_contact_address" class="form-label">Contact Address</label>
                            <input type="text" class="form-control @error('nok_contact_address') is-invalid @enderror" 
                                   id="nok_contact_address" name="nok_contact_address" value="{{ $patient->nextOfKin->contact_address }}">
                            @error('nok_contact_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="bi bi-check-circle me-2"></i>Save Changes
                        </button>
                        <a href="{{ route('record.patients.show', $patient->id) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
