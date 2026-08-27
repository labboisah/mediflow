<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Bill;
use App\Models\Patient;
use App\Models\PatientAdmission;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PatientRegisterController extends Controller
{
    public function index(Request $request)
    {
        [$startDate, $endDate] = $this->dateRange($request);
        $query = $this->registerQuery($startDate, $endDate, $request);
        $summary = $this->registerSummary(clone $query);

        $patients = (clone $query)
            ->withCount('patientVisits')
            ->latest('registration_date')
            ->paginate(25)
            ->withQueryString();

        return view('admin.patient.register-report', compact('patients', 'summary', 'startDate', 'endDate'));
    }

    public function csv(Request $request)
    {
        [$startDate, $endDate] = $this->dateRange($request);
        $patients = $this->registerQuery($startDate, $endDate, $request)
            ->withCount('patientVisits')
            ->latest('registration_date')
            ->get();

        $filename = 'admin-patient-register-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.csv';
        $handle = fopen('php://memory', 'w');

        fputcsv($handle, [
            'Hospital Number',
            'Patient Name',
            'Gender',
            'Age',
            'Phone Number',
            'File Type',
            'Patient Type',
            'Visits',
            'Registration Date',
        ]);

        foreach ($patients as $patient) {
            fputcsv($handle, [
                $patient->hospital_number,
                $patient->demographic?->full_name,
                $patient->demographic?->gender,
                $patient->demographic?->age,
                $patient->demographic?->phone_number,
                $patient->fileType?->name,
                $patient->is_walkIn ? 'Walk-in' : 'Registered',
                $patient->patient_visits_count,
                $patient->registration_date?->format('Y-m-d H:i:s'),
            ]);
        }

        rewind($handle);

        return response(stream_get_contents($handle), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    public function pdf(Request $request)
    {
        [$startDate, $endDate] = $this->dateRange($request);
        $query = $this->registerQuery($startDate, $endDate, $request);
        $summary = $this->registerSummary(clone $query);
        $patients = (clone $query)->withCount('patientVisits')->latest('registration_date')->get();
        $hospital = $this->hospitalHeaderData();
        $generatedBy = $request->user();

        $pdf = Pdf::loadView('admin.patient.register-pdf', compact('patients', 'summary', 'startDate', 'endDate', 'hospital', 'generatedBy'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('admin-patient-register-' . now()->format('Y-m-d') . '.pdf');
    }

    public function summary(Patient $patient)
    {
        $patient->load(['demographic', 'nextOfKin', 'fileType']);

        $visits = $patient->patientVisits()
            ->with([
                'createdBy',
                'bills.payments.paymentMethod',
                'bills.issuedBy',
                'admissions.bed.ward',
                'admissions.admittedBy',
                'admissions.discharge.dischargedBy',
                'visitActivities',
                'serviceRequests.service',
                'investigationRequests.investigation',
                'vitalSigns',
            ])
            ->latest('created_at')
            ->get();

        $bills = Bill::query()
            ->with(['payments.paymentMethod', 'issuedBy'])
            ->whereHas('patientVisit', fn (Builder $query) => $query->where('patient_id', $patient->id))
            ->latest('issued_date')
            ->get();

        $payments = Payment::query()
            ->with(['bill', 'paymentMethod', 'recordedBy'])
            ->whereHas('bill.patientVisit', fn (Builder $query) => $query->where('patient_id', $patient->id))
            ->latest('payment_date')
            ->get();

        $admissions = Admission::query()
            ->with(['patientVisit', 'bed.ward', 'admittedBy', 'discharge.dischargedBy'])
            ->whereHas('patientVisit', fn (Builder $query) => $query->where('patient_id', $patient->id))
            ->latest('created_at')
            ->get();

        $legacyAdmissions = PatientAdmission::query()
            ->with(['admittedBy', 'dischargedBy'])
            ->where('patient_id', $patient->id)
            ->latest('admission_date')
            ->get();

        $stats = [
            'visits' => $visits->count(),
            'admissions' => $admissions->count() + $legacyAdmissions->count(),
            'discharges' => $admissions->filter(fn ($admission) => $admission->discharge || strtolower((string) $admission->status) === 'discharged')->count()
                + $legacyAdmissions->whereNotNull('discharge_date')->count(),
            'bills' => $bills->count(),
            'billed' => $bills->sum('amount'),
            'due' => $bills->sum('due_amount'),
            'paid' => $payments->where('status', 'completed')->sum('amount'),
            'payments' => $payments->count(),
        ];

        return view('admin.patient.summary', compact('patient', 'visits', 'bills', 'payments', 'admissions', 'legacyAdmissions', 'stats'));
    }

    private function dateRange(Request $request): array
    {
        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:Male,Female,Other'],
            'patient_type' => ['nullable', 'in:registered,walk_in'],
        ]);

        $startDate = Carbon::parse($request->input('start_date', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', now()->format('Y-m-d')))->endOfDay();

        if ($startDate->gt($endDate)) {
            return [$endDate->copy()->startOfDay(), $startDate->copy()->endOfDay()];
        }

        return [$startDate, $endDate];
    }

    private function registerQuery(Carbon $startDate, Carbon $endDate, Request $request): Builder
    {
        $search = trim((string) $request->input('search', ''));

        return Patient::query()
            ->with(['demographic', 'fileType'])
            ->whereBetween('registration_date', [$startDate, $endDate])
            ->when(strlen($search) >= 2, function (Builder $query) use ($search) {
                $query->where(function (Builder $builder) use ($search) {
                    $builder->where('hospital_number', 'like', "%{$search}%")
                        ->orWhereHas('demographic', function (Builder $demographic) use ($search) {
                            $demographic->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('phone_number', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('gender'), function (Builder $query) use ($request) {
                $query->whereHas('demographic', fn (Builder $demographic) => $demographic->where('gender', $request->input('gender')));
            })
            ->when($request->input('patient_type') === 'registered', fn (Builder $query) => $query->where('is_walkIn', false))
            ->when($request->input('patient_type') === 'walk_in', fn (Builder $query) => $query->where('is_walkIn', true));
    }

    private function registerSummary(Builder $query): array
    {
        $patients = $query->get();

        return [
            'total' => $patients->count(),
            'registered' => $patients->where('is_walkIn', false)->count(),
            'walk_in' => $patients->where('is_walkIn', true)->count(),
            'male' => $patients->filter(fn ($patient) => $patient->demographic?->gender === 'Male')->count(),
            'female' => $patients->filter(fn ($patient) => $patient->demographic?->gender === 'Female')->count(),
            'other' => $patients->filter(fn ($patient) => $patient->demographic?->gender === 'Other')->count(),
        ];
    }

    private function hospitalHeaderData(): array
    {
        return [
            'name' => strtoupper(config('app.title', config('app.name', 'FAYHOS'))),
            'address' => strtoupper(config('app.address', '')),
            'logo' => public_path('images/logo.png'),
        ];
    }
}
