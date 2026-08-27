@php
    $pendingPrescriptions = \App\Models\Prescription::with([
        'patientVisit.patient.demographic',
        'prescribedBy',
        'prescriptionItems.medicine.batches',
    ])->where('status', 'submitted')->latest()->limit(10)->get();

    $todayPrescriptions = \App\Models\Prescription::whereDate('created_at', today())->count();
    $pendingPrescriptionCount = \App\Models\Prescription::where('status', 'submitted')->count();
    $dispensedToday = \App\Models\PharmacyDispense::whereDate('created_at', today())->count();
    $pharmacyDashboardUser = auth()->user();
    $pharmacyDashboardDepartment = strtolower((string) $pharmacyDashboardUser?->department?->name);
    $canManageInventory = $pharmacyDashboardUser?->hasRole('pharmacist')
        || ($pharmacyDashboardUser?->hasRole('head_of_department') && str_contains($pharmacyDashboardDepartment, 'pharmacy'));
    $lowStockCount = $canManageInventory ? \App\Models\MedicineBatch::where('quantity_remaining', '<=', 10)->count() : 0;
    $expiringBatches = $canManageInventory ? \App\Models\MedicineBatch::with('medicine')
        ->whereDate('expiry_date', '>=', today())
        ->whereDate('expiry_date', '<=', today()->addDays(60))
        ->orderBy('expiry_date')
        ->limit(10)
        ->get() : collect();
@endphp

<div class="container-fluid">

    <h4 class="mb-4">Pharmacy Dashboard</h4>

    <div class="row g-3">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <h6>Prescriptions Today</h6>
                    <h3>{{ $todayPrescriptions }}</h3>
                    <i class="bi bi-file-medical fs-2"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-warning text-dark">
                <div class="card-body">
                    <h6>Pending Prescriptions</h6>
                    <h3>{{ $pendingPrescriptionCount }}</h3>
                    <i class="bi bi-hourglass-split fs-2"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <h6>Dispensed Today</h6>
                    <h3>{{ $dispensedToday }}</h3>
                    <i class="bi bi-capsule-pill fs-2"></i>
                </div>
            </div>
        </div>

        @if($canManageInventory)
            <div class="col-md-3">
                <div class="card shadow-sm border-0 bg-danger text-white">
                    <div class="card-body">
                        <h6>Low Stock Medicines</h6>
                        <h3>{{ $lowStockCount }}</h3>
                        <i class="bi bi-exclamation-triangle fs-2"></i>
                    </div>
                </div>
            </div>
        @endif

    </div>


    <div class="row mt-4">

        <div class="{{ $canManageInventory ? 'col-md-8' : 'col-12' }}">
            <div class="card shadow-sm">
                <div class="card-header">
                Recent Prescriptions
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Medicine</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($pendingPrescriptions as $prescription)
                            @php
                                $patient = $prescription->patientVisit?->patient;
                                $items = $prescription->prescriptionItems;
                            @endphp
                            <tr>
                                <td>{{ $patient?->demographic?->full_name ?? 'N/A' }}</td>
                                <td>{{ $items->pluck('medicine.name')->filter()->implode(', ') ?: 'No medicine' }}</td>
                                <td>{{ $prescription->prescribedBy?->name ?? 'N/A' }}</td>
                                <td>{{ $prescription->created_at->format('d M Y') }}</td>

                                <td>
                                <a href="{{ route('patient.prescription.show',$prescription) }}"
                                class="btn btn-sm btn-primary">

                                <i class="bi bi-eye"></i> View
                                </a>
                                </td>
                            </tr>
                            @empty

                            <tr>
                                <td colspan="5" class="text-center">No prescriptions available</td>
                            </tr>

                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($canManageInventory)
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                    Expiry Alerts
                    </div>
                    <div class="card-body">
                        @forelse($expiringBatches as $drug)
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span>{{ $drug->medicine?->name ?? 'N/A' }} <small class="text-muted">({{ $drug->batch_number }})</small></span>
                                <span class="badge bg-danger">
                                {{ $drug->expiry_date }}
                                </span>
                            </div>
                        @empty
                        <p class="text-muted">No expiry alerts</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
