@extends('layouts.app')

@section('title', 'Pharmacy Receipt')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
                <a href="{{ route('pharmacy.finance.payments') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Payments
                </a>
                <button onclick="printPharmacyFinanceReceipt()" class="btn btn-primary">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>

            <div class="card shadow-sm" id="pharmacy-receipt">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Pharmacy Payment Receipt</h5>
                            <small>Receipt: {{ $payment->payment_id }}</small>
                        </div>
                        <i class="bi bi-capsule-pill fs-1"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="text-muted small">Bill Number</div>
                            <div class="fw-semibold">{{ $transaction->bill?->bill_number ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Payment Method</div>
                            <div class="fw-semibold">{{ $payment->paymentMethod?->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Reference</div>
                            <div class="fw-semibold">{{ $payment->reference_number ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Date</div>
                            <div class="fw-semibold">{{ $payment->payment_date?->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Collected By</div>
                            <div class="fw-semibold">{{ $payment->recordedBy?->name ?? 'System' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Status</div>
                            <span class="badge bg-success">{{ ucfirst($payment->status) }}</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Medicine</th>
                                    <th>Batch</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->stockTransactionItems as $item)
                                    <tr>
                                        <td>{{ $item->medicineBatch?->medicine?->name ?? 'N/A' }}</td>
                                        <td>{{ $item->medicineBatch?->batch_number ?? 'N/A' }}</td>
                                        <td class="text-end">&#8358;{{ number_format($item->price, 2) }}</td>
                                        <td class="text-end">{{ $item->quantity }}</td>
                                        <td class="text-end">&#8358;{{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Amount Paid</th>
                                    <th class="text-end">&#8358;{{ number_format($payment->amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="text-center text-muted small mt-4">
                        <p class="mb-1">This receipt is proof of pharmacy payment.</p>
                        <p class="mb-0">{{ config('app.title') }} | {{ config('app.address') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="pharmacy-finance-receipt-print" class="d-none">
    <div class="thermal-receipt">
        <div class="text-center">
            <h5>{{ strtoupper(config('app.title') ?? config('app.name')) }}</h5>
            <div>{{ strtoupper(config('app.address') ?? '') }}</div>
            <strong>PHARMACY RECEIPT</strong>
        </div>
        <div class="divider"></div>
        <p><strong>Receipt:</strong> {{ $payment->payment_id }}</p>
        <p><strong>Bill:</strong> {{ $transaction->bill?->bill_number ?? 'N/A' }}</p>
        <p><strong>Date:</strong> {{ $payment->payment_date?->format('M d, Y h:i A') }}</p>
        <p><strong>Method:</strong> {{ $payment->paymentMethod?->name ?? 'N/A' }}</p>
        @if($payment->reference_number)
            <p><strong>Ref:</strong> {{ $payment->reference_number }}</p>
        @endif
        <div class="divider"></div>
        <table>
            @foreach($transaction->stockTransactionItems as $item)
                <tr>
                    <td>{{ \Illuminate\Support\Str::limit($item->medicineBatch?->medicine?->name ?? 'N/A', 22) }}</td>
                    <td class="text-right">{{ $item->quantity }} x {{ number_format($item->price, 2) }}</td>
                </tr>
                <tr>
                    <td class="small">Batch {{ $item->medicineBatch?->batch_number ?? 'N/A' }}</td>
                    <td class="text-right">{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </table>
        <div class="divider"></div>
        <p><strong>Total Paid:</strong> {{ number_format($payment->amount, 2) }}</p>
        <p><strong>Collected By:</strong> {{ $payment->recordedBy?->name ?? 'System' }}</p>
        <div class="divider"></div>
        <p class="text-center">Thank you.</p>
    </div>
</div>

<style>
    .thermal-receipt {
        color: #000;
        font-family: monospace;
        font-size: 13.5px;
        line-height: 1.25;
        max-width: 72mm;
        width: 72mm;
    }

    .thermal-receipt p {
        margin: 0 0 3px;
    }

    .thermal-receipt table {
        border-collapse: collapse;
        width: 100%;
    }

    .thermal-receipt td {
        padding: 2px 0;
        vertical-align: top;
    }

    .thermal-receipt .divider {
        border-top: 1px dashed #000;
        margin: 6px 0;
    }

    .thermal-receipt .text-right {
        text-align: right;
    }

    @media print {
        .d-print-none,
        .admin-sidebar,
        .hospital-navbar {
            display: none !important;
        }

        #pharmacy-receipt {
            box-shadow: none !important;
            border: 0 !important;
            font-size: 14px;
            line-height: 1.25;
            width: 100%;
        }

        #pharmacy-receipt .card-header,
        #pharmacy-receipt .card-body {
            padding: 8px 10px;
        }

        #pharmacy-receipt .row {
            --bs-gutter-y: .35rem;
        }

        #pharmacy-receipt .table {
            margin-bottom: 0;
        }

        #pharmacy-receipt .table th,
        #pharmacy-receipt .table td {
            padding: 4px 6px;
        }

        @page {
            size: A4 portrait;
            margin: 8mm;
        }
    }
</style>

<script>
    function printPharmacyFinanceReceipt() {
        var template = document.getElementById('pharmacy-finance-receipt-print');

        if (!template) {
            return alert('Pharmacy receipt template not found.');
        }

        var printWindow = window.open('', '_blank', 'width=360,height=640');

        if (!printWindow) {
            return alert('Please allow popups to print the pharmacy receipt.');
        }

        printWindow.document.write('<html><head><title>Pharmacy Receipt</title>');
        printWindow.document.write('<style>body{margin:8px;font-family:monospace;color:#000;} .divider{border-top:1px dashed #000;margin:8px 0;} table{width:100%;border-collapse:collapse;} td{padding:2px 0;vertical-align:top;} .text-right{text-align:right;} .text-center{text-align:center;} .fw-bold{font-weight:700;} .small{font-size:12px;} h5{margin:0 0 3px;} p{margin:0 0 3px;}</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(template.innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();

        setTimeout(function () {
            printWindow.print();
            printWindow.close();
        }, 300);
    }
</script>
@endsection
