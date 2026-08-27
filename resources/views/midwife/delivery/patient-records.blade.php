@extends('layouts.app')

@section('title', 'Patient Delivery Records')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-folder2-open"></i> Delivery Records for {{ $patient->full_name }}</h1>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Delivery Date/Time</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveries as $delivery)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $delivery->delivery_date_time?->format('M d, Y H:i') }}</td>
                                <td>{{ ucfirst($delivery->delivery_type) }}</td>
                                <td>{{ ucfirst($delivery->status) }}</td>
                                <td>
                                    <a href="{{ route('midwife.delivery.show', $delivery) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('midwife.delivery.edit', $delivery) }}" class="btn btn-sm btn-warning">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No deliveries recorded for this patient.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection