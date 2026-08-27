<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\Bed;
use App\Models\Bill;
use App\Models\Department;
use App\Models\Investigation;
use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\Payment;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Service;
use App\Models\SyncOperation;
use App\Models\TemporaryPermission;
use App\Models\User;
use App\Models\WalkinPatient;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.live')]
class Dashboard extends Component
{
    public function render()
    {
        return view('components.admin.dashboard', [
            'accessMetrics' => $this->accessMetrics(),
            'hospitalMetrics' => $this->hospitalMetrics(),
            'financeMetrics' => $this->financeMetrics(),
            'setupMetrics' => $this->setupMetrics(),
            'syncMetrics' => $this->syncMetrics(),
            'visitStatusRows' => $this->visitStatusRows(),
            'billStatusRows' => $this->billStatusRows(),
            'recentActivities' => $this->recentActivities(),
            'lastUpdated' => now(),
            'canViewTechnicalRecords' => auth()->user()?->hasRole('administrator') ?? false,
        ]);
    }

    private function accessMetrics(): array
    {
        return [
            'roles' => Role::count(),
            'permissions' => Permission::count(),
            'users' => User::count(),
            'administrators' => User::whereHas('roles', fn ($query) => $query->where('name', 'administrator'))->count(),
            'temporary_permissions' => TemporaryPermission::active()->count(),
        ];
    }

    private function hospitalMetrics(): array
    {
        return [
            'patients' => Patient::count(),
            'walkin_patients' => WalkinPatient::count(),
            'visits' => PatientVisit::count(),
            'active_visits' => PatientVisit::where('status', 'Active')->count(),
            'today_visits' => PatientVisit::whereDate('visit_date', today())->count(),
        ];
    }

    private function financeMetrics(): array
    {
        return [
            'bills' => Bill::count(),
            'today_bills' => Bill::whereBetween('issued_date', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])->count(),
            'open_bills' => Bill::whereIn('status', ['pending', 'partial'])->count(),
            'total_billed_today' => (float) Bill::whereBetween('issued_date', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])->sum('amount'),
            'payments' => Payment::count(),
            'payments_today' => Payment::whereBetween('payment_date', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])->count(),
            'collected_today' => (float) Payment::where('status', 'completed')
                ->whereBetween('payment_date', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])
                ->sum('amount'),
        ];
    }

    private function setupMetrics(): array
    {
        return [
            'departments' => Department::count(),
            'services' => Service::count(),
            'investigations' => Investigation::count(),
            'wards' => Ward::count(),
            'beds' => Bed::count(),
            'occupied_beds' => Bed::where('status', 'occupied')->count(),
        ];
    }

    private function syncMetrics(): array
    {
        if (! Schema::hasTable('sync_operations')) {
            return [
                'pending' => 0,
                'failed' => 0,
                'synced_today' => 0,
                'latest' => null,
            ];
        }

        return [
            'pending' => SyncOperation::where('status', 'pending')->count(),
            'failed' => SyncOperation::where('status', 'failed')->count(),
            'synced_today' => SyncOperation::where('status', 'synced')->whereDate('synced_at', today())->count(),
            'latest' => SyncOperation::latest('updated_at')->first(),
        ];
    }

    private function visitStatusRows()
    {
        return PatientVisit::query()
            ->selectRaw('status as label, COUNT(id) as count')
            ->groupBy('status')
            ->orderByDesc('count')
            ->get();
    }

    private function billStatusRows()
    {
        return Bill::query()
            ->selectRaw('status as label, COUNT(id) as count, SUM(amount) as amount')
            ->groupBy('status')
            ->orderByDesc('count')
            ->get();
    }

    private function recentActivities()
    {
        return AuditLog::with('actor')
            ->latest()
            ->limit(12)
            ->get();
    }
}
