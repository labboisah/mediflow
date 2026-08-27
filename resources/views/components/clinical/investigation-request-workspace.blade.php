<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Investigation Request</h1>
            <p class="text-muted mb-0">{{ $patient->name() }} | {{ $patient->hospital_number }}</p>
        </div>
        <a href="{{ route('patient.show', $patient) }}" class="btn btn-outline-secondary">Back</a>
    </div>

    @include('components.clinical._feedback')

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Clinical Diagnoses</label>
                                    <textarea class="form-control @error('clinicalDiagnoses') is-invalid @enderror" rows="3" wire:model="clinicalDiagnoses"></textarea>
                                    @error('clinicalDiagnoses') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Discount (%)</label>
                                <input type="number" min="0" max="100" class="form-control @error('discount') is-invalid @enderror" wire:model="discount" />
                                    @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        @foreach($rows as $index => $row)
                            <div class="border rounded p-3 mb-3" wire:key="investigation-row-{{ $index }}">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label">Type</label>
                                        <select class="form-select" wire:model.live="rows.{{ $index }}.type_id">
                                            <option value="">Select type</option>
                                            @foreach($types as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Investigation</label>
                                        <select class="form-select" wire:model="rows.{{ $index }}.investigation_id">
                                            <option value="">Select investigation</option>
                                            @foreach($investigations->where('investigation_type_id', $row['type_id']) as $investigation)
                                                <option value="{{ $investigation->id }}">{{ $investigation->name }} - &#8358;{{ number_format((float) $investigation->price, 2) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Specimen</label>
                                        <input class="form-control" wire:model="rows.{{ $index }}.specimen">
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger" wire:click="removeRow({{ $index }})" @disabled($editingRequestId)>x</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary" wire:click="addRow" @disabled($editingRequestId)>Add Investigation</button>
                            <button type="submit" class="btn btn-success">{{ $editingRequestId ? 'Update Request & Bill' : 'Register Request & Bill' }}</button>
                            @if($editingRequestId)
                                <button type="button" class="btn btn-outline-secondary" wire:click="cancelEdit">Cancel</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h2 class="h6 mb-0">Recent Requests</h2></div>
                <div class="list-group list-group-flush">
                    @forelse($recentRequests as $request)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between gap-2">
                                <div class="fw-semibold">{{ $request->investigation?->name }}</div>
                                <strong>&#8358;{{ number_format((float) ($request->bill?->due_amount ?? $request->investigation?->price ?? 0), 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <div class="small text-muted">{{ $request->lab_no ?? 'Pending lab number' }} | Bill: {{ $request->bill?->bill_number ?? 'N/A' }} | {{ $request->bill?->status ?? 'pending' }}</div>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary" wire:click="editRequest({{ $request->id }})">Edit</button>
                                    <button type="button" class="btn btn-outline-danger" wire:click="deleteRequest({{ $request->id }})" wire:confirm="Remove this investigation request and update the bill?">Remove</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-muted">No recent requests.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
