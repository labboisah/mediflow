@extends('layouts.app')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 d-flex align-items-center mb-0">
            <i class="bi bi-eyedropper me-2 text-primary"></i>
            Send Investigation Result
        </h1>
    </div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow p-4">
                <form action="{{ route('radiology.requests.storeResult', $investigationRequest) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @foreach($investigationRequest->investigation->parameters as $parameter)
                    <div class="mb-3">
                        <label for="parameter_{{ $parameter->id }}" class="form-label">{{ $parameter->name }}</label>
                        <textarea rows="5" name="parameters[{{ $parameter->id }}]" id="parameter_{{ $parameter->id }}" placeholder="Enter result for {{ $parameter->name }} in {{ $parameter->unit }}" class="form-control">{{ old('parameters.' . $parameter->id) }}</textarea>
                    </div>
                    @endforeach

                    <div class="mb-3">
                        <label for="result_image" class="form-label">Radiology Image</label>
                        <input type="file" name="result_image" id="result_image" class="form-control" accept="image/*">
                        <div class="form-text">Upload an optional radiology image to attach to this request result.</div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-2"></i> Submit Result</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection