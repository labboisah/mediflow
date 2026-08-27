<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Patient</th>
                <th>Date / Time</th>
                <th>Temp</th>
                <th>BP</th>
                <th>Pulse / Resp.</th>
                <th>Remark</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                @php($patient = $record->patientVisit?->patient)
                <tr>
                    <td><div class="fw-semibold">{{ $patient?->name() ?? 'N/A' }}</div><small class="text-muted">{{ $patient?->hospital_number ?? 'N/A' }}</small></td>
                    <td>{{ $record->date }} {{ $record->time }}</td>
                    <td>{{ $record->temperature ?? 'N/A' }}</td>
                    <td>{{ $record->blood_pressure ?? 'N/A' }}</td>
                    <td>{{ $record->mate_pulse ?? 'N/A' }} / {{ $record->respiration ?? 'N/A' }}</td>
                    <td>{{ str($record->remark ?? 'N/A')->limit(80) }}</td>
                    <td class="text-end">
                        @if($patient)
                            <a href="{{ route($config['route'], $patient) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="{{ route('patient.show', $patient) }}" class="btn btn-sm btn-outline-secondary">View Profile</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No observations found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
