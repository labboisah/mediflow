

<div class="container-fluid">


<!-- Search Card -->
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-flask"></i>
                    Laboratory Result Entry
                </h5>
            </div>

            <div class="card-body">

                <label class="form-label">

                    Bill Number

                </label>

                <input type="text" class="form-control" wire:model="bill_number" wire:keydown.enter="search" placeholder="Enter Bill Number">


            </div>
            <div class="col-md-4 d-flex align-items-end"> 
                <button class="btn btn-success w-100" wire:click="search" wire:loading.attr="disabled"> 
                    <span wire:loading.remove wire:target="search"> 
                        <i class="bi bi-search"></i> Search 
                    </span> 
                    <span wire:loading wire:target="search"> 
                        <span class="spinner-border spinner-border-sm me-2"> 

                        </span> 
                        Searching... 
                    </span> 
                </button> 
            </div>
        </div>
    </div>
</div>
<!-- Loading -->

<div wire:loading wire:target="search">

    <div class="alert alert-info"> 
        <div class="spinner-border spinner-border-sm me-2"> 

        </div> Searching investigation requests... 
    </div>

</div>

<!-- Bill Information -->

@if($bill)

<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-primary text-white">

        Investigation Summary

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-3">

                <strong>Bill Number</strong>

                <p>{{ $bill->bill_number }}</p>

            </div>

            <div class="col-md-3">

                <strong>Total Investigations</strong>

                <p>{{ count($requests) }}</p>

            </div>

            <div class="col-md-4">

                <strong>Patient Information</strong>

                @if($patient)

                    <p class="mb-1">

                        <strong>Name:</strong>

                        {{ $patient->name() }}

                    </p>

                    <p class="mb-1">

                        <strong>Gender:</strong>

                        {{ $patient->demographic->gender }}

                    </p>

                    <p class="mb-1">

                        <strong>Phone:</strong>

                        {{ $patient->demographic->phone_number }}

                    </p>

                @elseif($walkin)

                    <p class="mb-1">

                        <strong>Name:</strong>

                        {{ $walkin->name }}

                    </p>

                    <p class="mb-1">

                        <strong>Phone:</strong>

                        {{ $walkin->phone_number }}

                    </p>

                    <p class="mb-1">

                        <strong>Address:</strong>

                        {{ $walkin->address }}

                    </p>

                @endif

            </div>

            <div class="col-md-2 text-end">

                @if($loaded)

                <a href="{{ route('lab.requests.show', $bill) }}"
                   target="_blank"
                   class="btn btn-primary">

                    <i class="bi bi-printer"></i>

                    Print Report

                </a>

                @endif

            </div>

        </div>

    </div>

</div>

@endif

<!-- Investigations -->

@if($loaded && count($requests))

    @foreach($requests as $request)

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">

                        {{ $request->investigation->name }}

                    </h5>

                    <small class="text-muted">

                        Requested:

                        {{ $request->created_at->format('d M Y h:i A') }}

                    </small>

                </div>

                <div>

                    @if($request->status == 'Completed')

                        <span class="badge bg-success">

                            Completed

                        </span>

                    @elseif($request->status == 'Pending')

                        <span class="badge bg-warning text-dark">

                            Pending

                        </span>

                    @else

                        <span class="badge bg-secondary">

                            {{ $request->status }}

                        </span>

                    @endif

                </div>

            </div>

        </div>

        <div class="card-body">

            <!-- Investigation Details -->

            <div class="row mb-4">

                <div class="col-md-3">

                    <label class="form-label">

                        Lab Number

                    </label>

                    <input
                        type="text"
                        class="form-control"
                        wire:model="labNumbers.{{ $request->id }}">

                </div>

                <div class="col-md-3">

                    <label class="form-label">

                        Specimen

                    </label>

                    <div>

                        {{ $request->specimen }}

                    </div>

                </div>

                <div class="col-md-3">

                    <label class="form-label">

                        Requested By


                    </label>


                    <div>

                        {{ $request->requestedBy->name ?? 'N/A' }}

                    </div>

                </div>

                <div class="col-md-3">

                    <label class="form-label">

                        Performed By

                    </label>

                    <div>

                        {{ $request->performedBy->name ?? 'N/A' }}

                    </div>

                </div>

            </div>

            <div class="mb-4">

                <label class="form-label">

                    Clinical Diagnosis

                </label>

                <div class="alert alert-light">

                    {{ $request->clinical_diagnoses }}

                </div>

            </div>

            <!-- Parameters -->

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>Parameter</th>

                            <th>Unit</th>

                            <th>Reference Range</th>

                            <th width="250">

                                Result Value

                            </th>

                            <th width="120">

                                Action

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach(
                        $request->investigation->parameters
                        as $parameter
                    )

                        <tr>

                            <td>

                                {{ $parameter->name }}

                            </td>

                            <td>

                                {{ $parameter->unit }}

                            </td>

                            <td>

                                {{ $parameter->reference_range }}

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control"
                                    wire:model.defer="results.{{ $request->id }}.{{ $parameter->id }}">

                            </td>

                            <td>

                                <button
                                    class="btn btn-sm btn-danger"
                                    wire:click="
                                    deleteResult(
                                    {{ $request->id }},
                                    {{ $parameter->id }}
                                    )">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

            <div class="text-end mt-3">

                <button
                    class="btn btn-success"
                    wire:click="
                    saveInvestigation(
                    {{ $request->id }}
                    )">

                    <i class="bi bi-save"></i>

                    Save Result

                </button>

            </div>

        </div>

    </div>

    @endforeach

@endif

<!-- No Records -->

@if($loaded && count($requests) == 0)

    <div class="alert alert-warning">

        No investigation request found for this Bill Number.

    </div>

@endif


</div>

