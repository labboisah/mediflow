<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Prescription</h1>
            <p class="text-muted mb-0">{{ $patient->name() }} | {{ $patient->hospital_number }}</p>
        </div>
        <a href="{{ route('patient.show', $patient) }}" class="btn btn-outline-secondary">Back</a>
    </div>

    @include('components.clinical._feedback')

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h2 class="h6 mb-0">Add Medicine</h2></div>
                <div class="card-body">
                    <form wire:submit.prevent="addItem">
                        <div class="mb-3">
                            <label class="form-label">Treatment / Infection / Disease</label>
                            <textarea class="form-control @error('treatmentDiagnosis') is-invalid @enderror" rows="2" wire:model.live="treatmentDiagnosis" placeholder="Indicate diagnosis, infection, or disease being treated"></textarea>
                            @error('treatmentDiagnosis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Medicine</label>
                            <input class="form-control @error('medicineName') is-invalid @enderror" list="medicine-options" wire:model.live="medicineName" placeholder="Type or select medicine">
                            <datalist id="medicine-options">
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->name }}" label="{{ $medicine->displayName() }}"></option>
                                @endforeach
                            </datalist>
                            @error('medicineName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Existing medicines show stock status; type a new name to add one.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Medicine Type</label>
                            <select class="form-select" wire:model="medicineTypeId">
                                <option value="">Select type</option>
                                @foreach($medicineTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Route</label>
                            <select class="form-select @error('routeId') is-invalid @enderror" wire:model="routeId">
                                <option value="">Select route</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}">{{ $route->name }}</option>
                                @endforeach
                            </select>
                            @error('routeId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label">Dosage</label>
                                <input class="form-control @error('dosage') is-invalid @enderror" wire:model="dosage">
                                @error('dosage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Period</label>
                                <input class="form-control @error('period') is-invalid @enderror" wire:model="period">
                                @error('period') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Duration</label>
                                <input class="form-control @error('duration') is-invalid @enderror" wire:model="duration">
                                @error('duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-success" type="submit">{{ $editingItemId ? 'Update Medicine' : 'Add Medicine' }}</button>
                            @if($editingItemId)
                                <button type="button" class="btn btn-outline-secondary" wire:click="cancelEdit">Cancel</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h2 class="h6 mb-0">Prescription Items</h2>
                    <button class="btn btn-primary btn-sm" wire:click="submitPrescription">Submit to Pharmacy</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Medicine</th><th>Company</th><th>Stock</th><th class="text-end">Amount</th><th>Route</th><th>Dosage</th><th>Period</th><th>Duration</th><th>Status</th><th></th></tr>
                        </thead>
                        <tbody>
                            @forelse($prescription?->prescriptionItems ?? [] as $item)
                                <tr wire:key="prescription-item-{{ $item->id }}">
                                    <td>
                                        <div class="fw-semibold">{{ $item->medicine?->name }}</div>
                                        @if($item->medicine?->generic_name)
                                            <small class="text-muted">{{ $item->medicine->generic_name }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $item->medicine?->manufacturer ?? 'N/A' }}</td>
                                    <td>
                                        @php($quantity = $item->medicine?->availableQuantity() ?? 0)
                                        <span class="badge bg-{{ $quantity > 0 ? 'success' : 'danger' }}">
                                            {{ $quantity > 0 ? 'Available: ' . $quantity : 'Not available' }}
                                        </span>
                                    </td>
                                    <td class="text-end">&#8358;{{ number_format($item->medicine?->latestSellingPrice() ?? 0, 2) }}</td>
                                    <td>{{ $item->route?->name }}</td>
                                    <td>{{ $item->dosage }}</td>
                                    <td>{{ $item->period }}</td>
                                    <td>{{ $item->duration }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->isStarted() ? 'success' : 'secondary' }}">
                                            {{ $item->isStarted() ? 'Started' : 'Stopped' }}
                                        </span>
                                        @if($item->medication_status_changed_at)
                                            <div class="small text-muted">{{ $item->medication_status_changed_at->format('M d, h:i A') }}</div>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if(auth()->user()->hasRole('doctor'))
                                            <div class="d-flex justify-content-end gap-1">
                                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="editItem({{ $item->id }})">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeItem({{ $item->id }})" wire:confirm="Delete this medicine from the prescription?">
                                                    Delete
                                                </button>
                                                @if($item->isStarted())
                                                    <button type="button" class="btn btn-sm btn-outline-warning" wire:click="stopMedication({{ $item->id }})">
                                                        Stop
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline-success" wire:click="startMedication({{ $item->id }})">
                                                        Start
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="10" class="text-center text-muted py-4">No medicine added yet.</td></tr>
                            @endforelse
                        </tbody>
                        @if($prescription?->prescriptionItems?->count())
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Prescription Amount</th>
                                    <th class="text-end">&#8358;{{ number_format($prescription->prescriptionItems->sum(fn($item) => $item->medicine?->latestSellingPrice() ?? 0), 2) }}</th>
                                    <th colspan="6"></th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
