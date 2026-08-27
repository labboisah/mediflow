# Agent Management Screen

## Purpose

Agent Management is a superadmin platform screen for people or organizations that help sell, implement, or support MediFlow.

Examples:

- Sales agents
- Resellers
- Implementation partners
- Support partners

## Access

Route:

- `admin/agents`
- route name: `admin.agents.index`

Middleware:

- `module:platform`
- `access:role:superadmin,permission:agent.manage`

Sidebar:

- group: `Platform`
- module row: `agent_management`
- license module: `platform`

## Data Model

Table:

```text
agents
```

Fields:

```text
id
name
type
email
phone
organization
commission_rate
status
notes
created_at
updated_at
```

## Agent Types

```text
sales_agent
reseller
implementation_partner
support_partner
```

## Statuses

```text
pending
active
suspended
inactive
```

## Files

- `app/Models/Agent.php`
- `app/Livewire/Admin/AgentManagement.php`
- `resources/views/components/admin/agent-management.blade.php`
- `database/migrations/2026_08_27_000009_create_agents_table.php`
- `database/seeders/ModulePermissionSeeder.php`

## Rerun Order

```bash
php artisan migrate --no-interaction
php artisan db:seed --class=ModulePermissionSeeder --no-interaction
php artisan optimize:clear
```

## Future Integration

Activation invoices and payments should optionally link to an agent so commissions and partner performance can be tracked.
