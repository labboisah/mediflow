<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Patient</th>
                <th>Ward / Bed</th>
                <th>Date / Time</th>
                <th>Status</th>
                <th>Note</th>
                <th>Admitted By</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                @php($patient = $record->patientVisit?->patient)
                <tr>
                    <td><div class="fw-semibold">{{ $patient?->name() ?? 'N/A' }}</div><small class="text-muted">{{ $patient?->hospital_number ?? 'N/A' }}</small></td>
                    <td>{{ $record->bed?->ward?->name ?? 'N/A' }} / {{ $record->bed?->bed_no ?? 'N/A' }}</td>
                    <td>{{ $record->date }} {{ $record->time }}</td>
                    <td>{{ ucfirst($record->status ?? 'registered') }}</td>
                    <td>{{ str($record->note ?? 'N/A')->limit(80) }}</td>
                    <td>{{ $record->admittedBy?->name ?? 'N/A' }}</td>
                    <td class="text-end">
                        @if($patient)
                            <a href="{{ route($config['route'], $patient) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="{{ route('patient.show', $patient) }}" class="btn btn-sm btn-outline-secondary">View Profile</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No admissions found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
