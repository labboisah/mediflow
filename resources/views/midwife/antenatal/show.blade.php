@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-0">Antenatal Care Record</h1>
            <p class="text-muted">
                {{ $antenatalCare->patient->demographic->first_name }} 
                {{ $antenatalCare->patient->demographic->last_name }} 
                | Hospital #{{ $antenatalCare->patient->hospital_number }}
            </p>
        </div>
        <div class="col-md-4 text-end">
            @if(auth()->user()->hasAnyRole(['midwife', 'administrator']))
                <a href="{{ route('midwife.antenatal.edit', $antenatalCare) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('midwife.antenatal.patient-records', $antenatalCare->patient) }}" class="btn btn-secondary">
                    <i class="bi bi-chevron-left"></i> Back
                </a>
            @endif
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            @include('midwife.antenatal.partials.patient-information')
            @include('midwife.antenatal.partials.pregnancy-details')
            @include('midwife.antenatal.partials.vital-signs')
            @include('midwife.antenatal.partials.physical-examination')
            @include('midwife.antenatal.partials.investigations')
            @include('midwife.antenatal.partials.risk-assessment')
            @include('midwife.antenatal.partials.management-counseling')
            @include('midwife.antenatal.partials.clinical-notes')
        </div>

        <div class="col-lg-4">
            @include('midwife.antenatal.partials.record-metadata')
        </div>
    </div>
</div>
@endsection
