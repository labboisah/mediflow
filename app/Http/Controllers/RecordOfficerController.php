<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientDemographic;
use App\Models\NextOfKin;
use App\Models\PatientVisit;
use App\Models\Service;
use App\Models\PatientAdmission;
use App\Models\PatientReferral;
use App\Models\Ward;
use App\Models\Bill;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class RecordOfficerController extends Controller
{
    /**
     * Dashboard
     */
    public function dashboard()
    {
        $totalPatients = Patient::count();
        $totalAppointments = Appointment::where('appointment_date', '>=', now()->startOfDay())->count();
        $recentPatients = Patient::with('demographic')
            ->latest('registration_date')
            ->limit(10)
            ->get();
        $upcomingAppointments = Appointment::with('patient.demographic')
            ->where('status', '!=', 'Cancelled')
            ->where('appointment_date', '>=', now()->startOfDay())
            ->orderBy('appointment_date')
            ->limit(5)
            ->get();

        return view('record.dashboard', compact(
            'totalPatients',
            'totalAppointments',
            'recentPatients',
            'upcomingAppointments'
        ));
    }

    /**
     * Show patient registration form
     */
    public function registerForm()
    
    {
        return view('record.patient.register');
    }

    /**
     * Register new patient
     */
    public function register(Request $request)
    {
       
        $validated = $request->validate([
            'file_type' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date',
            'lga' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'address' => 'nullable|string|max:500',
            'phone_number' => 'required|string|unique:patient_demographics,phone_number',
            'email' => 'nullable|email|unique:patient_demographics,email',
            'is_walkIn' => 'boolean',
            'nok_name' => 'required|string|max:255',
            'nok_relationship' => 'required|string|max:255',
            'nok_contact_address' => 'nullable|string|max:500',
            'nok_telephone' => 'required|string|max:20',
            'discount' => 'required|numeric|min:0|max:100',
        ]);
       

       
            // Create patient record
            $patient = Patient::create([
                'file_type_id'=>$request->file_type,
                'hospital_number' => Patient::generateHospitalNumber(),
                'registration_date' => now(),
                'is_walkIn' => $validated['is_walkIn'] ?? false,
            ]);

            // Calculate age
            $age = Carbon::parse($validated['date_of_birth'])->age;

            // Create demographic record
            PatientDemographic::create([
                'patient_id' => $patient->id,
                'first_name' => strtoupper($validated['first_name']),
                'last_name' => strtoupper($validated['last_name']),
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
                'age' => $age,
                'lga_id' => $validated['lga'],
                'occupation' => $validated['occupation'],
                'marital_status' => $validated['marital_status'],
                'address' => $validated['address'],
                'phone_number' => $validated['phone_number'],
                'email' => $validated['email'],
            ]);

            // Create next of kin record
            NextOfKin::create([
                'patient_id' => $patient->id,
                'name' => $validated['nok_name'],
                'relationship' => $validated['nok_relationship'],
                'contact_address' => $validated['nok_contact_address'],
                'telephone' => $validated['nok_telephone'],
            ]);
            
            // generate file opening bill with discount
        
            $visit = $patient->registerNewVisit();

            // record activity
            $visit->visitActivities()->create([
                'recorded_by' => auth()->user()->id,
                'activity' => "File Open" 
            ]);

            $discount = $validated['discount'] ?? 0;
            $patient->generateFileOpeningBill($visit, $discount, $request->anc ?? false);

            


            $visit->visitActivities()->create([
                'recorded_by' => auth()->user()->id,
                'activity' => "Visit Registerred"
            ]); 
            
            return redirect()->route('patient.show', $patient->id)
                ->with('success', "Patient {$patient->demographic->full_name} registered successfully with Hospital Number: {$patient->hospital_number}");
    }
    


    /**
     * List all patients
     */
    public function listPatients()
    {
        return view('record.patient.list');
    }

    public function patientRegister(Request $request)
    {
        [$startDate, $endDate] = $this->patientRegisterDateRange($request);
        $query = $this->patientRegisterQuery($startDate, $endDate, $request);
        $summary = $this->patientRegisterSummary(clone $query);

        $patients = (clone $query)
            ->latest('registration_date')
            ->paginate(25)
            ->withQueryString();

        return view('record.patient.register-report', compact('patients', 'summary', 'startDate', 'endDate'));
    }

    public function patientRegisterCsv(Request $request)
    {
        [$startDate, $endDate] = $this->patientRegisterDateRange($request);
        $patients = $this->patientRegisterQuery($startDate, $endDate, $request)
            ->latest('registration_date')
            ->get();

        $filename = 'patient-register-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.csv';
        $handle = fopen('php://memory', 'w');

        fputcsv($handle, [
            'Hospital Number',
            'Patient Name',
            'Gender',
            'Age',
            'Phone Number',
            'Email',
            'Address',
            'File Type',
            'Patient Type',
            'Registration Date',
        ]);

        foreach ($patients as $patient) {
            fputcsv($handle, [
                $patient->hospital_number,
                $patient->demographic?->full_name,
                $patient->demographic?->gender,
                $patient->demographic?->age,
                $patient->demographic?->phone_number,
                $patient->demographic?->email,
                $patient->demographic?->address,
                $patient->fileType?->name,
                $patient->is_walkIn ? 'Walk-in' : 'Registered',
                $patient->registration_date?->format('Y-m-d H:i:s'),
            ]);
        }

        rewind($handle);

        return response(stream_get_contents($handle), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    public function patientRegisterPdf(Request $request)
    {
        [$startDate, $endDate] = $this->patientRegisterDateRange($request);
        $query = $this->patientRegisterQuery($startDate, $endDate, $request);
        $summary = $this->patientRegisterSummary(clone $query);
        $patients = (clone $query)->latest('registration_date')->get();
        $hospital = $this->hospitalHeaderData();
        $generatedBy = $request->user();

        $pdf = Pdf::loadView('record.patient.register-pdf', compact('patients', 'summary', 'startDate', 'endDate', 'hospital', 'generatedBy'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('patient-register-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Show patient details
     */
    public function showPatient(Patient $patient)
    {
        $patient->load([
            'demographic',
            'nextOfKin',
            'visits',
            'appointments',
        ]);

        return view('record.patient.show', compact('patient'));
    }

    /**
     * Show edit patient form
     */
    public function editForm(Patient $patient)
    {
        $patient->load('demographic', 'nextOfKin');
        return view('record.patient.edit', compact('patient'));
    }

    /**
     * Update patient information
     */
    public function update(Request $request, Patient $patient)
    {

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date',
            'lga' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'address' => 'nullable|string|max:500',
            'phone_number' => 'required|string|unique:patient_demographics,phone_number,' . $patient->demographic->id,
            'email' => 'nullable|email|unique:patient_demographics,email,' . $patient->demographic->id,
            'nok_name' => 'required|string|max:255',
            'nok_relationship' => 'required|string|max:255',
            'nok_contact_address' => 'nullable|string|max:500',
            'nok_telephone' => 'required|string|max:20',
        ]);

        

            // Calculate age
            $age = Carbon::parse($validated['date_of_birth'])->age;

            // Update demographic
            $patient->demographic->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
                'age' => $age,
                'lga_id' => $validated['lga'],
                'occupation' => $validated['occupation'],
                'marital_status' => $validated['marital_status'],
                'address' => $validated['address'],
                'phone_number' => $validated['phone_number'],
                'email' => $validated['email'],
            ]);

            // Update next of kin
            $patient->nextOfKin->update([
                'name' => $validated['nok_name'],
                'relationship' => $validated['nok_relationship'],
                'contact_address' => $validated['nok_contact_address'],
                'telephone' => $validated['nok_telephone'],
            ]);

            

            return redirect()->route('record.patients.show', $patient)
                ->with('success', 'Patient information updated successfully');

        
    }

    /**
     * Search patients
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query || strlen($query) < 2) {
            return view('patient.search', ['patients' => []]);
        }

        $patients = Patient::with('demographic')
            ->where('hospital_number', 'like', "%{$query}%")
            ->orWhereHas('demographic', function ($q) use ($query) {
                $q->where('phone_number', 'like', "%{$query}%")
                  ->orWhere('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%");
            })
            ->get();

        return view('patient.search', compact('patients', 'query'));
    }

    
    /**
     * Record patient visit
     */
    public function visitForm(Patient $patient)
    {
        $patient->load('demographic');
        return view('record.visit.create', compact('patient'));
    }

    /**
     *  patient visit
     */
    public function storeVisit(Request $request, Patient $patient)
    {

        $validated = $request->validate([
            'visit_date' => 'required',
            'visit_type' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $visit = PatientVisit::create([
                'patient_id' => $patient->id,
                'visit_date' => $validated['visit_date'],
                'visit_type' => Service::find($validated['visit_type'])->name,
                'status' => 'Active',
                'created_by' => auth()->id(),
            ]);

            $service = Service::find($validated['visit_type']);

            $visit->generateServiceBillOf($service);

            DB::commit();
            // redirect to patient visit bill creation
            return redirect()->route('patient.show', $patient)
                ->with('success', 'Visit record created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to record visit. ' . $e->getMessage()])->withInput();
        }
    }

    
    
   
    /**
     * Wrapper: Create bill (for dual-role users)
     * Redirects to accountant bill creation with patient pre-selected
     */
    public function createBill(Patient $patient)
    {
        // Check if user has accountant role
        if (!auth()->user()->hasRole('accountant') && !auth()->user()->hasRole('record')) {
            return redirect()->back()
                ->with('error', 'You need accountant and record officer roles to generate bills.');
        }

        return redirect()->route('accountant.bills.create', ['patient_id' => $patient->id])
            ->with('info', 'Creating bill for ' . $patient->demographic->full_name);
    }

    /**
     * Wrapper: Create payment (for dual-role users)
     * Redirects to accountant payment creation with patient pre-selected
     */
    public function createPayment(Patient $patient)
    {
        // Check if user has accountant role
        if (!Auth::user()->hasRole('accountant')) {
            return redirect()->back()
                ->with('error', 'You need accountant role to record payments.');
        }

        return redirect()->route('accountant.payments.create', $patient)
            ->with('info', 'Recording payment for ' . $patient->demographic->full_name);
    }

    /**
     * Generate PDF export
     */
    private function generatePDF($patient)
    {
        // Placeholder for PDF generation
        // You can use libraries like DomPDF or TCPDF
        $content = "Patient Record Export\n";
        $content .= "Hospital Number: " . $patient->hospital_number . "\n";
        $content .= "Name: " . $patient->demographic->full_name . "\n";

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, 'patient_' . $patient->hospital_number . '.txt');
    }

    private function patientRegisterDateRange(Request $request): array
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

    private function patientRegisterQuery(Carbon $startDate, Carbon $endDate, Request $request): Builder
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

    private function patientRegisterSummary(Builder $query): array
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
