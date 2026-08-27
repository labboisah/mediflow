<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Mediflow - Healthcare Software Solution</title>
        
        @vite(['resources/css/welcome.css', 'resources/js/app.js'])
        
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top w-100">
            <div class="container-fluid px-4">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('images/logo.png') }}" alt="Mediflow Logo" width="40" height="40" class="d-inline-block align-text-top">
                    Mediflow Health Suite
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="navbar-nav ms-auto">
                        @if (Route::has('login'))
                            @auth
                                <a class="nav-link" href="{{ url('/dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            @else
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-left"></i> Login
                                </a>
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="bi bi-person-plus"></i> Register
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section w-100 mt-5">
            <div class="overlay"></div> <!-- for readability -->
            
            <div class="container-lg">
                <div class="hero-content text-center">
                    <h1 class="hero-title">Healthcare Software Solution</h1>
                    <p class="hero-subtitle mb-4">Built for hospitals, clinics, pharmacies, diagnostic centres, maternity units, and modern health teams.</p>
                    <p class="hero-tagline">One connected platform for patient care, operations, billing, records, and reporting.</p>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <div class="container-lg py-5">
            <section class="departments-section py-5">
                <div class="container-lg">
                    <div class="text-center mb-5">
                        <h2 class="section-title">Solutions for Every Health Facility</h2>
                        <p class="section-subtitle">
                            Modular software that fits the services your facility provides today and grows with you tomorrow.
                        </p>
                    </div>

                    <div class="row g-4">

                        <!-- Card 1 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="dept-card">
                                <div class="dept-icon bg-green">
                                    <i class="bi bi-heart-pulse"></i>
                                </div>
                                <h5>Hospitals</h5>
                                <p>Coordinate patients, admissions, wards, clinical notes, billing, pharmacy, and reports.</p>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="dept-card">
                                <div class="dept-icon bg-orange">
                                    <i class="bi bi-clipboard2-pulse"></i>
                                </div>
                                <h5>Clinics</h5>
                                <p>Manage registrations, consultations, appointments, prescriptions, and patient follow-up.</p>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="dept-card">
                                <div class="dept-icon bg-green">
                                    <i class="bi bi-x-ray"></i>
                                </div>
                                <h5>Diagnostic Centres</h5>
                                <p>Handle lab and radiology requests, result entry, reporting, and payment tracking.</p>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="dept-card">
                                <div class="dept-icon bg-orange">
                                    <i class="bi bi-hospital"></i>
                                </div>
                                <h5>Maternity Units</h5>
                                <p>Track antenatal care, labour, delivery, newborn records, and postnatal follow-up.</p>
                            </div>
                        </div>

                        <!-- Card 5 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="dept-card">
                                <div class="dept-icon bg-green">
                                    <i class="bi bi-person-heart"></i>
                                </div>
                                <h5>Outpatient Care</h5>
                                <p>Support fast visits, triage, vital signs, diagnosis, treatment plans, and referrals.</p>
                            </div>
                        </div>

                        <!-- Card 6 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="dept-card">
                                <div class="dept-icon bg-orange">
                                    <i class="bi bi-capsule"></i>
                                </div>
                                <h5>Pharmacies</h5>
                                <p>Manage prescriptions, dispensing, inventory, stock batches, and reconciliation.</p>
                            </div>
                        </div>

                        <!-- Card 7 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="dept-card">
                                <div class="dept-icon bg-green">
                                    <i class="bi bi-activity"></i>
                                </div>
                                <h5>Health Operations</h5>
                                <p>Connect departments, staff responsibilities, service requests, revenue, and expenses.</p>
                            </div>
                        </div>

                        <!-- Card 8 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="dept-card">
                                <div class="dept-icon bg-orange">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <h5>Health Records</h5>
                                <p>Keep secure digital patient records available to authorized teams when needed.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
            <!-- Features Section -->
            <section class="mb-5">
                <h2 class="text-center mb-5" style="font-size: 2rem; font-weight: 700; color: var(--primary-green);">
                    <i class="bi bi-stars"></i> Platform Features
                </h2>
                
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-file-earmark-medical"></i>
                            </div>
                            <h5 class="feature-title">Electronic Medical Records</h5>
                            <p class="feature-description">Centralized patient history, encounters, clinical notes, investigations, and care records.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <h5 class="feature-title">Patient Workflow</h5>
                            <p class="feature-description">Registration, visits, admissions, appointments, referrals, billing, and service tracking.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-prescription2"></i>
                            </div>
                            <h5 class="feature-title">Medication & Pharmacy</h5>
                            <p class="feature-description">Digital prescriptions, dispensing records, medicine batches, stock movement, and inventory control.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <h5 class="feature-title">Reports & Analytics</h5>
                            <p class="feature-description">Operational, clinical, payment, finance, activity, and department reports for better decisions.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="stats-section">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number">12+</span>
                            <span class="stat-label">Healthcare Modules</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number">24/7</span>
                            <span class="stat-label">Facility Access</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number">100%</span>
                            <span class="stat-label">Role-Based Control</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Additional Features Grid -->
            <section class="my-5">
                <h3 class="text-center mb-5" style="font-size: 1.8rem; font-weight: 700; color: var(--primary-orange);">
                    <i class="bi bi-gear"></i> Advanced Capabilities
                </h3>

                <div class="row" style="gap: 2rem;">
                    <div class="col-md-6">
                        <div class="d-flex gap-3 p-3" style="background: var(--light-green); border-radius: 10px;">
                            <div style="font-size: 2rem; color: var(--primary-green); flex-shrink: 0;">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <h5 style="color: var(--primary-green); font-weight: 600;">Secure Access Control</h5>
                                <p style="color: #666; margin: 0;">Role-based permissions keep every team member focused on approved work.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex gap-3 p-3" style="background: var(--light-orange); border-radius: 10px;">
                            <div style="font-size: 2rem; color: var(--primary-orange); flex-shrink: 0;">
                                <i class="bi bi-cloud-check"></i>
                            </div>
                            <div>
                                <h5 style="color: var(--primary-orange); font-weight: 600;">Modular Licensing</h5>
                                <p style="color: #666; margin: 0;">Activate only the modules each client needs across pharmacy, clinic, diagnostics, maternity, or hospital plans.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex gap-3 p-3" style="background: var(--light-green); border-radius: 10px;">
                            <div style="font-size: 2rem; color: var(--primary-green); flex-shrink: 0;">
                                <i class="bi bi-phone"></i>
                            </div>
                            <div>
                                <h5 style="color: var(--primary-green); font-weight: 600;">Responsive Access</h5>
                                <p style="color: #666; margin: 0;">Designed for front desk, clinical, pharmacy, finance, and management teams across devices.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex gap-3 p-3" style="background: var(--light-orange); border-radius: 10px;">
                            <div style="font-size: 2rem; color: var(--primary-orange); flex-shrink: 0;">
                                <i class="bi bi-chat-dots"></i>
                            </div>
                            <div>
                                <h5 style="color: var(--primary-orange); font-weight: 600;">Connected Operations</h5>
                                <p style="color: #666; margin: 0;">Link care delivery, payments, inventory, audit logs, and facility reporting in one system.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="text-center my-5 py-5">
                <h3 style="font-size: 2rem; font-weight: 700; color: #1a1a1a; margin-bottom: 2rem;">
                    Access Your Healthcare Workspace
                </h3>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-success cta-button">
                                <span><i class="bi bi-arrow-right"></i> Go to Dashboard</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-success cta-button">
                                <span><i class="bi bi-box-arrow-in-right"></i> Login Now</span>
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-warning cta-button">
                                    <span><i class="bi bi-person-plus"></i> Create Account</span>
                                </a>
                            @endif
                        @endauth
                    @endif

                    @if($canManageWifiSharing ?? false)
                        <button type="button"
                                id="wifiConnectButton"
                                class="btn btn-warning cta-button {{ ($wifiSharing['connected'] ?? false) ? 'd-none' : '' }}"
                                data-connect-url="{{ route('wifi-sharing.connect') }}"
                                data-status-url="{{ route('wifi-sharing.status') }}">
                            <span><i class="bi bi-wifi"></i> Connect Others</span>
                        </button>
                    @endif
                </div>

                @if($canManageWifiSharing ?? false)
                    <div id="wifiConnectStatus" class="mt-3 {{ ($wifiSharing['connected'] ?? false) ? '' : 'd-none' }}" style="color: var(--primary-green); font-weight: 600;">
                        <i class="bi bi-wifi"></i>
                        Connected for Wi-Fi access
                        <span class="d-block" style="font-size: 0.95rem; color: #666;">{{ $wifiSharing['url'] ?? '' }}</span>
                    </div>
                @endif
            </section>

        </div>

        <!-- Footer -->
        <footer class="footer w-100 mt-5">
            <div class="container-lg">
                <p><i class="bi bi-c-circle"></i> 2026 Mediflow Health Suite. All rights reserved.</p>
                <p style="font-size: 0.9rem; margin-top: 0.5rem;">Hospitals | Clinics | Pharmacies | Diagnostics | Maternity Care</p>
            </div>
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const button = document.getElementById('wifiConnectButton');
                const status = document.getElementById('wifiConnectStatus');

                if (!button || !status) {
                    return;
                }

                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                const setConnected = function (data) {
                    button.classList.add('d-none');
                    status.classList.remove('d-none');
                    status.style.color = 'var(--primary-green)';

                    const url = data && data.url ? data.url : '';
                    status.innerHTML = '<i class="bi bi-wifi"></i> Connected for Wi-Fi access' + (url ? '<span class="d-block" style="font-size: 0.95rem; color: #666;">' + url + '</span>' : '');
                };

                button.addEventListener('click', async function () {
                    const original = button.innerHTML;
                    button.disabled = true;
                    button.innerHTML = '<span><span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Connecting...</span>';

                    try {
                        const response = await fetch(button.dataset.connectUrl, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token,
                            },
                        });
                        const data = await response.json();

                        if (!response.ok || !data.connected) {
                            throw new Error('Connection failed');
                        }

                        setConnected(data);
                    } catch (error) {
                        button.disabled = false;
                        button.innerHTML = original;
                        status.classList.remove('d-none');
                        status.style.color = '#b42318';
                        status.textContent = 'Unable to start Wi-Fi connection. Please make sure port 8080 is free, then try again.';
                    }
                });
            });
        </script>

    </body>
</html>
