@extends('layouts.app')

@section('title', 'Patient Newborn Records')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-folder2-open"></i> Newborn Records for {{ $patient->full_name }}</h1>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Registry #</th>
                            <th>Delivery ID</th>
                            <th>Birth Date/Time</th>
                            <th>Sex</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($newborns as $newborn)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $newborn->newborn_registration_number }}</td>
                                <td>{{ $newborn->delivery_id }}</td>
                                <td>{{ $newborn->birth_date_time?->format('M d, Y H:i') }}</td>
                                <td>{{ ucfirst($newborn->sex) }}</td>
                                <td>{{ ucfirst($newborn->status) }}</td>
                                <td>
                                    <a href="{{ route('midwife.newborn.show', $newborn) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('midwife.newborn.edit', $newborn) }}" class="btn btn-sm btn-warning">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No newborns recorded for this patient.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection