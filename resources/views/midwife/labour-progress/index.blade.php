@extends('layouts.app')

@section('title', 'Labour Progress - ' . $labour->patient->full_name)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="bi bi-list-check"></i> Labour Progress Records</h1>
            <small class="text-muted">{{ $labour->patient->full_name }} - Labour on {{ $labour->labour_onset_time ? $labour->labour_onset_time->format('M d, Y H:i') : 'N/A' }}</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('midwife.labour.progress.create', $labour) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> New Progress
            </a>
            <a href="{{ route('midwife.labour.show', $labour) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Labour
            </a>
        </div>
    </div>

    @if($progressRecords->isEmpty())
        <div class="alert alert-info">No progress entries found.</div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date / Time</th>
                                <th>Dilation</th>
                                <th>Effacement</th>
                                <th>Contractions</th>
                                <th>FHR</th>
                                <th>Rec. by</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($progressRecords as $progress)
                                <tr>
                                    <td>{{ $progress->recorded_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $progress->cervical_dilation ?? 'N/A' }} cm</td>
                                    <td>{{ $progress->cervical_effacement ?? 'N/A' }}%</td>
                                    <td>{{ $progress->contraction_frequency ?? 'N/A' }}/10m ({{ $progress->contraction_intensity ?? 'N/A' }})</td>
                                    <td>{{ $progress->fetal_heart_rate ?? 'N/A' }} bpm</td>
                                    <td>{{ $progress->recordedBy->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('midwife.labour-progress.show', $progress) }}" class="btn btn-outline-info" title="View"><i class="bi bi-eye"></i></a>
                                            <a href="{{ route('midwife.labour-progress.edit', $progress) }}" class="btn btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                            <form action="{{ route('midwife.labour-progress.destroy', $progress) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Delete this progress record?')"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection