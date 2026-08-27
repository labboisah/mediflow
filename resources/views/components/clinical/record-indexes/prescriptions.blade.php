<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Patient</th>
                <th>Treatment / Disease</th>
                <th>Medicines</th>
                <th>Status</th>
                <th>Prescribed By</th>
                <th>Date</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                @php($patient = $record->patientVisit?->patient)
                <tr>
                    <td><div class="fw-semibold">{{ $patient?->name() ?? 'N/A' }}</div><small class="text-muted">{{ $patient?->hospital_number ?? 'N/A' }}</small></td>
                    <td>{{ str($record->treatment_diagnosis ?? 'N/A')->limit(80) }}</td>
                    <td>{{ $record->prescriptionItems->pluck('medicine.name')->filter()->take(3)->implode(', ') ?: 'N/A' }}</td>
                    <td>{{ ucfirst($record->status ?? 'active') }}</td>
                    <td>{{ $record->prescribedBy?->name ?? 'N/A' }}</td>
                    <td>{{ optional($record->created_at)->format('M d, Y h:i A') }}</td>
                    <td class="text-end">
                        @if($patient)
                            <a href="{{ route($config['route'], $patient) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="{{ route('patient.show', $patient) }}" class="btn btn-sm btn-outline-secondary">View Profile</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No prescriptions found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
