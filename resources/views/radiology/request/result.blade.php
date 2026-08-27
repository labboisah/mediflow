@extends('layouts.app')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 d-flex align-items-center mb-0">
            <i class="bi bi-clipboard2-data me-2 text-primary"></i>
            Radiology Investigation Result
        </h1>
    </div>
@endsection

@section('content')
    <style>
        #print {
            position: relative;
            overflow: hidden;
            background: white;
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
            html, body { width: 210mm; height: 297mm; margin: 0; }

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

            /* Show only the print area */
            body * { visibility: hidden; }
            #print, #print * { visibility: visible; }

            #print { position: absolute; left: 0; top: 0; width: auto; background: white; }

            .watermark-logo { opacity: 0.06 !important; }
        }
    </style>

    <div id="print">
        <div class="watermark-logo"></div>

        <div class="print-content p-4">
            <div class="text-center mb-4">
                <h2 class="text-success fw-bold" style="transform: scaleY(1.3);">FATIMA YAHAYA HOSPITAL, SIFAWA</h2>
                <h4>DEPARTMENT OF {{ strtoupper(auth()->user()->department->name) }}</h4>
            </div>

            <hr>

            <div class="p-3 mb-4">
                <p class="mb-1 text-muted">Patient Name: <strong>{{ $patientName }}</strong></p>
                <p class="mb-1 text-muted">
                    Hospital Number:
                    @if($hospitalNumber)
                        <strong>{{ $hospitalNumber }}</strong>
                    @else
                        <strong>Walk-in Patient</strong>
                    @endif
                </p>
                <p class="mb-1 text-muted">Requested At: <strong>{{ $investigationRequest->created_at->format('d M, Y @ h:i A') }}</strong></p>
                <p class="mb-1 text-muted">Requested By: <strong>{{ $investigationRequest->requestedBy->name ?? 'N/A' }}</strong></p>
                <p class="mb-1 text-muted">Performed By: <strong>{{ $investigationRequest->performedBy->name ?? 'N/A' }}</strong></p>
            </div>

            <hr>

            <div class="mb-4">
                <h5 class="fw-bold mb-3">{{ $investigationRequest->investigation->name }}</h5>

                @if($investigationRequest->investigationResults->isEmpty())
                    <div class="alert alert-warning">No results recorded yet.</div>
                @else
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Parameter</th>
                                <th>Value</th>
                                <th>Reference Range</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($investigationRequest->investigationResults as $result)
                                <tr>
                                    <td>{{ $result->parameter->name ?? 'Parameter' }}</td>
                                    <td>{{ $result->value }}</td>
                                    <td>{{ $result->parameter->reference_range }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                @if($investigationRequest->result_image)
                    <div class="mt-4">
                        <h6 class="mb-2">Attached Image</h6>
                        <a href="{{ asset('storage/' . $investigationRequest->result_image) }}" target="_blank">
                            <img src="{{ asset('storage/' . $investigationRequest->result_image) }}" class="img-fluid border" style="max-width:700px;" alt="Radiology Image">
                        </a>
                        <p class="text-muted small mt-1">Click image to open full size in a new tab.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer me-1"></i> Print Result</button>
        <a href="{{ route('radiology.requests.index') }}" class="btn btn-secondary ms-2">Back to Requests</a>
    </div>
@endsection
