@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-4">
        <i class="bi bi-receipt"></i> Transactions
        </h4>

        <div class="d-flex gap-2">
            <a href="{{ route('pharmacy.transactions.report') }}" class="btn btn-outline-secondary">
            <i class="bi bi-graph-up"></i> Report
            </a>
            <a href="{{ route('pharmacy.transactions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Transaction
            </a>
        </div>
    </div>

    <div class="card shadow-sm p-4">

        <div class="table-responsive">

            <table class="table table-striped datatable">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicines</th>
                        <th>Total Amount</th>
                        <th>Reference</th>
                        <th>Bill</th>
                        <th>Payment</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($transactions as $transaction)

                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>
                            @foreach($transaction->stockTransactionItems as $item)
                                {{ $item->medicineBatch?->medicine?->name ?? 'N/A' }},
                            @endforeach
                        </td>
                        <td>{{ $transaction->total_amount }}</td>
                        <td>{{ $transaction->reference }}</td>
                        <td>{{ $transaction->bill?->bill_number ?? 'N/A' }}</td>
                        <td>{{ $transaction->payment?->payment_id ?? 'N/A' }}</td>
                        <td>{{ $transaction->createdBy?->name ?? 'System' }}</td>
                        
                        <td>
                        @if($transaction->payment)
                            <a href="{{ route('pharmacy.finance.payments.receipt', $transaction->payment) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-receipt"></i>
                            </a>
                        @endif

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
