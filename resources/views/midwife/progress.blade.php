@extends('layouts.app')

@section('title', 'Maternal Journey Tracker')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | Journey Stages
    |--------------------------------------------------------------------------
    */

    $stages = [

        [
            'title' => 'Antenatal Care Registration',
            'icon' => 'bi-person-plus',
            'completed' => !is_null($antenatalCare),
            'view_route' => $antenatalCare
                ? route('midwife.antenatal.show', $antenatalCare)
                : null,
            'create_route' => route('midwife.antenatal.create', $patient),
            'date' => $antenatalCare?->created_at,
        ],

        [
            'title' => 'Labour Registration',
            'icon' => 'bi-heart-pulse',
            'completed' => !is_null($labour),
            'view_route' => $labour
                ? route('midwife.labour.show', $labour)
                : null,
            'create_route' => $antenatalCare
                ? route('midwife.labour.create', $patient)
                : null,
            'date' => $labour?->created_at,
        ],

        [
            'title' => 'Delivery Record',
            'icon' => 'bi-hospital',
            'completed' => !is_null($delivery),
            'view_route' => $delivery
                ? route('midwife.delivery.show', $delivery)
                : null,
            'create_route' => $labour
                ? route('midwife.delivery.create', $labour)
                : null,
            'date' => $delivery?->created_at,
        ],

        [
            'title' => 'Newborn Registration',
            'icon' => 'bi-baby',
            'completed' => $newborns->count() > 0,
            'view_route' => $newborns->count()
                ? route('midwife.newborn.show', $newborns->first())
                : null,
            'create_route' => $delivery
                ? route('midwife.newborn.create', $delivery)
                : null,
            'date' => $newborns->count()
                ? $newborns->first()->created_at
                : null,
        ],

        [
            'title' => 'Newborn Examination',
            'icon' => 'bi-clipboard2-pulse',
            'completed' => $newbornExaminations->count() > 0,
            'view_route' => $newbornExaminations->count()
                ? route('midwife.newborn-examination.show', $newbornExaminations->first())
                : null,
            'create_route' => $newborns->count()
                ? route('midwife.newborn-examination.create', $newborns->first())
                : null,
            'date' => $newbornExaminations->count()
                ? $newbornExaminations->first()->created_at
                : null,
        ],

        [
            'title' => 'Postnatal Examination',
            'icon' => 'bi-journal-medical',
            'completed' => $postnatalExaminations->count() > 0,
            'view_route' => $postnatalExaminations->count()
                ? route('midwife.postnatal-examination.show', $postnatalExaminations->first())
                : null,
            'create_route' => $delivery
                ? route('midwife.postnatal-examination.create', $delivery)
                : null,
            'date' => $postnatalExaminations->count()
                ? $postnatalExaminations->first()->created_at
                : null,
        ],

        [
            'title' => 'Child Follow-up',
            'icon' => 'bi-arrow-repeat',
            'completed' => $childFollowUps->count() > 0,
            'view_route' => $childFollowUps->count()
                ? route('midwife.child-follow-up.show', $childFollowUps->first())
                : null,
            'create_route' => $newborns->count()
                ? route('midwife.child-follow-up.create', $newborns->first())
                : null,
            'date' => $childFollowUps->count()
                ? $childFollowUps->first()->created_at
                : null,
        ],

    ];

    /*
    |--------------------------------------------------------------------------
    | Progress Calculation
    |--------------------------------------------------------------------------
    */

    $completedStages = collect($stages)
        ->where('completed', true)
        ->count();

    $totalStages = count($stages);

    $progressPercentage = round(
        ($completedStages / $totalStages) * 100
    );

@endphp

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col-md-8">

            <h1 class="h3 mb-0">

                <i class="bi bi-diagram-3"></i>
                Maternal Journey Tracker

            </h1>

            <small class="text-muted">

                Patient:
                <strong>
                    {{ $patient->name() }}
                </strong>

                |

                Hospital No:
                <strong>
                    {{ $patient->hospital_number }}
                </strong>

            </small>

        </div>

        <div class="col-md-4 text-end">

            <span class="badge bg-primary fs-6">

                {{ $progressPercentage }}% Completed

            </span>

        </div>

    </div>

    <!-- Progress Overview -->
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between mb-2">

                <strong>
                    Maternal Journey Progress
                </strong>

                <strong>

                    {{ $completedStages }}
                    /
                    {{ $totalStages }}

                    Stages Completed

                </strong>

            </div>

            <div class="progress"
                 style="height: 25px;">

                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                     role="progressbar"
                     style="width: {{ $progressPercentage }}%">

                    {{ $progressPercentage }}%

                </div>

            </div>

        </div>

    </div>

    <!-- Timeline -->
    <div class="card shadow-sm">

        <div class="card-header bg-light">

            <h5 class="mb-0">

                <i class="bi bi-list-check"></i>
                Maternal Care Journey Timeline

            </h5>

        </div>

        <div class="card-body">

            <div class="timeline">

                @foreach($stages as $index => $stage)

                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | Detect Current Stage
                        |--------------------------------------------------------------------------
                        */

                        $previousCompleted = $index == 0
                            ? true
                            : $stages[$index - 1]['completed'];

                        $isCurrentStage =
                            !$stage['completed']
                            && $previousCompleted;

                    @endphp

                    <div class="row mb-4">

                        <div class="col-md-1 text-center">

                            @if($stage['completed'])

                                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                     style="width:60px;height:60px;">

                                    <i class="bi bi-check-circle-fill fs-4"></i>

                                </div>

                            @elseif($isCurrentStage)

                                <div class="bg-warning text-dark rounded-circle d-inline-flex align-items-center justify-content-center"
                                     style="width:60px;height:60px;">

                                    <i class="bi {{ $stage['icon'] }} fs-4"></i>

                                </div>

                            @else

                                <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                     style="width:60px;height:60px;opacity:0.5;">

                                    <i class="bi {{ $stage['icon'] }} fs-4"></i>

                                </div>

                            @endif

                        </div>

                        <div class="col-md-11">

                            <div class="card border-0 shadow-sm">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between align-items-center flex-wrap">

                                        <div>

                                            <h5 class="mb-1">

                                                {{ $stage['title'] }}

                                            </h5>

                                            @if($stage['completed'])

                                                <span class="badge bg-success">

                                                    Completed

                                                </span>

                                            @elseif($isCurrentStage)

                                                <span class="badge bg-warning text-dark">

                                                    Current Stage

                                                </span>

                                            @else

                                                <span class="badge bg-secondary">

                                                    Pending

                                                </span>

                                            @endif

                                            @if($stage['date'])

                                                <small class="text-muted d-block mt-2">

                                                    Recorded:
                                                    {{ $stage['date']->format('M d, Y h:i A') }}

                                                </small>

                                            @endif

                                        </div>

                                        <div class="mt-2 mt-md-0">

                                            @if($stage['completed'])

                                                <a href="{{ $stage['view_route'] }}"
                                                   class="btn btn-outline-success">

                                                    <i class="bi bi-eye"></i>
                                                    View Record

                                                </a>

                                            @elseif($isCurrentStage && $stage['create_route'])

                                                <a href="{{ $stage['create_route'] }}"
                                                   class="btn btn-warning">

                                                    <i class="bi bi-plus-circle"></i>
                                                    Start Stage

                                                </a>

                                            @else

                                                <button class="btn btn-outline-secondary"
                                                        disabled>

                                                    Waiting Previous Stage

                                                </button>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    
            <a href="{{ route('midwife.antenatal.index') }}" class="btn btn-outline-secondary mt-3">Back to Patients List</a>
    </div>
</div>

@endsection