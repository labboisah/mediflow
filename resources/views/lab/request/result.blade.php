@extends('layouts.app')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 d-flex align-items-center mb-0">
            <i class="bi bi-clipboard2-data me-2 text-primary"></i>
            Combined Investigation Results
        </h1>
    </div>
@endsection

@section('content')
    @php
        $completedRequests = $bill->investigationRequests
            ->filter(function ($request) {
                return $request->investigationResults
                    ->filter(fn ($result) => filled($result->value))
                    ->isNotEmpty();
            })
            ->values();
    @endphp
    <style>
        #print {
            position: relative;
            overflow: visible;
            background: white;
            width: min(100%, 190mm);
            margin: 0 auto;
            box-sizing: border-box;
        }

        .watermark-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.06;
            z-index: 0;
            width: 300px;
            height: 300px;
            background-image: url('{{ asset("images/logo.png") }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            pointer-events: none;
        }

        .print-content {
            position: relative;
            z-index: 2;
        }

        /* Compact print layout to better fit a single A4 page */
        @page { size: A4; margin: 8mm; }

        @media print {
            html, body { width: 210mm; min-height: 297mm; margin: 0; background: white !important; }

            .hospital-navbar,
            .admin-sidebar,
            .breadcrumb,
            header,
            .toast-container,
            .no-print {
                display: none !important;
            }

            .min-vh-100,
            .admin-layout,
            .admin-content,
            main,
            main .container-fluid {
                display: block !important;
                width: 100% !important;
                min-height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Reduce overall font sizes and spacing */
            body, .print-content { font-size: 12px; }
            h2 { font-size: 16px; margin: 0 0 4px 0; }
            h4 { font-size: 13px; margin: 0 0 6px 0; }

            /* Tighten table spacing */
            table { border-collapse: collapse; width: 100%; }
            th, td { padding: 4px 6px !important; font-size: 11px; }

            /* Smaller margins inside cards */
            .p-3, .p-4 { padding: 6px !important; }

            /* Limit image size so it doesn't push to next page */
            img { max-width: 180mm !important; height: auto !important; }
            .img-fluid { max-width: 100% !important; }

            /* Prevent breaking inside key blocks */
            .card, .row, .col, .section, .print-section, .result-section, table, thead, tbody, tfoot { page-break-inside: avoid !important; break-inside: avoid !important; }

            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }

            h1,h2,h3,h4,h5,h6 { page-break-after: avoid !important; break-after: avoid !important; }

            p, li { orphans: 2; widows: 2; }

            #print {
                position: static !important;
                width: 194mm;
                min-height: auto;
                margin: 0 auto;
                background: white;
                box-shadow: none !important;
            }

            .watermark-logo { opacity: 0.06 !important; }
        }
    </style>

    <div id="print">
        <div class="watermark-logo"></div>

        <div class="print-content p-4">
            <div class="text-center mb-4">
                <!-- hospital logo -->
                 
                <h2 class="text-success fw-bold" style="transform: scaleY(1.3);">
                    FATIMA YAHAYA HOSPITAL, SIFAWA
                </h2>
                <h4>DEPARTMENT OF {{ strtoupper(auth()->user()->department->name) }}</h4>
                <h6 class="text-danger">
                    <em>No 5, Birnin Kebbi Road Sifawa, Bodinga LG, Sokoto State</em>
                </h6>
            </div>

            <hr>

            <div class="p-3 mb-4">
                <p class="mb-1 text-muted">Patient Name: <strong>{{ $bill->patientName() }}</strong></p>
                <p class="mb-1 text-muted">
                    Hospital Number:
                    @if($bill->patientVisit)
                        <strong>{{ $bill->patientVisit->patient->hospital_number }}</strong>
                    @else
                        <strong>Walk-in Patient</strong>
                    @endif
                </p>
                
                
            </div>

            <hr>
            @forelse($completedRequests as $investigationRequest)
                @php
                    $uploadedResults = $investigationRequest->investigationResults
                        ->filter(fn ($result) => filled($result->value))
                        ->values();
                @endphp
                <div class="result-section">
                    <!-- request informations -->
                    <h5 class="fw-bold">{{ $investigationRequest->investigation?->name ?? 'Investigation' }}</h5>
                    <p class="text-muted mb-3">Lab No: {{ $investigationRequest->lab_no }}</p>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Parameter</th>
                                <th>Value</th>
                                <th>Reference Range</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($uploadedResults as $result)
                                <tr>
                                    <td>{{ $result->parameter?->name ?? 'Parameter' }}</td>
                                    <td>{{ $result->value }}</td>
                                    <td>{{ $result->parameter?->reference_range ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <div class="alert alert-warning">No uploaded lab results are available for this bill.</div>
            @endforelse
        </div>
    </div>

    <div class="mt-3 no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer me-1"></i> Print Results
        </button>
        <a href="{{ route('lab.requests.index') }}" class="btn btn-secondary ms-2">Back to Requests</a>
    </div>
@endsection
