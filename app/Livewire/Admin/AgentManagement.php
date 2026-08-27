<?php

namespace App\Livewire\Admin;

use App\Models\Agent;
use App\Models\AuditLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.modern')]
class AgentManagement extends Component
{
    public ?int $agentId = null;
    public string $search = '';
    public string $name = '';
    public string $type = 'sales_agent';
    public string $email = '';
    public string $phone = '';
    public string $organization = '';
    public ?string $commissionRate = null;
    public string $status = 'active';
    public string $notes = '';

    public function mount(): void
    {
        $this->selectFirstAgent();
    }

    public function render()
    {
        return view('components.admin.agent-management', [
            'pageTitle' => 'Agent Management',
            'pageSubtitle' => 'Manage MediFlow sales agents, reseller partners, and implementation partners.',
            'agents' => $this->agents(),
            'types' => $this->types(),
            'statuses' => $this->statuses(),
            'summary' => $this->summary(),
        ]);
    }

    public function createAgent(): void
    {
        $this->agentId = null;
        $this->resetForm();
    }

    public function selectAgent(int $agentId): void
    {
        $agent = Agent::findOrFail($agentId);

        $this->agentId = $agent->id;
        $this->name = $agent->name;
        $this->type = $agent->type;
        $this->email = $agent->email ?? '';
        $this->phone = $agent->phone ?? '';
        $this->organization = $agent->organization ?? '';
        $this->commissionRate = $agent->commission_rate !== null ? (string) $agent->commission_rate : null;
        $this->status = $agent->status;
        $this->notes = $agent->notes ?? '';
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(array_keys($this->types()))],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'organization' => ['nullable', 'string', 'max:255'],
            'commissionRate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', Rule::in(array_keys($this->statuses()))],
            'notes' => ['nullable', 'string', 'max:3000'],
        ]);

        DB::transaction(function () use ($validated) {
            $agent = Agent::updateOrCreate(
                ['id' => $this->agentId],
                [
                    'name' => $validated['name'],
                    'type' => $validated['type'],
                    'email' => $validated['email'] ?: null,
                    'phone' => $validated['phone'] ?: null,
                    'organization' => $validated['organization'] ?: null,
                    'commission_rate' => $validated['commissionRate'] ?: null,
                    'status' => $validated['status'],
                    'notes' => $validated['notes'] ?: null,
                ]
            );

            AuditLog::record(auth()->user(), 'agent.save', $agent, null, $agent->toArray());

            $this->agentId = $agent->id;
        });

        $this->dispatch('toast', message: 'Agent saved successfully.', type: 'success');
    }

    public function setStatus(int $agentId, string $status): void
    {
        abort_unless(array_key_exists($status, $this->statuses()), 422);

        $agent = Agent::findOrFail($agentId);
        $before = $agent->toArray();
        $agent->update(['status' => $status]);

        AuditLog::record(auth()->user(), 'agent.status.update', $agent, $before, $agent->fresh()->toArray());

        if ($this->agentId === $agent->id) {
            $this->status = $status;
        }

        $this->dispatch('toast', message: 'Agent status updated.', type: 'success');
    }

    private function agents(): Collection
    {
        if (! Schema::hasTable('agents')) {
            return collect();
        }

        return Agent::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($builder) {
                    $builder->where('name', 'like', "%{$this->search}%")
                        ->orWhere('organization', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%");
                });
            })
            ->orderByRaw("FIELD(status, 'active', 'pending', 'suspended', 'inactive')")
            ->orderBy('name')
            ->limit(50)
            ->get();
    }

    private function summary(): array
    {
        if (! Schema::hasTable('agents')) {
            return [
                'agents' => 0,
                'active' => 0,
                'partners' => 0,
                'suspended' => 0,
            ];
        }

        return [
            'agents' => Agent::count(),
            'active' => Agent::where('status', 'active')->count(),
            'partners' => Agent::whereIn('type', ['reseller', 'implementation_partner'])->count(),
            'suspended' => Agent::where('status', 'suspended')->count(),
        ];
    }

    private function types(): array
    {
        return [
            'sales_agent' => 'Sales Agent',
            'reseller' => 'Reseller',
            'implementation_partner' => 'Implementation Partner',
            'support_partner' => 'Support Partner',
        ];
    }

    private function statuses(): array
    {
        return [
            'pending' => 'Pending',
            'active' => 'Active',
            'suspended' => 'Suspended',
            'inactive' => 'Inactive',
        ];
    }

    private function selectFirstAgent(): void
    {
        if (! Schema::hasTable('agents')) {
            return;
        }

        $agent = Agent::orderBy('name')->first();

        if ($agent) {
            $this->selectAgent($agent->id);
        }
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'email', 'phone', 'organization', 'commissionRate', 'notes']);
        $this->type = 'sales_agent';
        $this->status = 'active';
    }
}
