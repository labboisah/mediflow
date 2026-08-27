<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Patient</th>
                <th>Investigation</th>
                <th>Diagnosis</th>
                <th>Specimen</th>
                <th>Status</th>
                <th>Requested By</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                @php($patient = $record->patientVisit?->patient)
                <tr>
                    <td><div class="fw-semibold">{{ $patient?->name() ?? 'N/A' }}</div><small class="text-muted">{{ $patient?->hospital_number ?? 'N/A' }}</small></td>
                    <td>{{ $record->investigation?->name ?? 'N/A' }}</td>
                    <td>{{ str($record->clinical_diagnoses ?? 'N/A')->limit(80) }}</td>
                    <td>{{ $record->specimen ?? 'N/A' }}</td>
                    <td>{{ $record->status ?? 'Pending' }}</td>
                    <td>{{ $record->requestedBy?->name ?? 'N/A' }}</td>
                    <td class="text-end">
                        @if($patient)
                            <a href="{{ route($config['route'], ['patient' => $patient, 'request' => $record->id]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="{{ route('patient.show', $patient) }}" class="btn btn-sm btn-outline-secondary">View Profile</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No investigation requests found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
