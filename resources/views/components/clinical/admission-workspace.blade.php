<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Admission</h1>
            <p class="text-muted mb-0">{{ $patient->name() }} | {{ $patient->hospital_number }}</p>
        </div>
        <a href="{{ route('patient.show', $patient) }}" class="btn btn-outline-secondary">Back</a>
    </div>

    @include('components.clinical._feedback')

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label class="form-label">Ward</label>
                            <select class="form-select @error('wardId') is-invalid @enderror" wire:model.live="wardId">
                                <option value="">Select ward</option>
                                @foreach($wards as $ward)
                                    <option value="{{ $ward->id }}">{{ $ward->name }} - &#8358;{{ number_format((float) $ward->price, 2) }}/day</option>
                                @endforeach
                            </select>
                            @error('wardId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bed</label>
                            <select class="form-select @error('bedId') is-invalid @enderror" wire:model="bedId">
                                <option value="">Select bed</option>
                                @foreach($beds as $bed)
                                    <option value="{{ $bed->id }}">{{ $bed->bed_no }}</option>
                                @endforeach
                            </select>
                            @error('bedId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="row g-2">
                            <div class="col-md-4"><label class="form-label">Days</label><input type="number" min="1" class="form-control" wire:model.live="days"></div>
                            <div class="col-md-4"><label class="form-label">Date</label><input type="date" class="form-control" wire:model="date"></div>
                            <div class="col-md-4"><label class="form-label">Time</label><input type="time" class="form-control" wire:model="time"></div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label class="form-label">Note</label>
                            <textarea class="form-control" rows="3" wire:model="note"></textarea>
                        </div>
                        <div class="alert alert-info">Estimated ward/bed charge: <strong>&#8358;{{ number_format($estimatedAmount, 2) }}</strong></div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-success" type="submit">{{ $editingId ? 'Update Admission' : 'Register Admission' }}</button>
                            @if($editingId)
                                <button type="button" class="btn btn-outline-secondary" wire:click="cancelEdit">Cancel</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h2 class="h6 mb-0">Ward/Bed Charges This Visit</h2></div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light"><tr><th>Ward</th><th>Bed</th><th>Days</th><th>Rate/Day</th><th>Date</th><th>Status</th><th class="text-end">Ward/Bed Charge</th><th></th></tr></thead>
                        <tbody>
                            @forelse($admissions as $admission)
                                @php
                                    $bedBill = $admission->bills->firstWhere('service_description', 'Bed Space Charges') ?? $admission->bills->first();
                                    $daysBilled = (int) ($bedBill?->billServices?->sum('quantity') ?: 1);
                                    $dailyRate = (float) ($admission->bed?->ward?->price ?? $bedBill?->billServices?->first()?->unit_price ?? 0);
                                    $bedCharge = (float) ($bedBill?->due_amount ?? ($dailyRate * $daysBilled));
                                @endphp
                                <tr>
                                    <td>{{ $admission->bed?->ward?->name }}</td>
                                    <td>{{ $admission->bed?->bed_no }}</td>
                                    <td>{{ $daysBilled }}</td>
                                    <td>&#8358;{{ number_format($dailyRate, 2) }}</td>
                                    <td>{{ $admission->date }}</td>
                                    <td>{{ $admission->status }}</td>
                                    <td class="text-end">&#8358;{{ number_format($bedCharge, 2) }}</td>
                                    <td class="text-end"><button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $admission->id }})">Edit</button></td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-muted py-4">No admissions for this visit.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
