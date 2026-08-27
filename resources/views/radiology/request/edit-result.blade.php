@extends('layouts.app')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 d-flex align-items-center mb-0">
            <i class="bi bi-pencil-square me-2 text-primary"></i>
            Edit Investigation Result
        </h1>
    </div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow p-4">
                <form action="{{ route('radiology.requests.updateResult', $investigationRequest) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @foreach($investigationRequest->investigation->parameters as $parameter)
                    @php
                        $existing = $investigationRequest->investigationResults->firstWhere('parameter_id', $parameter->id);
                    @endphp
                    <div class="mb-3">
                        <label for="parameter_{{ $parameter->id }}" class="form-label">{{ $parameter->name }}</label>
                        <textarea name="parameters[{{ $parameter->id }}]" id="parameter_{{ $parameter->id }}" class="form-control" placeholder="Enter result for {{ $parameter->name }} in {{ $parameter->unit }}">{{ old('parameters.' . $parameter->id, $existing->value ?? '') }}</textarea>
                    </div>
                    @endforeach

                    <div class="mb-3">
                        <label for="result_image" class="form-label">Radiology Image</label>
                        <input type="file" name="result_image" id="result_image" class="form-control" accept="image/*">
                        <div class="form-text">Upload to replace existing image.</div>
                    </div>

                    @if($investigationRequest->result_image)
                        <div class="mb-3">
                            <h6>Current Image</h6>
                            <a href="{{ asset('storage/' . $investigationRequest->result_image) }}" target="_blank">
                                <img src="{{ asset('storage/' . $investigationRequest->result_image) }}" class="img-fluid border" style="max-width:400px;" alt="Radiology Image">
                            </a>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                                <label class="form-check-label" for="remove_image">Remove current image</label>
                            </div>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i> Update Result</button>
                    <a href="{{ route('radiology.requests.show', $investigationRequest) }}" class="btn btn-secondary ms-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
