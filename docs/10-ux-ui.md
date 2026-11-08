# UX and UI design

## Design goals

Make a multi-facility system understandable to nontechnical healthcare administrators while clearly distinguishing **local care**, **hosted coordination**, **clinical sharing** and **licensing**. Use accessible, responsive React and TypeScript screens backed by the Laravel API, with visible text labels, keyboard navigation, consistent feedback, and time-zone-aware timestamps. Do not encode status by color alone. Patient-facing MedPass UI remains owned by the MedPass team.

Centralize accessible UI components, routing, typed API access and server-state handling. Show loading, authorization failure, stale and reconnect states. Use paginated lists and bounded dashboard refresh; do not cache patient data persistently in browser storage. Clear organization-scoped state when users switch tenants or sign out. Browser state is a presentation aid, not a source of authorization or clinical truth.

## Proposed navigation

```text
Organization selector
  Overview
  Facilities
  Servers and sync
  Subscription
  MedPass connections
  Backups
  Provider network        (later phase)
  Users and roles
  Audit and alerts
  Settings
```

Only show navigation and actions allowed by role and entitlement. Hiding navigation is not authorization. Avoid showing patient information in organization-wide summary cards.

## Core screens

| Screen | Content and primary actions | Empty/error/safety states |
|---|---|---|
| Organization overview | Facility count, server status, last sync, backup alerts, subscription status. Drill down to a facility. | Explain unknown/stale data, offline sites and missing setup; no patient list by default. |
| Facility and server detail | Server identity/version, facility, last contact, queue age/counts, credential and enrollment status. | Distinguish offline from revoked; never show credential; provide actionable retry/alert instructions. |
| Subscription | Verified plan, effective capabilities and limits, expiration, last verification and source. Manage through permitted KernelBridge flow. | Display grace/unavailable/expired separately; don't fabricate plan or interpret an old snapshot as live. |
| MedPass connections | Patient link state, pending reviews and request/delivery history for authorized roles. | Ambiguous match requires human review; visibly distinguish consent/revocation propagation and stale records. |
| Backup center | Last backup, source timestamp, completion/integrity, storage, last restore drill, retention. | Upload success is not restore success; show offline backlog, missing key and failures. |
| Provider network | Approved providers, services, agreement and relationship state. | No clinical disclosure from directory access; state that self-reported availability is not verified. |
| Users and access | Scoped memberships, invitations, MFA and suspension. | Warn on privilege change; require extra confirmation for sensitive roles. |
| Audit and alerts | Filtered actions, outcomes, actor, purpose, correlation and timestamps. | No credentials or raw clinical payload; access restricted. |

## Key journeys

**First facility:** accept invite and MFA -> choose organization -> link subscription -> create facility -> enroll local server -> verify heartbeat -> show next steps. Separate account linking from local installation activation and explain errors.

**Patient sharing:** authorized request -> identity and permissions review -> scope preview -> facility queues data -> delivery receipt -> show latest source time, completeness and authorization status. Make uncertain or offline states obvious.

**Recovery:** inspect last successful backup and restore test -> authorized request -> dual approval where policy requires -> isolated restore -> verification -> facility-controlled cutover. Clearly warn that restoring can overwrite local data.

## Content patterns

- For stale data: “Last synchronized at [time, zone]; newer records may exist at the facility.”
- For a pending revocation: “New platform delivery is blocked. The offline facility has not yet acknowledged the change.”
- For licensing outage: “Verification unavailable; cached status last verified [time].” Do not label an outage as a revocation.
- For errors: provide the operation, recoverable next step and non-sensitive correlation ID. Never ask users to paste credentials into support chat.

## Design validation

Test on desktop and a smaller tablet viewport with facility administrators and accessibility reviewers. Include loading, empty, expired, offline, conflict, failed, revoked and success states. Use synthetic data for prototypes and screenshots. Clinical staff must validate language and risk warnings.
