@extends('layouts.app')

@section('title', 'Patient Details - ' . ($patient->demographic->full_name ?? 'Unknown'))

@section('header')
<div class="d-flex align-items-center gap-3">
    
    <div class="col-md-5">
        <h1 class="h3 mb-1"><i class="bi bi-person-vcard text-success" style="font-size: 2rem;"></i> {{ $patient->demographic->full_name ?? 'Patient Details' }}</h1>
        <p class="mb-0 text-muted">
            Hospital Number:
            <strong class="text-success">{{ $patient->hospital_number }}</strong>
        </p>
        @if($patient->currentVisit())
        <p class="mb-0 text-muted">
           Registered At:
           <strong class="text-success">{{ date('M d, Y',strtotime($patient->created_at))  ?? 'No Visit Recorded' }} @ {{ date('h:s A',strtotime($patient->currentVisit()->created_at))}}</strong>
        </p>
        <p class="mb-0 text-muted">
           Last Hospital Visit on:
           <strong class="text-success">{{ date('M d, Y',strtotime($patient->currentVisit()->visit_date))  ?? 'No Visit Recorded' }} @ {{ date('h:s A',strtotime($patient->currentVisit()->created_at))}}</strong>
        </p>
        <p class="mb-0 text-muted">
           Absconded Record:
           <strong class="text-success">{{ $patient->abscondedAdmissions()->count() }}</strong>
        </p>
        <p class="mb-0 text-muted">
           SAMA Record:
           <strong class="text-success">{{ $patient->samaAdmissions()->count() }}</strong>
        </p>
        <p class="mb-0 text-muted">
           Visit Status:
           <strong class="text-success">{{ $patient->currentVisit()->status }}</strong>
        </p>
        <p class="mb-0 text-muted">
           Admission Status:
           <strong class="text-success">{{ $patient->currentVisit()->admissionStatus() }}</strong>
        </p>
         
         
       
        <p class="mb-0 text-muted">
           Pending Balance:
           <strong class="{{$patient->payment()['pending']> 0 ? 'text-danger' : 'text-success'}}">{{ number_format($patient->payment()['pending'], 2) }}</strong>
        </p>
        
        @if(auth()->user()->hasRole('nurse') && $registeredAdmission = $patient->currentVisit()->registeredAdmission())
        <p>Patient has pending admission <a href="{{route('patient.admission.confirmed',$registeredAdmission)}}" class="btn btn-outline-warning">Confirm the Admission</a></p>
        @endif

        @else
            <div class="alert alert-warning">No visit recorded or patient was dischaged</div>
        @endif
       
    </div>
    <div class="col-md-7">
      <p class="mb-0 text-muted">
           Last Continuation Note:
           @if($note = $patient->currentVisit()->continuations()->latest()->first())
            <p class="mb-1"><strong>Notes:</strong> {{ $note->note}}</p>
            <p class="mb-1"><strong>History:</strong> {{ $note->history}}</p>
            <p class="mb-1"><strong>Examination:</strong> {{ $note->examination}}</p>
            <p class="mb-1"><strong>Diganose:</strong> {{ $note->diagnose}}</p>
            <p class="mb-1"><strong>Plan:</strong> {{ $note->plan}}</p>
           
            @else
           <strong class="text-success">Not Available</strong>
            @endif
         </p>
    </div>
</div>
@endsection


@section('content')



<div class="row">

    <!-- MAIN CONTENT -->
    @include('patient.profile.view')
</div>

@endsection
