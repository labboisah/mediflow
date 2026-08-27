<!-- Record Metadata -->
<div class="card sticky-top" style="top: 20px;">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Record Details</h5>
    </div>
    <div class="card-body small">
        <div class="mb-3 pb-3 border-bottom">
            <label class="text-muted">Created</label>
            <p class="mb-0">
                <i class="bi bi-calendar"></i> {{ $antenatalCare->created_at->format('M d, Y H:i') }}
            </p>
        </div>

        <div class="mb-3 pb-3 border-bottom">
            <label class="text-muted">Last Updated</label>
            <p class="mb-0">
                <i class="bi bi-calendar"></i> {{ $antenatalCare->updated_at->format('M d, Y H:i') }}
            </p>
        </div>

        <div class="mb-3 pb-3 border-bottom">
            <label class="text-muted">Recorded By</label>
            <p class="mb-0">
                <i class="bi bi-person"></i> {{ $antenatalCare->recordedBy->name ?? 'N/A' }}
            </p>
        </div>

        <div class="mb-3 pb-3 border-bottom">
            <label class="text-muted">Record ID</label>
            <p class="mb-0">
                <code>{{ $antenatalCare->id }}</code>
            </p>
        </div>

        @if(auth()->user()->hasAnyRole(['midwife', 'administrator']))
            <div class="d-grid gap-2">
                <a href="{{ route('midwife.antenatal.edit', $antenatalCare) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i> Edit Record
                </a>
                <form action="{{ route('midwife.antenatal.destroy', $antenatalCare) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('Are you sure?')">
                        <i class="bi bi-trash"></i> Delete Record
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>