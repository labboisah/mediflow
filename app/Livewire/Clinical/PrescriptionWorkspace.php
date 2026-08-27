<?php

namespace App\Livewire\Clinical;

use App\Livewire\Clinical\Concerns\ManagesClinicalVisit;
use App\Models\Medicine;
use App\Models\MedicineType;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Route as MedicineRoute;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.live')]
class PrescriptionWorkspace extends Component
{
    use ManagesClinicalVisit;

    public ?int $prescriptionId = null;
    public ?int $editingItemId = null;
    public string $medicineName = '';
    public string $medicineTypeId = '';
    public string $routeId = '';
    public string $dosage = '';
    public string $period = '';
    public string $duration = '';
    public string $treatmentDiagnosis = '';

    public function mount(Patient $patient): void
    {
        $this->patient = $patient;
        $requestedPrescriptionId = request()->integer('prescription');

        if ($requestedPrescriptionId) {
            $this->prescriptionId = $this->currentVisit()
                ->prescriptions()
                ->whereKey($requestedPrescriptionId)
                ->value('id');
        } else {
            $this->prescriptionId = $this->currentVisit()
                ->prescriptions()
                ->where('prescribe_by', auth()->id())
                ->where('status', 'active')
                ->latest()
                ->value('id');
        }

        $this->treatmentDiagnosis = (string) ($this->prescription()?->treatment_diagnosis ?? '');
    }

    public function render()
    {
        return view('components.clinical.prescription-workspace', [
            'medicines' => Medicine::with('batches')->orderBy('name')->get(),
            'medicineTypes' => MedicineType::orderBy('name')->get(),
            'routes' => MedicineRoute::orderBy('name')->get(),
            'prescription' => $this->prescription(),
        ]);
    }

    public function addItem(): void
    {
        abort_unless(auth()->user()->hasRole('doctor'), 403);

        $validated = $this->validate([
            'medicineName' => ['required', 'string', 'max:255'],
            'medicineTypeId' => ['nullable', 'integer', 'exists:medicine_types,id'],
            'routeId' => ['required', 'integer', 'exists:routes,id'],
            'dosage' => ['required', 'string', 'max:255'],
            'period' => ['required', 'string', 'max:255'],
            'duration' => ['required', 'string', 'max:255'],
            'treatmentDiagnosis' => ['required', 'string', 'max:1000'],
        ]);

        $medicine = $this->resolveMedicine($validated['medicineName'], $validated['medicineTypeId'] ?? null);

        if (! $medicine) {
            $this->feedback('Type or select a medicine.', 'warning');
            return;
        }

        $prescription = $this->prescription() ?: $this->currentVisit()->prescriptions()->create([
            'prescribe_by' => auth()->id(),
            'status' => 'active',
            'treatment_diagnosis' => $validated['treatmentDiagnosis'],
        ]);

        $this->prescriptionId = $prescription->id;
        $prescription->update(['treatment_diagnosis' => $validated['treatmentDiagnosis']]);

        $payload = [
            'medicine_id' => $medicine->id,
            'route_id' => (int) $validated['routeId'],
            'dosage' => $validated['dosage'],
            'period' => $validated['period'],
            'duration' => $validated['duration'],
        ];

        if ($this->editingItemId) {
            $prescription->prescriptionItems()->findOrFail($this->editingItemId)->update($payload);
            $this->logActivity("Prescription item updated for medicine: {$medicine->name}");
        } else {
            $prescription->prescriptionItems()->create($payload);
            $this->logActivity("Prescription item added for medicine: {$medicine->name}");
        }

        $this->editingItemId = null;
        $this->resetItemForm();
        $this->feedback('Medicine saved to prescription.');
    }

    public function editItem(int $itemId): void
    {
        abort_unless(auth()->user()->hasRole('doctor'), 403);

        $item = $this->prescription()?->prescriptionItems()->with('medicine')->findOrFail($itemId);

        $this->editingItemId = $item->id;
        $this->medicineName = (string) $item->medicine?->name;
        $this->medicineTypeId = (string) ($item->medicine?->medicine_type_id ?? '');
        $this->routeId = (string) $item->route_id;
        $this->dosage = (string) $item->dosage;
        $this->period = (string) $item->period;
        $this->duration = (string) $item->duration;
        $this->treatmentDiagnosis = (string) ($this->prescription()?->treatment_diagnosis ?? '');
    }

    public function removeItem(int $itemId): void
    {
        abort_unless(auth()->user()->hasRole('doctor'), 403);

        $prescription = $this->prescription();
        $item = $prescription?->prescriptionItems()->findOrFail($itemId);
        $medicineName = $item?->medicine?->name;
        $item?->drugCharts()->delete();
        $item?->delete();
        $this->logActivity("Prescription item removed: {$medicineName}");
        $this->feedback('Medicine removed from prescription.', 'danger');
    }

    public function startMedication(int $itemId): void
    {
        abort_unless(auth()->user()->hasRole('doctor'), 403);

        $item = $this->prescription()?->prescriptionItems()->with('medicine')->findOrFail($itemId);
        $item->startMedication(auth()->id());
        $this->logActivity("Medication started: {$item->medicine?->name}");
        $this->feedback('Medication started.');
    }

    public function stopMedication(int $itemId): void
    {
        abort_unless(auth()->user()->hasRole('doctor'), 403);

        $item = $this->prescription()?->prescriptionItems()->with('medicine')->findOrFail($itemId);
        $item->stopMedication(auth()->id());
        $this->logActivity("Medication stopped: {$item->medicine?->name}");
        $this->feedback('Medication stopped.', 'warning');
    }

    public function submitPrescription(): void
    {
        $prescription = $this->prescription();

        if (! $prescription || $prescription->prescriptionItems()->count() === 0) {
            $this->feedback('Add at least one medicine before submitting to pharmacy.', 'warning');
            return;
        }

        if (blank($prescription->treatment_diagnosis)) {
            $this->feedback('Enter the treatment, infection, or disease before submitting.', 'warning');
            return;
        }

        $prescription->update(['status' => 'submitted']);
        $this->logActivity('Prescription submitted to pharmacy');
        $this->feedback('Prescription submitted to pharmacy.');
    }

    private function prescription(): ?Prescription
    {
        return $this->prescriptionId
            ? Prescription::with(['prescriptionItems.medicine.batches', 'prescriptionItems.route'])->find($this->prescriptionId)
            : null;
    }

    private function resolveMedicine(string $medicineName, ?string $medicineTypeId): ?Medicine
    {
        $medicineName = trim($medicineName);
        $medicines = Medicine::with('batches')->get();

        $medicine = $medicines->first(fn (Medicine $medicine) => $medicine->displayName() === $medicineName)
            ?? $medicines->first(fn (Medicine $medicine) => strcasecmp($medicine->name, $medicineName) === 0);

        if ($medicine) {
            return $medicine;
        }

        if (! $medicineTypeId) {
            return null;
        }

        return Medicine::firstOrCreate([
            'name' => $medicineName,
        ], [
            'medicine_type_id' => (int) $medicineTypeId,
        ]);
    }

    private function resetItemForm(): void
    {
        $this->reset(['medicineName', 'medicineTypeId', 'routeId', 'dosage', 'period', 'duration']);
        $this->resetValidation();
    }

    public function cancelEdit(): void
    {
        $this->editingItemId = null;
        $this->resetItemForm();
    }
}
