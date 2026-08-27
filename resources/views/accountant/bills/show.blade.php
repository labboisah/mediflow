@extends('layouts.app')

@section('content')
@php

if($bill->walkinPatient) {
    $patientName = $bill->walkinPatient->name;
    $hospitalNumber = 'Walk-in Patient';
} else if($bill->patientVisit) {
    $patientName = $bill->patientVisit->patient->demographic->getFullNameAttribute();
    $hospitalNumber = $bill->patientVisit->patient->hospital_number;
}else{
    $patientName = 'N/A';
    $hospitalNumber = 'N/A';    
}
$billDate = now()->format('M d, Y h:i A');
@endphp

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Bill Details</h1>
                <div>
                    @if($bill->status !== 'paid')
                        <a href="{{ route('accountant.bills.payments.create', $bill) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-cash-coin"></i> Record Payment
                        </a>
                    @endif
                    @if($bill->canBeManagedAsUnpaidByAccountant(auth()->user()))
                        <a href="{{ route('accountant.bills.edit', $bill) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    @endif
                    <a href="{{ route('accountant.bills.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            {{-- Print Actions --}}
            <div class="card shadow-sm border-secondary mb-4 d-print-none">
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <button onclick="window.print()" class="btn btn-primary w-100">
                                <i class="bi bi-printer"></i> Print A4 Bill
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button onclick="printThermalBill()" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-printer-fill"></i> Print Thermal Bill
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bill Preview --}}
            <div class="card shadow-sm mb-4" id="bill-preview">
                <div class="card-header bg-primary text-white p-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Bill Details</h5>
                            <small>Bill #: <strong>{{ $bill->bill_number }}</strong></small>
                        </div>
                        <div class="col-auto text-end">
                            <i class="bi bi-file-earmark-text" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Patient Name</p>
                            <p class="fw-bold">{{ $patientName ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Hospital Number</p>
                            <p class="fw-bold">{{ $hospitalNumber ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Bill Number</p>
                            <p class="fw-bold">{{ $bill->bill_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Status</p>
                            <p>
                                @if($bill->status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($bill->status === 'partial')
                                    <span class="badge bg-warning">Partial</span>
                                @elseif($bill->status === 'pending')
                                    <span class="badge bg-danger">Pending</span>
                                @else
                                    <span class="badge bg-secondary">Cancelled</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Issued Date</p>
                                <p class="fw-bold">{{ $bill->issued_date->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Due Date</p>
                                <p class="fw-bold">{{ $bill->due_date->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Discount</p>
                                <p class="fw-bold">{{ number_format($bill->discount, 0) }}%</p>
                    </div>

                    <hr>

                    {{-- Services Breakdown --}}
                    @if($bill->services->count() > 0 || $bill->investigations->count() > 0)
                        <div class="mb-4">
                            <p class="text-muted small mb-2">Services & Items</p>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Description</th>
                                            <th class="text-end">Unit Price</th>
                                            <th class="text-end">Qty</th>
                                            <th class="text-end">Subtotal</th>
                                            <th class="text-end">Payment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bill->services as $service)
                                            <tr>
                                                <td>
                                                    <strong>{{ $service->name }}</strong><br>
                                                    <small class="text-muted">{{ $service->code }}</small>
                                                </td>
                                                <td class="text-end">{{ number_format($service->pivot->unit_price, 2) }}</td>
                                                <td class="text-end">{{ $service->pivot->quantity }}</td>
                                                <td class="text-end">{{ number_format($service->pivot->subtotal, 2) }}</td>
                                                <td class="text-end">{{ ucfirst($bill->status) }}</td>
                                                
                                            </tr>
                                        @endforeach

                                        @foreach($bill->investigations as $investigation)
                                            <tr>
                                                <td>
                                                    <strong>{{ $investigation->name }}</strong><br>
                                                    <small class="text-muted">{{ $investigation->code }}</small>
                                                </td>
                                                <td class="text-end">{{ number_format($investigation->pivot->unit_price, 2) }}</td>
                                                <td class="text-end">{{ $investigation->pivot->quantity }}</td>
                                                <td class="text-end">{{ number_format($investigation->pivot->subtotal, 2) }}</td>
                                                <td class="text-end">{{ ucfirst($bill->status) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="fw-bold border-top">
                                            <td colspan="4" class="text-end">Total:</td>
                                            <td class="text-end">{{ number_format($bill->amount, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <hr>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <p class="text-muted small mb-1">Amount</p>
                            <h4 class="text-primary mb-0">{{ number_format($bill->amount, 2) }}</h4>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted small mb-1">Amount Due</p>
                            <h4 class="text-primary mb-0">{{ number_format($bill->due_amount, 2) }}</h4>
                        </div>
                        <div class="col-md-333">
                            <p class="text-muted small mb-1">Total Paid</p>
                            <h4 class="text-success mb-0">{{ number_format($bill->totalPaid(), 2) }}</h4>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted small mb-1">Balance Due</p>
                            <h4 class="text-danger mb-0">{{ number_format($bill->balance, 2) }}</h4>
                        </div>
                    </div>

                    <div class="text-center text-muted small mt-4 pt-3 border-top">
                        <p class="mb-1">This bill is generated by the system. Please keep it for your records.</p>
                        <p class="mb-0">Issued by: <strong>{{ $bill->issuedBy->name }}</strong></p>
                        <p class="mb-0">Bill Date: <strong>{{ $billDate }}</strong></p>
                    </div>
                </div>
            </div>

            {{-- Thermal Bill Preview --}}
            <div class="card shadow-sm border-secondary mb-4 d-print-none" id="thermal-bill-preview-card">
                <div class="card-header bg-secondary text-white py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Thermal Bill Preview</h6>
                            <small class="text-white-50">Review before printing</small>
                        </div>
                        <button type="button" onclick="printThermalBill()" class="btn btn-sm btn-light">
                            <i class="bi bi-printer-fill"></i> Print Thermal
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="thermal-bill-preview">
                        <div class="text-center mb-3">
                            <h5 class="mb-1">{{ strtoupper(config('app.name', 'FAYHOS')) }}</h5>
                            <p class="small mb-1">Bill Statement</p>
                            <div class="divider"></div>
                        </div>

                        <div class="mb-2 small">
                            <p class="mb-1"><strong>Bill:</strong> {{ $bill->bill_number }}</p>
                            <p class="mb-1"><strong>Date:</strong> {{ $billDate }}</p>
                            <p class="mb-1"><strong>Patient:</strong> {{ $patientName ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Hospital No:</strong> {{ $hospitalNumber ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Status:</strong> {{ ucfirst($bill->status) }}</p>
                        </div>

                        <div class="divider"></div>
                        <table class="w-100">
                            @foreach($bill->services as $service)
                                <tr>
                                    <td style="width:50%;">{{ Str::limit($service->name, 15) }}</td>
                                    <td class="text-end">{{ number_format($service->pivot->unit_price, 2) }}</td>
                                    <td class="text-end">{{ $service->pivot->quantity }}</td>
                                    <td class="text-end">{{ number_format($service->pivot->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="small text-muted">Status: {{ ucfirst($bill->status) }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                            @foreach($bill->investigations as $investigation)
                                <tr>
                                    <td>{{ Str::limit($investigation->name, 15) }}</td>
                                    <td class="text-end">{{ number_format($investigation->pivot->unit_price, 2) }}</td>
                                    <td class="text-end">{{ $investigation->pivot->quantity }}</td>
                                    <td class="text-end">{{ number_format($investigation->pivot->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="small text-muted">Status: {{ ucfirst($bill->status) }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="divider"></div>

                        <p class="mb-1"><strong>Total Due:</strong> {{ number_format($bill->due_amount, 2) }}</p>
                        <p class="mb-1"><strong>Total Paid:</strong> {{ number_format($bill->totalPaid(), 2) }}</p>
                        <p class="mb-0"><strong>Balance:</strong> {{ number_format($bill->balance, 2) }}</p>
                        <div class="divider"></div>
                        <p class="small mb-0">Issued by: {{ $bill->issuedBy->name }}</p>
                        <p class="small mb-0">Please settle payment promptly.</p>
                    </div>
                </div>
            </div>

            {{-- Payments History --}}
            @if($bill->payments->count() > 0)
                <div class="card shadow-sm d-print-none">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Payment History</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Date</th>
                                        <th>Recorded By</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bill->payments as $payment)
                                        <tr>
                                            <td><strong>{{ $payment->payment_id }}</strong></td>
                                            <td>{{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ $payment->payment_method }}</td>
                                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                            <td>{{ $payment->recordedBy->name }}</td>
                                            <td>
                                                <a href="{{ route('accountant.payments.receipt', $payment) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-printer"></i> Print Receipt
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Print-only bill content for A4 --}}
<div id="bill-print-content" class="d-none">
    <div class="bill-print-card">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h6 class="mb-1">Bill Statement</h6>
                <small>Bill #: <strong>{{ $bill->bill_number }}</strong></small>
            </div>
            <div class="text-end">
                <i class="bi bi-file-earmark-text" style="font-size:1.6rem;"></i>
            </div>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between">
                <div>
                    <p class="mb-1 text-muted small">Patient Name</p>
                    <p class="mb-0 fw-bold">{{ $patientName ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="mb-1 text-muted small">Hospital Number</p>
                    <p class="mb-0 fw-bold">{{ $hospitalNumber ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <div>
                <p class="mb-1 text-muted small">Bill Number</p>
                <p class="mb-0 fw-bold">{{ $bill->bill_number }}</p>
            </div>
            <div>
                <p class="mb-1 text-muted small">Status</p>
                <p class="mb-0 fw-bold">{{ ucfirst($bill->status) }}</p>
            </div>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <div>
                <p class="mb-1 text-muted small">Issued Date</p>
                <p class="mb-0 fw-bold">{{ $bill->issued_date->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="mb-1 text-muted small">Due Date</p>
                <p class="mb-0 fw-bold">{{ $bill->due_date->format('M d, Y') }}</p>
            </div>
        </div>

        @if($bill->services->count() > 0 || $bill->investigations->count() > 0)
            <div class="mb-3">
                <p class="mb-1 text-muted small">Services & Items</p>
                <table class="table table-borderless table-sm mb-0 w-100">
                    <tbody>
                    @foreach($bill->services as $service)
                        <tr>
                            <td class="small">{{ $service->name }}</td>
                            <td class="text-end small">{{ number_format($service->pivot->unit_price, 2) }}</td>
                            <td class="text-end small">{{ $service->pivot->quantity }}</td>
                            <td class="text-end small">{{ number_format($service->pivot->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="small text-muted">Status: {{ ucfirst($bill->status) }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                    @foreach($bill->investigations as $investigation)
                        <tr>
                            <td class="small">{{ $investigation->name }}</td>
                            <td class="text-end small">{{ number_format($investigation->pivot->unit_price, 2) }}</td>
                            <td class="text-end small">{{ $investigation->pivot->quantity }}</td>
                            <td class="text-end small">{{ number_format($investigation->pivot->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="small text-muted">Status: {{ ucfirst($bill->status) }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-top">
                            <td class="small fw-bold">Total:</td>
                            <td></td>
                            <td></td>
                            <td class="text-end small fw-bold">{{ number_format($bill->amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

            <div class="d-flex justify-content-between mb-3">
            <div>
                <p class="mb-1 text-muted small">Amount Due</p>
                <p class="mb-0 fw-bold">{{ number_format($bill->due_amount, 2) }}</p>
            </div>
            <div>
                <p class="mb-1 text-muted small">Total Paid</p>
                <p class="mb-0 fw-bold">{{ number_format($bill->totalPaid(), 2) }}</p>
            </div>
            <div>
                <p class="mb-1 text-muted small">Balance Due</p>
                <p class="mb-0 fw-bold">{{ number_format($bill->balance, 2) }}</p>
            </div>
        </div>

        <div class="text-center text-muted small">
            <p class="mb-1">This bill is generated by the system. Please keep it for your records.</p>
            <p class="mb-0">Issued by: <strong>{{ $bill->issuedBy->name }}</strong></p>
            <p class="mb-0">Bill Date: <strong>{{ $billDate }}</strong></p>
        </div>
    </div>
</div>

{{-- Thermal bill template --}}
<div id="thermal-bill-template" class="d-none">
    <div class="thermal-bill">
        <div class="text-center">
            <h3 style="margin-bottom:0;">{{ strtoupper(config('app.name', 'FAYHOS')) }}</h3>
            <p class="small" style="margin:0;">Bill Statement</p>
            <div class="divider"></div>
        </div>

        <p><strong>Bill:</strong> {{ $bill->bill_number }}</p>
        <p><strong>Date:</strong> {{ $billDate }}</p>
        <p><strong>Patient:</strong> {{ $patientName ?? 'N/A' }}</p>
        <p><strong>Hospital No:</strong> {{ $hospitalNumber ?? 'N/A' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($bill->status) }}</p>

        <div class="divider"></div>
        <table>
            @foreach($bill->services as $service)
                <tr>
                    <td style="width:40%;">{{ Str::limit($service->name, 15) }}</td>
                    <td class="text-right">{{ number_format($service->pivot->unit_price, 2) }}</td>
                    <td class="text-right">{{ $service->pivot->quantity }}</td>
                    <td class="text-right">{{ number_format($service->pivot->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td class="small text-muted">Status: {{ ucfirst($bill->status) }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
            @foreach($bill->investigations as $investigation)
                <tr>
                    <td>{{ Str::limit($investigation->name, 15) }}</td>
                    <td class="text-right">{{ number_format($investigation->pivot->unit_price, 2) }}</td>
                    <td class="text-right">{{ $investigation->pivot->quantity }}</td>
                    <td class="text-right">{{ number_format($investigation->pivot->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td class="small text-muted">Status: {{ ucfirst($bill->status) }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </table>
        <div class="divider"></div>

        <p><strong>Total Due:</strong> {{ number_format($bill->due_amount, 2) }}</p>
        <p><strong>Total Paid:</strong> {{ number_format($bill->totalPaid(), 2) }}</p>
        <p><strong>Balance:</strong> {{ number_format($bill->balance, 2) }}</p>

        <div class="divider"></div>
        <p class="small" style="margin-bottom:0;">Issued by: {{ $bill->issuedBy->name }}</p>
        <p class="small" style="margin-top:0;">Please settle payment promptly.</p>
    </div>
</div>

<style>
    @media print {
        body {
            background: white !important;
        }

        .d-print-none,
        .btn,
        .card-footer,
        .mt-3,
        #bill-preview,
        #thermal-bill-preview-card {
            display: none !important;
        }

        #bill-print-content {
            display: block !important;
            width: 100%;
        }

        .bill-print-card {
            page-break-inside: avoid;
            break-inside: avoid;
            border: 1px solid #ccc;
            padding: 16px;
            width: 100%;
        }

        @page {
            size: A4 portrait;
            margin: 10mm;
        }
    }

    @media screen {
        .thermal-bill {
            font-family: monospace;
        }
    }

    .thermal-bill .divider,
    .thermal-bill-preview .divider {
        border-top: 1px dashed #000;
        margin: 8px 0;
    }

    .thermal-bill table,
    .thermal-bill-preview table {
        width: 100%;
        border-collapse: collapse;
    }

    .thermal-bill td,
    .thermal-bill-preview td {
        padding: 2px 0;
    }

    .thermal-bill .text-right,
    .thermal-bill-preview .text-right {
        text-align: right;
    }

    .thermal-bill-preview p {
        margin-bottom: 0.25rem;
    }
</style>

<script>
    function printThermalBill() {
        var template = document.getElementById('thermal-bill-template');
        if (!template) {
            return alert('Thermal bill template not found.');
        }

        var newWindow = window.open('', '_blank', 'width=360,height=640');
        if (!newWindow) {
            return alert('Please allow popups to print the thermal bill.');
        }

        newWindow.document.write('<html><head><title>Thermal Bill</title>');
        newWindow.document.write('<style>body{margin:8px;font-family:monospace;color:#000;} .divider{border-top:1px dashed #000;margin:8px 0;} table{width:100%;border-collapse:collapse;} td{padding:2px 0;} .text-right{text-align:right;}</style>');
        newWindow.document.write('</head><body>');
        newWindow.document.write(template.innerHTML);
        newWindow.document.write('</body></html>');
        newWindow.document.close();
        newWindow.focus();

        setTimeout(function() {
            newWindow.print();
            newWindow.close();
        }, 300);
    }
</script>
@endsection
