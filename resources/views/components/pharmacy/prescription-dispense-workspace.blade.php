<div class="container-fluid py-3">
    @php($patient = $prescription->patientVisit?->patient)

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h4 mb-1"><i class="bi bi-prescription2 me-2 text-success"></i>Prescription Dispensing</h1>
            <p class="text-muted mb-0">
                {{ $patient?->demographic?->full_name ?? 'Patient' }} | {{ $patient?->hospital_number ?? 'N/A' }}
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pharmacy.prescriptions.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Prescriptions
            </a>
            @if($unavailableRows->isNotEmpty())
                <button type="button" class="btn btn-outline-primary" onclick="printUnavailablePrescription()">
                    <i class="bi bi-printer me-1"></i> Print Unavailable
                </button>
            @endif
        </div>
    </div>

    @if($isPaid)
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-1"></i>
            This prescription has already been paid. Additional payment is blocked.
        </div>
    @endif

    <div class="row g-3">
        <div class="col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex flex-wrap justify-content-between gap-2">
                        <div>
                            <h2 class="h6 mb-0">Prescribed Medicines</h2>
                            <small class="text-muted">Select available medicines to dispense.</small>
                        </div>
                        <div class="fw-semibold">
                            Selected Bill Amount: &#8358;{{ number_format($selectedTotal, 2) }}
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Dispense</th>
                                <th>Medicine</th>
                                <th>Prescription</th>
                                <th>Stock</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $row)
                                <tr wire:key="prescription-dispense-{{ $row['item_id'] }}">
                                    <td>
                                        <input type="checkbox"
                                               class="form-check-input"
                                               wire:model.live="selected.{{ $row['item_id'] }}"
                                               @disabled($isPaid || $row['available'] <= 0 || $row['status'] !== 'Started')>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $row['medicine'] }}</div>
                                        @if($row['generic'])
                                            <small class="text-muted">{{ $row['generic'] }}</small><br>
                                        @endif
                                        <small class="text-muted">{{ $row['company'] }}</small>
                                    </td>
                                    <td>
                                        <div><strong>Route:</strong> {{ $row['route'] }}</div>
                                        <div><strong>Dosage:</strong> {{ $row['dosage'] }}</div>
                                        <div><strong>Period:</strong> {{ $row['period'] }}</div>
                                        <div><strong>Duration:</strong> {{ $row['duration'] }}</div>
                                        <div><strong>Suggested Qty:</strong> {{ $row['suggested_quantity'] }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $row['available'] > 0 ? 'success' : 'danger' }}">
                                            {{ $row['available'] > 0 ? $row['available'] . ' available' : 'Not available' }}
                                        </span>
                                        @if($row['shortage'] > 0)
                                            <div class="small text-danger">Short by {{ $row['shortage'] }}</div>
                                        @endif
                                        @if($row['status'] !== 'Started')
                                            <div class="small text-warning">Medication stopped</div>
                                        @endif
                                    </td>
                                    <td class="text-end" style="min-width: 110px;">
                                        <input type="number"
                                               min="0"
                                               max="{{ $row['available'] }}"
                                               class="form-control form-control-sm text-end"
                                               wire:model.live="quantities.{{ $row['item_id'] }}"
                                               @disabled($isPaid || $row['available'] <= 0 || $row['status'] !== 'Started')>
                                        <small class="text-muted">&#8358;{{ number_format($row['unit_price'], 2) }} each</small>
                                    </td>
                                    <td class="text-end">&#8358;{{ number_format($row['selected'] ? $row['amount'] : 0, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">Amount to Collect</th>
                                <th class="text-end">&#8358;{{ number_format($selectedTotal, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0">Payment</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select @error('paymentMethodId') is-invalid @enderror" wire:model="paymentMethodId" @disabled($isPaid)>
                            <option value="">Select method</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                        @error('paymentMethodId')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference</label>
                        <input type="text" class="form-control" wire:model="referenceNumber" placeholder="Optional POS/transfer reference" @disabled($isPaid)>
                    </div>
                    @error('selected')<div class="alert alert-danger py-2">{{ $message }}</div>@enderror
                    <button type="button" class="btn btn-primary w-100" wire:click="dispense" wire:loading.attr="disabled" @disabled($isPaid)>
                        <span wire:loading.remove wire:target="dispense">{{ $isPaid ? 'Already Paid' : 'Record Payment & Dispense' }}</span>
                        <span wire:loading wire:target="dispense">Saving...</span>
                    </button>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0">Unavailable / Shortage</h2>
                </div>
                <div class="card-body">
                    @forelse($unavailableRows as $row)
                        <div class="border-bottom py-2">
                            <div class="fw-semibold">{{ $row['medicine'] }}</div>
                            <div class="small text-muted">{{ $row['dosage'] }} | {{ $row['period'] }} | {{ $row['duration'] }}</div>
                            <div class="small text-danger">
                                Required {{ $row['suggested_quantity'] }}, available {{ $row['available'] }}
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">All prescribed medicines are available.</div>
                    @endforelse
                </div>
            </div>

            @if($receiptTransaction)
                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h6 mb-0">Receipt Ready</h2>
                            <small>{{ $receiptTransaction->payment?->payment_id }}</small>
                        </div>
                        <button type="button" class="btn btn-light btn-sm" onclick="printDispenseReceipt()">
                            <i class="bi bi-printer me-1"></i> Thermal
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="text-muted small">Amount Paid</div>
                        <div class="h5 mb-0">&#8358;{{ number_format($receiptTransaction->payment?->amount ?? 0, 2) }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div id="unavailable-prescription-print" class="d-none">
        <div class="print-prescription">
            <div class="text-center">
                <h4>{{ strtoupper(config('app.title') ?? config('app.name')) }}</h4>
                <div>{{ config('app.address') }}</div>
                <h5 class="mt-2">Unavailable Prescription Items</h5>
            </div>
            <hr>
            <p><strong>Patient:</strong> {{ $patient?->demographic?->full_name ?? 'N/A' }}</p>
            <p><strong>Hospital No:</strong> {{ $patient?->hospital_number ?? 'N/A' }}</p>
            <p><strong>Doctor:</strong> {{ $prescription->prescribedBy?->name ?? 'N/A' }} | {{ $prescription->prescribedBy?->department?->name ?? 'N/A' }}</p>
            <p><strong>Treatment:</strong> {{ $prescription->treatment_diagnosis ?? 'N/A' }}</p>
            <table>
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Route</th>
                        <th>Dosage</th>
                        <th>Period</th>
                        <th>Duration</th>
                        <th>Needed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unavailableRows as $row)
                        <tr>
                            <td>{{ $row['medicine'] }}</td>
                            <td>{{ $row['route'] }}</td>
                            <td>{{ $row['dosage'] }}</td>
                            <td>{{ $row['period'] }}</td>
                            <td>{{ $row['duration'] }}</td>
                            <td>{{ max(1, $row['shortage'] ?: $row['suggested_quantity']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="mt-3"><strong>Pharmacy Note:</strong> The medicines listed above are not available or not fully available at this pharmacy.</p>
            <p><strong>Date:</strong> {{ now()->format('d M, Y h:i A') }}</p>
        </div>
    </div>

    @if($receiptTransaction)
        <div id="dispense-receipt-print" class="d-none">
            <div class="thermal-receipt">
                <div class="text-center">
                    <h5>{{ strtoupper(config('app.title') ?? config('app.name')) }}</h5>
                    <div>{{ strtoupper(config('app.address') ?? '') }}</div>
                    <strong>PHARMACY RECEIPT</strong>
                </div>
                <div class="divider"></div>
                <p><strong>Receipt:</strong> {{ $receiptTransaction->payment?->payment_id }}</p>
                <p><strong>Bill:</strong> {{ $receiptTransaction->bill?->bill_number }}</p>
                <p><strong>Patient:</strong> {{ $patient?->demographic?->full_name ?? 'N/A' }}</p>
                <p><strong>Hospital No:</strong> {{ $patient?->hospital_number ?? 'N/A' }}</p>
                <p><strong>Method:</strong> {{ $receiptTransaction->payment?->paymentMethod?->name ?? 'N/A' }}</p>
                <div class="divider"></div>
                <table>
                    @foreach($receiptTransaction->stockTransactionItems as $item)
                        <tr>
                            <td>{{ \Illuminate\Support\Str::limit($item->medicineBatch?->medicine?->name ?? 'N/A', 20) }}</td>
                            <td class="text-right">{{ $item->quantity }} x {{ number_format($item->price, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="small">{{ $item->prescriptionItem?->dosage }} | {{ $item->prescriptionItem?->duration }}</td>
                            <td class="text-right">{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </table>
                <div class="divider"></div>
                <p><strong>Total Paid:</strong> {{ number_format($receiptTransaction->payment?->amount ?? 0, 2) }}</p>
                <p><strong>Served By:</strong> {{ $receiptTransaction->createdBy?->name ?? 'System' }}</p>
                <div class="divider"></div>
                <p class="text-center">Thank you.</p>
            </div>
        </div>
    @endif

    <style>
        .thermal-receipt,
        .print-prescription {
            font-family: monospace;
            color: #000;
        }

        .thermal-receipt {
            width: 72mm;
            max-width: 72mm;
            font-size: 13.5px;
            line-height: 1.25;
        }

        .thermal-receipt p {
            margin: 0 0 3px;
        }

        .thermal-receipt table,
        .print-prescription table {
            width: 100%;
            border-collapse: collapse;
        }

        .thermal-receipt td,
        .print-prescription th,
        .print-prescription td {
            padding: 3px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        .thermal-receipt .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .text-right {
            text-align: right;
        }
    </style>

    @push('scripts')
        <script>
            function openPrintWindow(html, title, width = 420, height = 700, thermal = false) {
                const printWindow = window.open('', '_blank', `width=${width},height=${height}`);
                if (!printWindow) {
                    alert('Please allow popups to print.');
                    return;
                }

                printWindow.document.write('<html><head><title>' + title + '</title>');
                if (thermal) {
                    printWindow.document.write('<style>body{margin:8px;font-family:monospace;color:#000;} .divider{border-top:1px dashed #000;margin:8px 0;} table{width:100%;border-collapse:collapse;} td{padding:2px 0;border-bottom:0;vertical-align:top;} .text-center{text-align:center;} .text-right{text-align:right;} .small{font-size:12px;} h5{margin:0 0 3px;} p{margin:0 0 3px;}</style>');
                } else {
                    printWindow.document.write('<style>@page{size:A4 portrait;margin:12mm;} body{margin:0;font-family:monospace;color:#000;font-size:13px;} table{width:100%;border-collapse:collapse;} th,td{padding:4px;border-bottom:1px solid #ddd;vertical-align:top;} .text-center{text-align:center;} .text-right{text-align:right;} .small{font-size:12px;}</style>');
                }
                printWindow.document.write('</head><body>' + html + '</body></html>');
                printWindow.document.close();
                printWindow.focus();

                setTimeout(function () {
                    printWindow.print();
                    printWindow.close();
                }, 300);
            }

            function printUnavailablePrescription() {
                const template = document.getElementById('unavailable-prescription-print');
                if (template) {
                    openPrintWindow(template.innerHTML, 'Unavailable Prescription', 780, 900);
                }
            }

            function printDispenseReceipt() {
                const template = document.getElementById('dispense-receipt-print');
                if (template) {
                    openPrintWindow(template.innerHTML, 'Pharmacy Receipt', 420, 700, true);
                }
            }

            document.addEventListener('livewire:init', function () {
                Livewire.on('print-prescription-dispense-receipt', function () {
                    setTimeout(printDispenseReceipt, 400);
                });
            });
        </script>
    @endpush
</div>
