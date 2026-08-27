@extends('layouts.app')

@section('title', 'Midwifery Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 d-flex align-items-center">
                <i class="bi bi-hospital me-3 text-primary"></i>
                Midwifery Dashboard
            </h1>
            <p class="text-muted">Welcome back! Here's your maternity care overview.</p>
        </div>
    </div>

    <!-- Key Statistics Row 1 -->
    <div class="row mb-4">
        <!-- Antenatal Care -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #e7f1ff;">
                            <i class="bi bi-heart-pulse text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Antenatal Records</h6>
                            <h3 class="h4 mb-0">{{ $antenatal_total }}</h3>
                            <small class="text-success">{{ $antenatal_today }} today</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Labour Management -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #ffe7e7;">
                            <i class="bi bi-activity text-danger" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Labour Records</h6>
                            <h3 class="h4 mb-0">{{ $labour_total }}</h3>
                            <small class="text-warning">{{ $labour_in_progress }} in progress</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deliveries -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #e8f8f5;">
                            <i class="bi bi-star-fill text-success" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Deliveries</h6>
                            <h3 class="h4 mb-0">{{ $delivery_total }}</h3>
                            <small class="text-info">{{ $delivery_today }} today</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Newborns -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #fff8e1;">
                            <i class="bi bi-brightness-high-fill text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Newborns Registered</h6>
                            <h3 class="h4 mb-0">{{ $newborn_total }}</h3>
                            <small class="text-success">{{ $newborn_healthy }} healthy</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Statistics Row 2 -->
    <div class="row mb-4">
        <!-- Postnatal Examinations -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #f3e5f5;">
                            <i class="bi bi-person-check-fill text-secondary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Postnatal Exams</h6>
                            <h3 class="h4 mb-0">{{ $postnatal_examinations_total }}</h3>
                            <small class="text-success">{{ $postnatal_normal }} normal</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Child Follow-ups -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #e0f7fa;">
                            <i class="bi bi-graph-up text-info" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Child Follow-ups</h6>
                            <h3 class="h4 mb-0">{{ $child_follow_ups_total }}</h3>
                            <small class="text-success">{{ $child_follow_ups_today }} today</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pregnant Patients -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #fce4ec;">
                            <i class="bi bi-people-fill text-danger" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Pregnant Patients</h6>
                            <h3 class="h4 mb-0">{{ $pregnant_patients }}</h3>
                            <small class="text-muted">Under care</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Newborn Examinations -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: #e8eaf6;">
                            <i class="bi bi-clipboard-check text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Newborn Exams</h6>
                            <h3 class="h4 mb-0">{{ $newborn_examinations_total }}</h3>
                            <small class="text-muted">Completed</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    

    <!-- Summary Cards Row -->
    <div class="row mb-4">
        <!-- Delivery Types Summary -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart me-2 text-primary"></i>Delivery Type Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <div style="font-size: 2rem; font-weight: bold; color: #28a745;">
                                    {{ $vaginal_deliveries }}
                                </div>
                                <small class="text-muted">Vaginal Deliveries</small>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: {{ $delivery_total > 0 ? ($vaginal_deliveries / $delivery_total * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div style="font-size: 2rem; font-weight: bold; color: #ffc107;">
                                    {{ $caesarean_deliveries }}
                                </div>
                                <small class="text-muted">Caesarean Deliveries</small>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $delivery_total > 0 ? ($caesarean_deliveries / $delivery_total * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Newborn Gender Distribution -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart me-2 text-info"></i>Newborn Gender Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <div style="font-size: 2rem; font-weight: bold; color: #0dcaf0;">
                                    {{ $newborn_males }}
                                </div>
                                <small class="text-muted">Male</small>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-info" style="width: {{ $newborn_total > 0 ? ($newborn_males / $newborn_total * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div style="font-size: 2rem; font-weight: bold; color: #f06595;">
                                    {{ $newborn_females }}
                                </div>
                                <small class="text-muted">Female</small>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-danger" style="width: {{ $newborn_total > 0 ? ($newborn_females / $newborn_total * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Health Status Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-heart-pulse me-2 text-danger"></i>Newborn Health Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Status Overview</h6>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Healthy</span>
                                    <span class="badge bg-success">{{ $newborn_healthy }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: {{ $newborn_total > 0 ? ($newborn_healthy / $newborn_total * 100) : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>At Risk</span>
                                    <span class="badge bg-warning">{{ $newborn_at_risk }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $newborn_total > 0 ? ($newborn_at_risk / $newborn_total * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Postnatal Status</h6>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Normal</span>
                                    <span class="badge bg-success">{{ $postnatal_normal }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: {{ $postnatal_examinations_total > 0 ? ($postnatal_normal / $postnatal_examinations_total * 100) : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>At Risk</span>
                                    <span class="badge bg-warning">{{ $postnatal_at_risk }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $postnatal_examinations_total > 0 ? ($postnatal_at_risk / $postnatal_examinations_total * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Records Section -->
    <div class="row mb-4">
        <!-- Recent Antenatal Care -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2 text-primary"></i>Recent Antenatal Records
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($recent_antenatal->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <tbody>
                                    @foreach($recent_antenatal as $record)
                                        <tr class="cursor-pointer" onclick="window.location='{{ route('midwife.antenatal.show', $record) }}'">
                                            <td>
                                                <strong>{{ $record->patient->full_name }}</strong><br>
                                                <small class="text-muted">{{ $record->created_at->format('M d, Y H:i') }}</small>
                                            </td>
                                            <td class="text-end">
                                                <i class="bi bi-chevron-right text-muted"></i>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-3 text-center text-muted">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">No antenatal records yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Deliveries -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2 text-success"></i>Recent Deliveries
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($recent_deliveries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <tbody>
                                    @foreach($recent_deliveries as $delivery)
                                        <tr class="cursor-pointer" onclick="window.location='{{ route('midwife.delivery.show', $delivery) }}'">
                                            <td>
                                                <strong>{{ $delivery->patient->full_name }}</strong><br>
                                                <small class="text-muted">{{ $delivery->created_at->format('M d, Y H:i') }}</small>
                                            </td>
                                            <td class="text-end">
                                                <i class="bi bi-chevron-right text-muted"></i>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-3 text-center text-muted">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">No deliveries recorded yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Newborn & Follow-ups -->
    <div class="row mb-4">
        <!-- Recent Newborns -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2 text-warning"></i>Recent Newborn Registrations
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($recent_newborns->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <tbody>
                                    @foreach($recent_newborns as $newborn)
                                        <tr class="cursor-pointer" onclick="window.location='{{ route('midwife.newborn.show', $newborn) }}'">
                                            <td>
                                                <strong>{{ $newborn->newborn_registration_number }}</strong><br>
                                                <small class="text-muted">{{ ucfirst($newborn->sex) }} - {{ $newborn->birth_weight }}g</small>
                                            </td>
                                            <td class="text-end">
                                                @if($newborn->status === 'healthy')
                                                    <span class="badge bg-success">Healthy</span>
                                                @elseif($newborn->status === 'at_risk')
                                                    <span class="badge bg-warning">At Risk</span>
                                                @else
                                                    <span class="badge bg-danger">Critical</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-3 text-center text-muted">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">No newborn registrations yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Child Follow-ups -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2 text-info"></i>Recent Child Follow-ups
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($recent_follow_ups->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <tbody>
                                    @foreach($recent_follow_ups as $followUp)
                                        <tr class="cursor-pointer" onclick="window.location='{{ route('midwife.child-follow-up.show', $followUp) }}'">
                                            <td>
                                                <strong>{{ $followUp->newborn->newborn_registration_number }}</strong><br>
                                                <small class="text-muted">{{ $followUp->created_at->format('M d, Y H:i') }}</small>
                                            </td>
                                            <td class="text-end">
                                                @if($followUp->health_status === 'normal')
                                                    <span class="badge bg-success">Normal</span>
                                                @elseif($followUp->health_status === 'at_risk')
                                                    <span class="badge bg-warning">At Risk</span>
                                                @else
                                                    <span class="badge bg-danger">Referred</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-3 text-center text-muted">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">No follow-up records yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection