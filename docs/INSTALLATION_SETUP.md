# Installation Setup

The local installation now has a super-admin-only configuration interface at `/admin/installation`. This implements the local package setup requested in September 2026 and supersedes earlier guidance that all license configuration must be external.

## Use

Sign in with an installation administrator account and open **Installation Setup** in the sidebar. Enter the organization name, choose Pharmacy, General Clinic, Diagnostic Center, Maternity Clinic, Hospital, or Enterprise Hospital, then review the modules and save. Choosing a package resets the draft module selection to that preset; it does not save until Save installation is clicked.

Core and Access Control are required. Other modules can be enabled as add-ons or disabled. Dependencies are validated before saving. Branch Management is reserved for Enterprise. These settings enable existing application capabilities; they do not install separate software or implement additional branch workflows.

Saving updates the latest installation license and records an explicit enabled/disabled row for each known module inside a transaction. Existing operational records, staff roles, and module assignments are retained. Disabled features are hidden in navigation and blocked on direct requests, including persistent Livewire requests. Configured installations use a dashboard listing available workspaces. Activity report screens and exports filter their datasets by enabled modules.

Inactive, future-dated, or expired saved licenses leave only Core and Access Control enabled, while the setup screen remains accessible to installation administrators. An expired license cannot fall back to a previous license or the default hospital package. Before a license is configured, the configured default plan is used for compatibility.

## Administration

The installation-administrator flag is separate from editable staff roles and is not mass assignable. Ordinary administrators cannot modify an installation administrator through User Management. Grant/revoke this flag for an existing account from the server:

```sh
php artisan installation:admin someone@example.com
php artisan installation:admin someone@example.com --revoke
```

Apply the additive `2026_09_27_000001_add_installation_admin_to_users` migration on other installations. No default credentials are seeded. Role permissions and staff module access remain separate from installation configuration.

Package definitions/dependencies: `config/mediflow_modules.php`.
Route restrictions: `config/installation_routes.php`; all matching rules apply and `|` means either module can support a shared route. Add rules when introducing new feature routes. Package checks do not grant operational role permissions and do not constitute cryptographically signed remote licensing.

## Verification

```sh
php vendor/phpunit/phpunit/phpunit tests/Feature/InstallationSetupTest.php
php artisan view:cache
```

Tests use an isolated in-memory SQLite schema and cover setup authorization, revoked access, presets, dependency validation, explicit disabled modules, inactive/expired licenses, direct route restrictions, middleware persistence, and report datasets.

## System branding and welcome pages

The same Installation Setup screen includes a separate **System branding** form. It saves brand name, organization address, optional welcome heading and statement, logo, and welcome template without altering the installation package or staff access.

Logo uploads accept PNG, JPEG, and WebP up to 2 MB and 2048 ? 2048 pixels. Logos use generated filenames on the private local disk and are served by the read-only `/branding/logo` endpoint; no storage symlink is required. Replacement/removal cleans up the previous logo after the database save succeeds. SVG uploads are rejected. Changes are audited as `system.branding_updated`.

Automatic template selection follows the saved installation package. Six styles are available: Pharmacy, Clinic, Diagnostics Center, Maternity Clinic, Hospital, and Enterprise. A manual selection overrides the automatic choice without changing module access. Empty welcome text uses the selected template?s default wording. Welcome text is escaped, not rendered as HTML. The preview uses the selected package; save package changes separately to apply them publicly.

Saved branding appears on the welcome page, login screen, app favicon/sidebar, and existing web receipt/report headers that read `app.name`, `app.title`, or `app.address`. No `.env` editing is required. The original marketing welcome page remains until branding is first saved. Settings live in the singleton `system_settings` row, separate from license data. Apply `2026_09_28_000001_create_system_settings_table` when upgrading another installation.

## Department heads and technician roles

Department-specific head roles are named from the department, for example `head_of_pharmacy` (Head of Pharmacy) and `head_of_laboratory` (Head of Laboratory). Administrators assign these roles and the matching department through User Management. Existing `head_of_department` assignments remain supported; existing users are not automatically reassigned. A named head role only acts as an HOD when its department matches the user's assigned department.

HODs use `/department/users` to create staff, change names/login details, and assign operational roles within their own department. Staff role choices follow `config/department_staff.php` and the enabled installation modules. Unrecognized department names require an administrator to add the appropriate mapping. HODs cannot edit themselves, other departments, installation administrators, administrator/head accounts, or accounts holding roles outside the department's allowed set. Role and user updates are transactional and audited without recording passwords. Associated operational module access is updated alongside the roles.

Department Users uses the Access Control module, so it is available in standalone installations without enabling the full Departments & Inventory package. Installation Setup remains restricted to installation administrators, and no mandatory activation or remote licensing restriction was added.

`pharmacy_technician` is an operational role for pharmacy sales and dispensing; it does not grant medicine/stock management or pharmacy finance administration. `lab_technician` reuses the existing role instead of introducing a duplicate.

Apply the focused update on existing installations:

```sh
php artisan db:seed --class=TechnicianRoleSeeder --force
```

This creates/updates named department-head roles, adds Pharmacy Technician, ensures Lab Technician exists, and updates Department Users navigation. It preserves existing role assignments and permissions rather than reseeding users or resetting access.

Tests: `php vendor/phpunit/phpunit/phpunit tests/Feature/DepartmentUsersTest.php tests/Feature/InstallationSetupTest.php`.
