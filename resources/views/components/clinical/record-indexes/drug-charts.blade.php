<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Patient</th>
                <th>Medicine</th>
                <th>Dosage Given</th>
                <th>Comment</th>
                <th>Recorded By</th>
                <th>Time</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                @php($patient = $record->prescriptionItem?->prescription?->patientVisit?->patient)
                <tr>
                    <td><div class="fw-semibold">{{ $patient?->name() ?? 'N/A' }}</div><small class="text-muted">{{ $patient?->hospital_number ?? 'N/A' }}</small></td>
                    <td>{{ $record->medicine?->name ?? 'N/A' }}</td>
                    <td>{{ $record->dosage ?? 'N/A' }}</td>
                    <td>{{ str($record->comment ?? 'N/A')->limit(80) }}</td>
                    <td>{{ $record->dispensedBy?->name ?? 'N/A' }}</td>
                    <td>{{ optional($record->created_at)->format('M d, Y') }} {{ $record->time }}</td>
                    <td class="text-end">
                        @if($patient)
                            <a href="{{ route($config['route'], $patient) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="{{ route('patient.show', $patient) }}" class="btn btn-sm btn-outline-secondary">View Profile</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No drug chart entries found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
