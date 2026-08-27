<?php

namespace App\Http\Controllers\Radiology;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\InvestigationRequest;

class RequestController extends Controller
{
    public function index()
    {
        $requests = auth()->user()->department->investigationRequests()
            ->with([
                'bill',
                'patientVisit.patient.demographic',
                'requestedBy',
                'performedBy',
                'investigation',
            ])
            ->orderByDesc('created_at')
            ->get()
            ->filter(fn ($investigationRequest) => $investigationRequest->bill !== null);

        return view('radiology.request.index', compact('requests'));
    }

    public function createResult(InvestigationRequest $investigationRequest)
    {
        return view('radiology.request.create-result', compact('investigationRequest'));
    }

    public function storeResult(Request $request, InvestigationRequest $investigationRequest)
    {
        $validated = $request->validate([
            'parameters' => 'sometimes|array',
            'parameters.*' => 'nullable|string',
            'result_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $hasResult = false;

        foreach ($validated['parameters'] ?? [] as $parameterId => $value) {
            if (trim((string) $value) === '') {
                continue;
            }

            $investigationRequest->investigationResults()->updateOrCreate(
                ['parameter_id' => $parameterId],
                ['value' => $value]
            );

            $hasResult = true;
        }

        if ($request->hasFile('result_image')) {
            if ($investigationRequest->result_image) {
                Storage::disk('public')->delete($investigationRequest->result_image);
            }

            $investigationRequest->result_image = $request->file('result_image')->store('radiology-results', 'public');
            $hasResult = true;
        }

        if (! $hasResult) {
            return back()->withInput()->with('error', 'Please provide at least one radiology result or upload an image.');
        }

        $investigationRequest->update([
            'performed_by' => auth()->id(),
            'completed_at' => now(),
            'status' => 'Completed',
            'result_image' => $investigationRequest->result_image,
        ]);

        return redirect()->route('radiology.requests.index')->with('success', 'Investigation result recorded successfully.');
    }

    public function show(InvestigationRequest $investigationRequest)
    {
        $investigationRequest->load([
            'investigation.parameters',
            'investigationResults.parameter',
            'patientVisit.patient.demographic',
            'walkinPatient',
            'requestedBy',
            'performedBy',
        ]);

        if ($investigationRequest->patientVisit) {
            $patientName = $investigationRequest->patientVisit->patient->demographic->full_name;
            $hospitalNumber = $investigationRequest->patientVisit->patient->hospital_number ?? null;
        } else {
            $patientName = $investigationRequest->walkinPatient->name ?? 'Walkin Patient';
            $hospitalNumber = null;
        }

        return view('radiology.request.result', compact('investigationRequest', 'patientName', 'hospitalNumber'));
    }

    public function editResult(InvestigationRequest $investigationRequest)
    {
        $investigationRequest->load([
            'investigation.parameters',
            'investigationResults.parameter',
            'patientVisit.patient.demographic',
            'walkinPatient',
        ]);

        return view('radiology.request.edit-result', compact('investigationRequest'));
    }

    public function updateResult(Request $request, InvestigationRequest $investigationRequest)
    {
        $validated = $request->validate([
            'parameters' => 'sometimes|array',
            'parameters.*' => 'nullable|string',
            'result_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'remove_image' => 'nullable|boolean',
        ]);

        $hasChange = false;

        foreach ($validated['parameters'] ?? [] as $parameterId => $value) {
            if (trim((string) $value) === '') {
                // delete existing result if present? keep as is — skip
                continue;
            }

            $investigationRequest->investigationResults()->updateOrCreate(
                ['parameter_id' => $parameterId],
                ['value' => $value]
            );

            $hasChange = true;
        }

        if (!empty($validated['remove_image']) && $validated['remove_image']) {
            if ($investigationRequest->result_image) {
                Storage::disk('public')->delete($investigationRequest->result_image);
                $investigationRequest->result_image = null;
                $hasChange = true;
            }
        }

        if ($request->hasFile('result_image')) {
            if ($investigationRequest->result_image) {
                Storage::disk('public')->delete($investigationRequest->result_image);
            }

            $investigationRequest->result_image = $request->file('result_image')->store('radiology-results', 'public');
            $hasChange = true;
        }

        if (! $hasChange) {
            return back()->with('warning', 'No changes detected.')->withInput();
        }

        $investigationRequest->update([
            'performed_by' => auth()->id(),
            'completed_at' => now(),
            'status' => 'Completed',
            'result_image' => $investigationRequest->result_image,
        ]);

        return redirect()->route('radiology.requests.show', $investigationRequest)->with('success', 'Investigation result updated successfully.');
    }
}
