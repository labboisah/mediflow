<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Patient</th>
                <th>Date / Time</th>
                <th>Input</th>
                <th>Output</th>
                <th>Balance</th>
                <th>Recorded By</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                @php
                    $patient = $record->admission?->patientVisit?->patient;
                    $totalIn = (float) ($record->oral ?? 0) + (float) ($record->iv ?? 0);
                    $totalOut = (float) ($record->urine ?? 0) + (float) ($record->faces ?? 0);
                @endphp
                <tr>
                    <td><div class="fw-semibold">{{ $patient?->name() ?? 'N/A' }}</div><small class="text-muted">{{ $patient?->hospital_number ?? 'N/A' }}</small></td>
                    <td>{{ $record->date }} {{ $record->time }}</td>
                    <td>Oral {{ $record->oral ?? 0 }} / IV {{ $record->iv ?? 0 }}</td>
                    <td>Urine {{ $record->urine ?? 0 }} / Faeces {{ $record->faces ?? 0 }}</td>
                    <td>{{ number_format($totalIn - $totalOut, 2) }}</td>
                    <td>{{ $record->recordedBy?->name ?? 'N/A' }}</td>
                    <td class="text-end">
                        @if($patient)
                            <a href="{{ route($config['route'], $patient) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="{{ route('patient.show', $patient) }}" class="btn btn-sm btn-outline-secondary">View Profile</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No fluid balance entries found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
