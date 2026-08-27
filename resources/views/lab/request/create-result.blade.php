@extends('layouts.app')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 d-flex align-items-center mb-0">
            <i class="bi bi-eyedropper me-2 text-primary"></i>
            Send Combined Investigation Results
        </h1>
    </div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow p-4">
                <div class="mb-4">
                    <h5>Patient: <strong>{{ $patientName }}</strong></h5>
                    @if($hospitalNumber)
                        <p class="mb-0">Hospital Number: <strong>{{ $hospitalNumber }}</strong></p>
                    @else
                        <p class="mb-0">Walk-in Patient</p>
                    @endif
                </div>

                <form action="{{ route('lab.requests.results.store', ['groupType' => $groupType, 'groupId' => $groupId]) }}" method="POST">
                    @csrf

                    @foreach($investigationRequests as $investigationRequest)
                        <div class="mb-4 border rounded p-3">
                            <h6 class="fw-bold">{{ $investigationRequest->investigation->name }}</h6>
                            <p class="text-muted mb-3">Requested on: {{ $investigationRequest->created_at->format('d M Y, h:i A') }}</p>

                            @foreach($investigationRequest->investigation->parameters as $parameter)
                                <div class="mb-3">
                                    <label for="parameter_{{ $investigationRequest->id }}_{{ $parameter->id }}" class="form-label">
                                        {{ $parameter->name }}
                                    </label>
                                    <input type="text"
                                        class="form-control"
                                        id="parameter_{{ $investigationRequest->id }}_{{ $parameter->id }}"
                                        name="parameters[{{ $investigationRequest->id }}][{{ $parameter->id }}]"
                                        value="{{ old('parameters.' . $investigationRequest->id . '.' . $parameter->id) }}"
                                        placeholder="Enter result for {{ $parameter->name }} - {{ $parameter->unit }}">
                                    <small class="text-muted">Reference Range: {{ $parameter->reference_range }}</small>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i> Submit Combined Results
                    </button>
                    <a href="{{ route('lab.requests.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
