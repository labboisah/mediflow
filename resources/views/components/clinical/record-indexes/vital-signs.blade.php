<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Patient</th>
                <th>Temp</th>
                <th>BP</th>
                <th>Pulse / Resp.</th>
                <th>SpO2</th>
                <th>Recorded By</th>
                <th>Date</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                @php($patient = $record->patientVisit?->patient)
                <tr>
                    <td><div class="fw-semibold">{{ $patient?->name() ?? 'N/A' }}</div><small class="text-muted">{{ $patient?->hospital_number ?? 'N/A' }}</small></td>
                    <td>{{ $record->body_temperature ?? 'N/A' }}</td>
                    <td>{{ $record->blood_pressure_systolic ?? 'N/A' }}/{{ $record->blood_pressure_diastolic ?? 'N/A' }}</td>
                    <td>{{ $record->heart_rate ?? 'N/A' }} / {{ $record->respiratory_rate ?? 'N/A' }}</td>
                    <td>{{ $record->oxygen_saturation ?? 'N/A' }}</td>
                    <td>{{ $record->recordedBy?->name ?? 'N/A' }}</td>
                    <td>{{ optional($record->recorded_date)->format('M d, Y h:i A') }}</td>
                    <td class="text-end">
                        @if($patient)
                            <a href="{{ route($config['route'], $patient) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="{{ route('patient.show', $patient) }}" class="btn btn-sm btn-outline-secondary">View Profile</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No vital signs found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
