# Product scope, operating model, and decisions

## Purpose

Enable a healthcare organization to administer its MediFlow installations and, with approved authorization, securely exchange selected patient information with MedPass even when a facility is intermittently connected. The EHR remains usable locally and retains responsibility for its own clinical record.

## Users and outcomes

| Actor | Outcome | Explicit boundary |
|---|---|---|
| Organization administrator | Register facilities, manage memberships and inspect operational/license status. | No automatic access to clinical content. |
| Facility administrator | Monitor local server, sync and backups; respond to incidents. | Cannot approve their own privileged access by default. |
| Clinician / authorized records officer | Review patient match and approve permitted exchange within clinical duties. | Access is scoped by patient, facility, purpose and policy. |
| Patient or authorized representative | Connect MedPass, inspect sharing and use supported revocation/complaint channels. | Facility staff do not control the patient's independent MedPass account. |
| Support operator | Diagnose a support case using minimum necessary metadata. | No standing access to identifiable records or backup plaintext. |
| Platform operator | Maintain hosted service and provider onboarding. | Cross-tenant access is tightly controlled and audited. |

## Product boundaries

- **MediFlow server:** source of local clinical records and local clinical workflows. Its administrator controls enrollment and its local access policy.
- **MediFlow Platform:** tenant and installation coordination, verified license presentation, sync orchestration, operational monitoring and explicitly authorized exchange. It is not the subscription billing authority.
- **KernelBridge:** commercial subscription, platform account link, installation grants and signed entitlement authority. No patient clinical payload is needed for licensing.
- **MedPass:** independent patient-facing product. Its teams own its accounts and consent/sharing experience; the exchange contract must be jointly agreed.
- **Other EHRs:** independent data controllers/operators and integration partners; no blanket claim that any FHIR server can interoperate without profile, identity and workflow testing.

An organization may have many facilities; one facility may have multiple installations. A registered installation belongs to exactly one facility at a time; historical transfers are preserved. A facility operator may not enroll another organization's server.

## Scope and rollout

**Pilot 1:** organization onboarding, facility and installation registration, KernelBridge account linking and verified license display, device heartbeat, outbound operational sync, sync monitoring, audit and role-based access.

**Pilot 2:** limited, read-only, patient-approved exchange between one MediFlow version and one MedPass version; clinically validated mappings, manual patient-match review, sharing history and revocation enforcement.

**Pilot 3:** encrypted backup upload and tested recovery for a consenting facility, with approved key custody.

**Later:** partner onboarding, other EHR connectors, referrals and provider services. Each has a separate go/no-go.

Out of the initial scope: automatic patient matching/merging, bidirectional clinical writes, complete hosted EHR, emergency break-glass sharing, unrestricted bulk export, automatic insurance/payment settlement, claims of national interoperability, and automatic deletion of already received copies.

## Decision register

| Decision | Proposed default | Required approver / open question |
|---|---|---|
| Jurisdiction and legal responsibilities | Determine before any identifiable data moves. | Legal/privacy lead: controller/processor roles, residency, lawful bases, agreements, cross-border transfer. |
| Clinical hosting | Minimum selected, authorized records in encrypted tenant-scoped exchange storage, retained for a defined delivery window; no full shared clinical repository in pilot. | Product, clinical and privacy leads: retention, MedPass receipt and deletion obligations. |
| Patient identity | Explicit verified link and human review for uncertain matches; no automatic merge. | Clinical, privacy and MedPass owners: evidence, age and representative workflows. |
| Authorization | Purpose- and recipient-specific grants with expiry and revocation where legally applicable. | Legal/clinical leads: exceptions, record amendments, disclosure obligations and emergency processes. |
| License expiry | Preserve safety-critical local care and legal access; restrict nonessential hosted services only under an approved policy. | Clinical safety, product and legal owners: exact grace and downgrade semantics. |
| Backups | Facility-specific encrypted backup sets, separate from clinical exchange. | Organization and security owners: encryption-key custody, location, retention, recovery permissions and cost. |
| Offline physical transfer | Disabled by default; dedicated design and approval required. | Security and clinical owners: chain of custody, replay prevention, media sanitation and incident process. |
| Targets and hosting | Targets in this pack are initial engineering proposals, not contractual promises. | Product and operations: pilot scale, SLOs, region and budget. |
| Stack | React + TypeScript frontend; Laravel API and workers. | Owner selected; confirm supported versions, hosting and authentication details at implementation and security review. |

## Success measures

Use synthetic or aggregated operational data for measurement: percentage of enrolled servers reporting within expected window, oldest pending queue age, sync success and rejection rates, time to identify failures, verified backup restore rate, and patient-match exceptions. Define denominator, exclusions and targets before the pilot. Clinical outcomes require separately approved studies; do not infer them from sync counts.
