@php
    $navUser = Auth::user();
    $canNav = function (array $roles = [], array $permissions = []) use ($navUser) {
        if (! $navUser) {
            return false;
        }

        return (! empty($roles) && $navUser->hasAnyRole($roles))
            || (! empty($permissions) && $navUser->hasAnyPermission($permissions));
    };
    $showAdminSidebar = $navUser !== null;
    $navDepartmentName = strtolower((string) $navUser?->department?->name);
    $canManageDepartmentInvestigations = str_contains($navDepartmentName, 'lab') || str_contains($navDepartmentName, 'radio');
    $canManagePharmacyInventory = $navUser?->hasRole('pharmacist')
        || ($navUser?->hasRole('head_of_department') && str_contains($navDepartmentName, 'pharmacy'));

    $navClinicalPatient = null;
    foreach (['patient', 'prescription', 'admission', 'investigationRequest', 'vitalSign'] as $routeKey) {
        $routeValue = request()->route($routeKey);

        if ($routeValue instanceof \App\Models\Patient) {
            $navClinicalPatient = $routeValue;
            break;
        }

        if ($routeValue instanceof \App\Models\Prescription) {
            $navClinicalPatient = $routeValue->patientVisit?->patient;
            break;
        }

        if ($routeValue instanceof \App\Models\Admission) {
            $navClinicalPatient = $routeValue->patientVisit?->patient;
            break;
        }

        if ($routeValue instanceof \App\Models\InvestigationRequest) {
            $navClinicalPatient = $routeValue->patientVisit?->patient;
            break;
        }

        if ($routeValue instanceof \App\Models\VitalSign) {
            $navClinicalPatient = $routeValue->patientVisit?->patient;
            break;
        }
    }

    $navClinicalVisit = $navClinicalPatient?->currentVisit();
    $navClinicalCounts = [
        'vitals' => $navClinicalVisit?->vitalSigns()->count() ?? 0,
        'observations' => $navClinicalVisit?->observations()->count() ?? 0,
        'drug_charts' => $navClinicalVisit
            ? \App\Models\DrugChart::whereHas('prescriptionItem.prescription', fn ($query) => $query->where('patient_visit_id', $navClinicalVisit->id))->count()
            : 0,
        'fluid_balance' => $navClinicalVisit
            ? $navClinicalVisit->admissions()->withCount('fluidBalances')->get()->sum('fluid_balances_count')
            : 0,
        'investigations' => $navClinicalVisit?->investigationRequests()->count() ?? 0,
        'admissions' => $navClinicalVisit?->admissions()->count() ?? 0,
        'prescriptions' => $navClinicalVisit?->prescriptions()->count() ?? 0,
        'continuations' => $navClinicalVisit?->continuations()->count() ?? 0,
    ];
@endphp

