# License Management Screen

## Purpose

The License Management screen lets the MediFlow superadmin select a client license group and enable the feature modules available to that client.

Client profiles are managed separately in `CLIENT_MANAGEMENT_SCREEN.md`. A license may be linked to a client profile through `client_licenses.client_id`.

Route:

- `admin/license-management`
- route name: `admin.license-management`

Permission:

- `license.manage`

Role:

- `superadmin`

Sidebar:

- group: `Platform`
- module row: `license_management`
- license module: `platform`

## License Groups

The supported license groups are configured in `config/mediflow_modules.php`.

1. Diagnostic Center
2. Maternity Clinic
3. Hospital
4. General Clinic
5. Pharmacy
6. Enterprise Hospital

Enterprise Hospital is treated as full hospital access plus branch management.

## Features

Features are license module keys, such as:

- `patient_records`
- `clinical_care`
- `maternity`
- `laboratory`
- `radiology`
- `pharmacy`
- `billing`
- `finance`
- `reports`
- `wards_beds`
- `department_management`
- `access_control`
- `synchronization`
- `maintenance`
- `branch_management`

The License Management screen reads feature labels from `config('mediflow_modules.features')` and also includes any non-platform `modules.license_module` values found in the database.

Reserved platform modules, such as `platform`, are not sellable client features and must not appear in the client feature checklist.

## Behavior

- Selecting a license group applies that group's preset features.
- The superadmin may customize the checked feature modules.
- Saving the license stores selected features in `client_enabled_modules`.
- If a license has saved feature rows, those rows become the source of truth.
- If a license has no saved feature rows, the system falls back to the configured plan preset.
- Only one license should be active at a time. Saving an active license deactivates other licenses.
- Enterprise Hospital requires a branch limit.
- Hospital administrators do not manage licenses. They manage hospital users, data, and activities inside activated license modules.

## Files

- `app/Livewire/Admin/LicenseManagement.php`
- `resources/views/components/admin/license-management.blade.php`
- `config/mediflow_modules.php`
- `app/Services/LicenseService.php`
- `app/Models/ClientLicense.php`
- `app/Models/Client.php`
- `database/migrations/2026_08_27_000005_add_branch_limit_to_client_licenses_table.php`
- `database/migrations/2026_08_27_000007_add_client_id_to_client_licenses_table.php`
- `database/seeders/ModulePermissionSeeder.php`

## Verification

Run:

```bash
php -l app/Livewire/Admin/LicenseManagement.php
php artisan migrate --no-interaction
php artisan db:seed --class=ModulePermissionSeeder --no-interaction
php artisan view:cache
php artisan route:list --name=admin.license-management --except-vendor
php artisan view:clear
```
