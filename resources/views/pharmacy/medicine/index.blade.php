@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h4><i class="bi bi-capsule"></i> Medicines</h4>

        <a href="{{ route('pharmacy.medicines.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Medicine
        </a>
    </div>

    <div class="card shadow-sm">

        <div class="table-responsive">

            <table class="table table-striped datatable">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Generic</th>
                        <th>Form</th>
                        <th>Manufacturer</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($medicines as $medicine)

                    <tr>

                        <td>{{ $medicine->name }}</td>
                        <td>{{ $medicine->medicineType?->name ?? 'N/A' }}</td>
                        <td>{{ $medicine->generic_name }}</td>
                        <td>{{ $medicine->form }}</td>
                        <td>{{ $medicine->manufacturer }}</td>

                        <td>
                        <span class="badge bg-success">
                        {{ $medicine->availableQuantity() }}
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