<nav class="navbar navbar-expand-lg hospital-navbar shadow-sm">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                        <x-hospital-logo class="me-2" />
                        <div class="ms-2">
                            <div class="fw-bold brand-accent" ><img src="{{asset('images/logo.png')}}" alt="logo" width="60"> <span style="transform: scaleY(2);">FAYHOS</span> </div>
                        </div>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="mainNav">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('dashboard') }}"><i class="bi bi-house-fill me-2 text-success"></i>Home</a>
                            </li>

                            

                            @if(! $showAdminSidebar)
                            @if($canNav(['record'], ['patient.read']))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('record.patients.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Patients</a>
                            </li>
                            @endif

                            @if($canNav(['nurse'], ['vital_sign.read', 'observation.read', 'nursing_note.read']))

                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('nurse.patient.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>{{ auth()->user()->department ? count(auth()->user()->pendingServiceRequests()) : 0 }} Patients</a>
                            </li>
                            @endif



                            @if($canNav(['doctor'], ['prescription.read', 'admission.read', 'discharge.read']))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('doctor.patient.index') }}"><i class="bi bi-people-fill me-2 text-success"></i> {{ auth()->user()->department ? count(auth()->user()->pendingServiceRequests()) : 0 }} Patients</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('doctor.patient.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Admission</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('doctor.patient.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Discharge</a>
                            </li>
                            
                            @endif

                            @if($canNav(['midwife'], ['antenatal_care.read']))
                            
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('midwife.patient.index') }}"><i class="bi bi-people-fill me-2 text-success"></i> {{ auth()->user()->department ? count(auth()->user()->pendingServiceRequests()) : 0 }} ANC Patients</a>
                            </li>
                            @endif

                            @if($canNav(['midwife'], ['antenatal_care.read', 'labour.read', 'delivery.read', 'newborn.read']))
                            <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-lightning-fill me-2 text-success"></i>
                                        Quick Actions
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.antenatal.index') }}">

                                                <i class="bi bi-heart-pulse-fill me-2 text-primary"></i>
                                                Antenatal Care

                                            </a>
                                        </li>

                                        <!-- Labour -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.labour.index') }}">

                                                <i class="bi bi-activity me-2 text-warning"></i>
                                                Labour Management

                                            </a>
                                        </li>

                                        <!-- Delivery -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.delivery.index') }}">

                                                <i class="bi bi-hospital-fill me-2 text-danger"></i>
                                                Delivery Records

                                            </a>
                                        </li>

                                        <!-- Newborn -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.newborn.index') }}">

                                                <i class="bi bi-bandaid-fill me-2 text-info"></i>
                                                Newborn Records

                                            </a>
                                        </li>

                                        <!-- Newborn Examinations -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.newborn-examination.index') }}">

                                                <i class="bi bi-clipboard2-pulse-fill me-2 text-success"></i>
                                                Newborn Examinations

                                            </a>
                                        </li>

                                        <!-- Postnatal Examination -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.postnatal-examination.index') }}">

                                                <i class="bi bi-journal-medical me-2 text-secondary"></i>
                                                Postnatal Examination

                                            </a>
                                        </li>

                                        <!-- Child Follow Up -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.child-follow-up.index') }}">

                                                <i class="bi bi-arrow-repeat me-2 text-dark"></i>
                                                Child Follow-up

                                            </a>
                                        </li>
                                        
                                    </ul>
                                </li>
                            

                            @endif

                            @if($canNav(['lab_technician', 'lab_scientist'], ['laboratory_request.read', 'laboratory_investigation.read', 'laboratory_result.create']))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('lab.requests.index') }}"><i class="bi bi-clipboard2-pulse me-2 text-success"></i>{{ auth()->user()->department ? auth()->user()->department->requestStats()['pending'] : 0 }} Requests</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('lab.investigations.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Investigations</a>
                            </li>
                            <!-- results entry -->
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('lab.result') }}"><i class="bi bi-clipboard2-data me-2 text-success"></i>Results Entry</a>
                            </li>
                            @endif
                            @if($canNav(['head_of_department'], ['consumable.read', 'consumable_stock.read', 'user.read']))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('department.users.index') }}"><i class="bi bi-people me-2 text-success"></i>Users</a>
                            </li>
                            @if($canManageDepartmentInvestigations)
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('department.investigations.index') }}"><i class="bi bi-clipboard2-data me-2 text-success"></i>Investigations</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('department.consumables.index') }}"><i class="bi bi-box-seam me-2 text-success"></i>Consumables</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('department.stocks.index') }}"><i class="bi bi-boxes me-2 text-success"></i>Consumable Stock</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('department.stock-usage.index') }}"><i class="bi bi-clipboard-check me-2 text-success"></i>Stock Usage</a>
                            </li>
                            
                            @endif

                            @if($canNav(['radiologist', 'radiographer'], ['radiology_request.read', 'radiology_investigation.read']))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('radiology.requests.index') }}"><i class="bi bi-radioactive me-2 text-success"></i>{{ auth()->user()->department ? auth()->user()->department->requestStats()['pending'] : 0 }} Requests</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('radiology.investigations.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Investigations</a>
                            </li>
                            
                            @endif

                            @if($navUser?->hasRole('accountant'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('accountant.bills.index') }}"><i class="fa-solid fa-naira-sign me-2 text-success"></i>  Billing</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('accountant.payments.index') }}"><i class="bi bi-receipt me-2 text-success"></i>Payments</a>
                            </li>
                            
                            @endif

                            @if(! $showAdminSidebar && $canNav(['administrator'], ['role.read', 'permission.read', 'user.read', 'service.read', 'investigation.read', 'department.read', 'ward.read']))
                                
                                @if($canNav(['administrator'], ['role.read', 'permission.read']))
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center" href="{{ route('admin.access-control') }}"><i class="bi bi-shield-check me-2 text-success"></i>Access Control</a>
                                    </li>
                                @endif
                                <!-- Add more admin-specific links here -->
                                @if($canNav(['administrator'], ['user.read']))
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center" href="{{ route('admin.users.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Users</a>
                                    </li>
                                @endif
                                @if($canNav(['administrator'], ['service.read', 'investigation.read', 'department.read', 'ward.read']))
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="managementDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-sliders me-2 text-success"></i>
                                            Management
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="managementDropdown">
                                            @if($canNav(['administrator'], ['service.read']))
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.services.index') }}">
                                                        <i class="bi bi-gear-fill me-2"></i>
                                                        Services
                                                    </a>
                                                </li>
                                            @endif
                                            @if($canNav(['administrator'], ['investigation.read']))
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.investigations.index') }}">
                                                        <i class="bi bi-clipboard2-data me-2"></i>
                                                        Investigation
                                                    </a>
                                                </li>
                                            @endif
                                            @if($canNav(['administrator'], ['department.read']))
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.departments.index') }}">
                                                        <i class="bi bi-buildings me-2"></i>
                                                        Departments
                                                    </a>
                                                </li>
                                            @endif
                                            @if($canNav(['administrator'], ['ward.read']))
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.wards.index') }}">
                                                        <i class="bi bi-hospital me-2"></i>
                                                        Wards
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif

                                
                            @endif

                            @if($canNav(['pharmacist'], ['pharmacy_sale.read', 'dispense.read']))
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-file-medical me-1"></i>
                                        Prescriptions
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('pharmacy.transactions.index')}}">
                                        <i class="bi bi-file-medical me-1"></i>
                                        Transactions
                                    </a>
                                </li>

                                @if($canManagePharmacyInventory)
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('pharmacy.medicines.index')}}">
                                            <i class="bi bi-capsule me-1"></i>
                                            Medicine
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('pharmacy.stocks.index')}}">
                                            <i class="bi bi-box-seam me-1"></i>
                                            Stock
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('pharmacy.batches.index')}}">
                                            <i class="bi bi-layers me-1"></i>
                                            Batches
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('pharmacy.expiries.index')}}">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            Expiry Alerts
                                        </a>
                                    </li>
                                @endif

                                
                            @endif

                            @if(! $showAdminSidebar && $canNav(['administrator', 'medical_director', 'accountant'], ['bill.read', 'payment.read', 'expense.read', 'department_report.read']))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-cash-stack me-2 text-success"></i>
                                    Finance
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                                    
                                    @if($navUser?->hasRole('administrator'))
                                        <li><a class="dropdown-item" href="{{ route('admin.bills.index') }}"><i class="bi bi-receipt me-2"></i> Bills Management</a></li>
                                    @endif
                                    @if($navUser?->hasRole('administrator'))
                                        <li><a class="dropdown-item" href="{{ route('admin.payments.index') }}"><i class="bi bi-credit-card-2-front me-2"></i> Payments Management</a></li>
                                    @endif
                                    @if($canNav(['administrator', 'medical_director', 'accountant'], ['bill.read', 'report.read', 'department_report.read']))
                                        <li><a class="dropdown-item" href="{{ route('reports.finance.index') }}"><i class="bi bi-file-earmark-text me-2"></i> Billing Report</a></li>
                                    @endif
                                    @if($canNav(['administrator', 'medical_director', 'accountant'], ['payment.read', 'report.read', 'department_report.read']))
                                        <li><a class="dropdown-item" href="{{ route('reports.payments.index') }}"><i class="bi bi-bar-chart-line me-2"></i> Payment Report</a></li>
                                    @endif
                                    @if($canNav(['administrator', 'medical_director'], ['expense.read']))
                                        <li><a class="dropdown-item" href="{{ $navUser?->hasRole('medical_director') ? route('medical-director.expenses.index') : route('admin.expenses.index') }}"><i class="bi bi-cash-stack me-2 text-success"></i> Expenses</a></li>
                                    @endif
                                    @if($canNav(['administrator', 'medical_director'], ['revenue.read']))
                                        <li><a class="dropdown-item" href="{{ $navUser?->hasRole('medical_director') ? route('medical-director.revenues.index') : route('admin.revenues.index') }}"><i class="bi bi-cash-stack me-2 text-success"></i> Revenues</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if(! $showAdminSidebar && $canNav(['administrator'], ['department_report.read']))
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="activitiesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-activity me-2 text-success"></i>
                                        Activities
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="activitiesDropdown">
                                        @foreach(\App\Models\Department::orderBy('name')->get(['id', 'name']) as $activityDepartment)
                                            <li>
                                                <a class="dropdown-item" href="{{ route('reports.activities.show', $activityDepartment) }}">
                                                    <i class="bi bi-building me-2"></i>
                                                    {{ $activityDepartment->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                            @endif

                            @auth
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-person-circle me-2 text-secondary"></i>
                                        {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                        <li><a class="dropdown-item" href="{{ route('reports.my-activities.index') }}">My Activities</a></li>
                                        @if(Auth::user()->hasRole('administrator'))
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.system.update') }}">Check System Update</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.sync.index') }}">Data Synchronization Dashboard</a>
                                            </li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item">Log Out</button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="btn btn-outline-success ms-2 d-flex align-items-center" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>
