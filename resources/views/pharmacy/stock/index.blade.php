@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h4><i class="bi bi-box-seam"></i> Medicine Stocks</h4>

        <a href="{{ route('pharmacy.stocks.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Stock
        </a>
    </div>

    <div class="card shadow-sm">

        <div class="table-responsive">

            <table class="table table-striped datatable">

                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Batch No</th>
                        <th>Quantity Recieved</th>
                        <th>Purchase Price</th>
                        <th>Selling Price</th>
                        <th>Manufacturing Date</th>
                        <th>Expiry Date</th>
                        <th>Quantity Remaining</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($batches as $batch)

                    <tr>

                        <td>{{ $batch->medicine?->name ?? 'N/A' }}</td>
                        <td>{{ $batch->batch_number }}</td>
                        <td>{{ $batch->quantity_received }}</td>
                        <td>{{ $batch->purchase_price }}</td>
                        <td>{{ $batch->selling_price }}</td>
                        <td>{{ $batch->manufacture_date }}</td>
                        <td>{{ $batch->expiry_date }}</td>

                        <td>
                        <span class="badge bg-success">
                        {{ $batch->quantity_remaining }}
                        </span>
                        </td>

                        <td>

                        <a href="#" class="btn btn-sm btn-info">
                        <i class="bi bi-eye"></i>
                        </a>

                        <a href="#" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i>
                        </a>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
