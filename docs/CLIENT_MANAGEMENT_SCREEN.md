# Client Management Screen

## Purpose

Client Management is a superadmin platform screen for managing organizations that buy or evaluate MediFlow.

It is separate from License Management:

```text
Client Management
    -> Organization profile, sector, contact person, status, notes

License Management
    -> Activation plan, enabled modules, license key, dates, branch limit
```

## Access

Route:

- `admin/clients`
- route name: `admin.clients.index`

Middleware:

- `module:platform`
- `access:role:superadmin,permission:client.manage`

Sidebar:

- group: `Platform`
- module row: `client_management`
- license module: `platform`

## Data Model

Table:

```text
clients
```

Fields:

```text
id
name
sector
contact_person
email
phone
administrator_email
administrator_password
city
state
status
notes
created_at
updated_at
```

`administrator_password` is cast as encrypted in `App\Models\Client`.

These administrator credentials are the default login details copied during client activation. They are not the same as the MediFlow platform superadmin credentials.

The `client_licenses` table now has optional:

```text
client_id
```

This lets one client have many license or activation records over time.

## Client Statuses

```text
prospect
active
suspended
inactive
```

## Files

- `app/Models/Client.php`
- `app/Livewire/Admin/ClientManagement.php`
- `resources/views/components/admin/client-management.blade.php`
- `database/migrations/2026_08_27_000006_create_clients_table.php`
- `database/migrations/2026_08_27_000007_add_client_id_to_client_licenses_table.php`
- `database/seeders/ModulePermissionSeeder.php`

## Rerun Order

```bash
php artisan migrate --no-interaction
php artisan db:seed --class=RoleSeeder --no-interaction
php artisan db:seed --class=ModulePermissionSeeder --no-interaction
php artisan optimize:clear
```

## Next Screens

The next platform screens should connect to `clients.id`:

- Activation invoices
- Activation payments
- Reseller or partner management
- Branch management for Enterprise Hospital
