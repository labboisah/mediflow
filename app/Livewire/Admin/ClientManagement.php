<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.modern')]
class ClientManagement extends Component
{
    public ?int $clientId = null;
    public string $search = '';
    public string $name = '';
    public string $sector = '';
    public string $contactPerson = '';
    public string $email = '';
    public string $phone = '';
    public string $administratorEmail = '';
    public string $administratorPassword = '';
    public string $city = '';
    public string $state = '';
    public string $status = 'prospect';
    public string $notes = '';

    public function mount(): void
    {
        $this->selectFirstClient();
    }

    public function render()
    {
        return view('components.admin.client-management', [
            'pageTitle' => 'Client Management',
            'pageSubtitle' => 'Manage hospitals, clinics, diagnostic centers, pharmacies, and enterprise clients.',
            'clients' => $this->clients(),
            'selectedClient' => $this->selectedClient(),
            'statuses' => $this->statuses(),
            'sectors' => $this->sectors(),
            'summary' => $this->summary(),
        ]);
    }

    public function createClient(): void
    {
        $this->clientId = null;
        $this->resetForm();
    }

    public function selectClient(int $clientId): void
    {
        $client = Client::findOrFail($clientId);

        $this->clientId = $client->id;
        $this->name = $client->name;
        $this->sector = $client->sector ?? '';
        $this->contactPerson = $client->contact_person ?? '';
        $this->email = $client->email ?? '';
        $this->phone = $client->phone ?? '';
        $this->administratorEmail = $client->administrator_email ?? '';
        $this->administratorPassword = $client->administrator_password ?? '';
        $this->city = $client->city ?? '';
        $this->state = $client->state ?? '';
        $this->status = $client->status;
        $this->notes = $client->notes ?? '';
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'sector' => ['nullable', 'string', Rule::in(array_keys($this->sectors()))],
            'contactPerson' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'administratorEmail' => ['nullable', 'email', 'max:255'],
            'administratorPassword' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(array_keys($this->statuses()))],
            'notes' => ['nullable', 'string', 'max:3000'],
        ]);

        DB::transaction(function () use ($validated) {
            $client = Client::updateOrCreate(
                ['id' => $this->clientId],
                [
                    'name' => $validated['name'],
                    'sector' => $validated['sector'] ?: null,
                    'contact_person' => $validated['contactPerson'] ?: null,
                    'email' => $validated['email'] ?: null,
                    'phone' => $validated['phone'] ?: null,
                    'administrator_email' => $validated['administratorEmail'] ?: null,
                    'administrator_password' => $validated['administratorPassword'] ?: null,
                    'city' => $validated['city'] ?: null,
                    'state' => $validated['state'] ?: null,
                    'status' => $validated['status'],
                    'notes' => $validated['notes'] ?: null,
                ]
            );

            AuditLog::record(auth()->user(), 'client.save', $client, null, $client->toArray());

            $this->clientId = $client->id;
        });

        $this->dispatch('toast', message: 'Client saved successfully.', type: 'success');
    }

    public function generateAdministratorPassword(): void
    {
        $this->administratorPassword = 'MediFlow-' . Str::upper(Str::random(8));
    }

    public function setStatus(int $clientId, string $status): void
    {
        abort_unless(array_key_exists($status, $this->statuses()), 422);

        $client = Client::findOrFail($clientId);
        $before = $client->toArray();
        $client->update(['status' => $status]);

        AuditLog::record(auth()->user(), 'client.status.update', $client, $before, $client->fresh()->toArray());

        if ($this->clientId === $client->id) {
            $this->status = $status;
        }

        $this->dispatch('toast', message: 'Client status updated.', type: 'success');
    }

    private function clients(): Collection
    {
        if (! Schema::hasTable('clients')) {
            return collect();
        }

        return Client::query()
            ->withCount('licenses')
            ->with(['licenses' => fn ($query) => $query->latest('updated_at')->limit(1)])
            ->when($this->search !== '', function ($query) {
                $query->where(function ($builder) {
                    $builder->where('name', 'like', "%{$this->search}%")
                        ->orWhere('contact_person', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%");
                });
            })
            ->orderByRaw("FIELD(status, 'active', 'prospect', 'suspended', 'inactive')")
            ->orderBy('name')
            ->limit(50)
            ->get();
    }

    private function selectedClient(): ?Client
    {
        if (! $this->clientId || ! Schema::hasTable('clients')) {
            return null;
        }

        return Client::with('licenses.enabledModules')->find($this->clientId);
    }

    private function summary(): array
    {
        if (! Schema::hasTable('clients')) {
            return [
                'clients' => 0,
                'active' => 0,
                'prospects' => 0,
                'licensed' => 0,
            ];
        }

        return [
            'clients' => Client::count(),
            'active' => Client::where('status', 'active')->count(),
            'prospects' => Client::where('status', 'prospect')->count(),
            'licensed' => Client::has('licenses')->count(),
        ];
    }

    private function statuses(): array
    {
        return [
            'prospect' => 'Prospect',
            'active' => 'Active',
            'suspended' => 'Suspended',
            'inactive' => 'Inactive',
        ];
    }

    private function sectors(): array
    {
        return [
            'diagnostic_center' => 'Diagnostic Center',
            'maternity_clinic' => 'Maternity Clinic',
            'hospital' => 'Hospital',
            'general_clinic' => 'General Clinic',
            'pharmacy' => 'Pharmacy',
            'enterprise_hospital' => 'Enterprise Hospital',
        ];
    }

    private function selectFirstClient(): void
    {
        if (! Schema::hasTable('clients')) {
            return;
        }

        $client = Client::orderBy('name')->first();

        if ($client) {
            $this->selectClient($client->id);
        }
    }

    private function resetForm(): void
    {
        $this->reset([
            'name',
            'sector',
            'contactPerson',
            'email',
            'phone',
            'administratorEmail',
            'administratorPassword',
            'city',
            'state',
            'notes',
        ]);
        $this->status = 'prospect';
    }
}
