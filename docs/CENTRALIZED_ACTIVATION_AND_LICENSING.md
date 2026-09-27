# Centralized Activation And Licensing

## Decision

MediFlow no longer manages superadmin, agents, clients, plans, subscription activation, or license activation inside the local hospital installation.

Those responsibilities belong to the centralized MediFlow platform called kernelbridge.

## Local Hospital System Responsibilities

The local hospital system should keep only:

- Hospital administrator and staff users
- Hospital roles and permissions
- Local module enforcement based on received activation data
- Hospital operational data
- Optional sync/activation records pushed from the central platform

## Central Platform Responsibilities

The central platform should manage:

- Agents and partners
- Client profiles
- Subscription plans
- Activation invoices
- Activation payments
- License keys
- Enabled modules per client
- Enterprise branch entitlements

## Local Tables Kept

The local system still keeps license enforcement tables that may be populated by activation/sync:

```text
client_licenses
client_enabled_modules
module_user_access
```

## Removed Local Platform Screens

These screens were removed from the local hospital app:

- Client Management
- Agent Management
- Plans Management
- License Management
- Superadmin dashboard

## Removed Local Role

The local app no longer seeds:

```text
superadmin
```

The highest local role is:

```text
administrator
```
