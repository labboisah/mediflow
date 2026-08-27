<div wire:poll.10s>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="row align-items-center g-2">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check me-1 text-primary"></i>
                        Investigation Requests
                    </h5>
                </div>

                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input
                            type="text"
                            wire:model.live.debounce.500ms="search"
                            class="form-control"
                            placeholder="Search patient, hospital number, investigation, status..."
                        >
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div wire:loading.delay class="alert alert-info py-2">
                <i class="bi bi-arrow-repeat me-1"></i>
                Updating records...
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Patient Name</th>
                            <th>Hospital Number</th>
                            <th>Investigation</th>
                            <th>Requested At</th>
                            <th>Requested By</th>
                            <th>Payment Status</th>
                            <th>Completed At</th>
                            <th>Performed By</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($requests as $index => $investigationRequest)
                            @php
                                $patientName = $investigationRequest->bill?->patientName() ?? 'N/A';

                                $hospitalNumber = $investigationRequest->patientVisit?->patient?->hospital_number
                                    ?? 'Walk in Patient';
                            @endphp

                            <tr wire:key="investigation-request-{{ $investigationRequest->id }}">
                                <td>
                                    {{ $requests->firstItem() + $index }}
                                </td>

                                <td>
                                    {{ $patientName }}
                                </td>

                                <td>
                                    {{ $hospitalNumber }}
                                </td>

                                <td>
                                    {{ $investigationRequest->investigation?->name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $investigationRequest->requested_at
                                        ? \Carbon\Carbon::parse($investigationRequest->requested_at)->format('d M Y h:i A')
                                        : 'N/A' }}
                                </td>

                                <td>
                                    {{ $investigationRequest->requestedBy?->name ?? 'N/A' }}
                                </td>

                                <td>
                                    @if ($investigationRequest->bill?->status === 'paid')
                                        <span class="badge bg-success">
                                            Paid
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            No Payment Recorded
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $investigationRequest->completed_at
                                        ? \Carbon\Carbon::parse($investigationRequest->completed_at)->format('d M Y h:i A')
                                        : 'N/A' }}
                                </td>

                                <td>
                                    {{ $investigationRequest->performedBy?->name ?? 'N/A' }}
                                </td>

                                <td class="text-end">
                                    @if ($investigationRequest->bill?->status === 'paid')
                                        <a href="{{ route('lab.requests.createResult', $investigationRequest) }}"
                                           class="btn btn-sm btn-outline-primary me-1">
                                            <i class="bi bi-save"></i>
                                            Save
                                        </a>

                                        @if ($investigationRequest->completed_at)
                                            <a href="{{ route('lab.requests.show', $investigationRequest) }}"
                                               class="btn btn-sm btn-outline-success me-1">
                                                <i class="bi bi-printer"></i>
                                                Print
                                            </a>

                                            <a href="{{ route('lab.requests.editResult', $investigationRequest) }}"
                                               class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-pencil"></i>
                                                Edit
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted">
                                            No Payment Recorded
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle me-1"></i>
                                    No investigation requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>