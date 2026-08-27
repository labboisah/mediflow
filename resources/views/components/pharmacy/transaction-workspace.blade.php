<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h4 mb-1"><i class="bi bi-receipt me-2 text-success"></i>Transaction</h1>
            <p class="text-muted mb-0">Select medicines, collect payment, create bill, and print receipt.</p>
        </div>
        <a href="{{ route('pharmacy.transactions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-list-ul me-1"></i> Transactions
        </a>
    </div>

    <div class="row g-3">
        <div class="col-xl-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0">Medicine Selection</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Search Medicine</label>
                        <input type="search" class="form-control" wire:model.live.debounce.300ms="search" placeholder="Name, generic, company, or batch">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" min="1" class="form-control @error('quantity') is-invalid @enderror" wire:model="quantity">
                        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    @error('batchId')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

                    <div class="list-group pharmacy-search-results">
                        @forelse($batches as $batch)
                            <button type="button" class="list-group-item list-group-item-action" wire:click="addBatchToCart({{ $batch->id }})">
                                <div class="d-flex justify-content-between gap-2">
                                    <div>
                                        <div class="fw-semibold">{{ $batch->medicine?->name }}</div>
                                        <div class="small text-muted">
                                            {{ $batch->medicine?->manufacturer ?? 'N/A' }} | Batch {{ $batch->batch_number }} | Exp {{ $batch->expiry_date }}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-semibold">&#8358;{{ number_format($batch->selling_price, 2) }}</div>
                                        <div class="small {{ $batch->quantity_remaining <= 10 ? 'text-danger' : 'text-success' }}">
                                            {{ $batch->quantity_remaining }} left
                                        </div>
                                    </div>
                                </div>
                            </button>
                        @empty
                            <div class="list-group-item text-muted">No available medicine matches your search.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h2 class="h6 mb-0">Cart</h2>
                    @if(count($cart) > 0)
                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="clearCart" wire:confirm="Clear all items from this cart?">
                            <i class="bi bi-trash me-1"></i> Clear
                        </button>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Batch</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-end"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cart as $index => $item)
                                <tr wire:key="cart-item-{{ $item['batch_id'] }}">
                                    <td>{{ $item['medicine'] }}</td>
                                    <td>{{ $item['batch_number'] }}</td>
                                    <td class="text-end">&#8358;{{ number_format($item['price'], 2) }}</td>
                                    <td class="text-end">{{ $item['quantity'] }}</td>
                                    <td class="text-end">&#8358;{{ number_format($item['subtotal'], 2) }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeFromCart({{ $index }})">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No medicine added yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Total</th>
                                <th class="text-end">&#8358;{{ number_format($total, 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-body border-top">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label">Payment Method</label>
                            <select class="form-select @error('paymentMethodId') is-invalid @enderror" wire:model="paymentMethodId">
                                <option value="">Select method</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                                @endforeach
                            </select>
                            @error('paymentMethodId')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Payment Reference</label>
                            <input type="text" class="form-control" wire:model="referenceNumber" placeholder="Optional POS/transfer reference">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary w-100" wire:click="completeTransaction" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="completeTransaction">Pay</span>
                                <span wire:loading wire:target="completeTransaction">Saving...</span>
                            </button>
                        </div>
                    </div>
                    @error('cart')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                </div>
            </div>

            @if($receiptTransaction)
                <div class="card shadow-sm mt-3" id="receipt-preview">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h6 mb-0">Receipt Ready</h2>
                            <small>{{ $receiptTransaction->payment?->payment_id }} | {{ $receiptTransaction->bill?->bill_number }}</small>
                        </div>
                        <button type="button" class="btn btn-light btn-sm" onclick="printPharmacyThermalReceipt()">
                            <i class="bi bi-printer me-1"></i> Print Thermal
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="text-muted small">Amount Paid</div>
                                <div class="fw-bold">&#8358;{{ number_format($receiptTransaction->payment?->amount ?? 0, 2) }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Payment Method</div>
                                <div class="fw-bold">{{ $receiptTransaction->payment?->paymentMethod?->name ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Collected By</div>
                                <div class="fw-bold">{{ $receiptTransaction->createdBy?->name ?? 'System' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pharmacy-thermal-receipt" class="d-none">
                    <div class="thermal-receipt">
                        <div class="text-center">
                            <h5 class="mb-1">{{ strtoupper(config('app.title') ?? config('app.name')) }}</h5>
                            <div>{{ strtoupper(config('app.address') ?? '') }}</div>
                            <div class="fw-bold mt-1">PHARMACY RECEIPT</div>
                        </div>
                        <div class="divider"></div>
                        <p><strong>Receipt:</strong> {{ $receiptTransaction->payment?->payment_id }}</p>
                        <p><strong>Bill:</strong> {{ $receiptTransaction->bill?->bill_number }}</p>
                        <p><strong>Date:</strong> {{ $receiptTransaction->payment?->payment_date?->format('M d, Y h:i A') }}</p>
                        <p><strong>Method:</strong> {{ $receiptTransaction->payment?->paymentMethod?->name ?? 'N/A' }}</p>
                        @if($receiptTransaction->payment?->reference_number)
                            <p><strong>Ref:</strong> {{ $receiptTransaction->payment->reference_number }}</p>
                        @endif
                        <div class="divider"></div>
                        <table>
                            @foreach($receiptTransaction->stockTransactionItems as $item)
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
                        <p><strong>Total Paid:</strong> {{ number_format($receiptTransaction->payment?->amount ?? 0, 2) }}</p>
                        <p><strong>Served By:</strong> {{ $receiptTransaction->createdBy?->name ?? 'System' }}</p>
                        <div class="divider"></div>
                        <p class="text-center">Thank you.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .thermal-receipt {
            font-family: monospace;
            color: #000;
            width: 72mm;
            max-width: 72mm;
            font-size: 13.5px;
            line-height: 1.25;
        }

        .thermal-receipt p {
            margin: 0 0 3px;
        }

        .thermal-receipt table {
            width: 100%;
            border-collapse: collapse;
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

        .pharmacy-search-results {
            max-height: 480px;
            overflow-y: auto;
        }
    </style>

    @push('scripts')
        <script>
            function printPharmacyThermalReceipt() {
                var template = document.getElementById('pharmacy-thermal-receipt');

                if (!template) {
                    return alert('Pharmacy receipt template not found.');
                }

                var printWindow = window.open('', '_blank', 'width=360,height=640');

                if (!printWindow) {
                    return alert('Please allow popups to print the thermal receipt.');
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

            document.addEventListener('livewire:init', function () {
                Livewire.on('print-pharmacy-thermal', function () {
                    setTimeout(printPharmacyThermalReceipt, 400);
                });
            });
        </script>
    @endpush
</div>
