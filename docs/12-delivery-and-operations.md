# Implementation roadmap, testing, and operations

## Discovery and preparation

1. Confirm MedPass repository, owners, deployed capabilities and target environments. Review MediFlow deployed version, FHIR CapabilityStatement, existing sync and KernelBridge SDK integration.
2. Resolve the [decision register](01-product-scope.md#decision-register), define clinical dataset and data hosting policy, and approve a privacy impact assessment and clinical safety case.
3. Inventory all existing activation flows; agree compatible KernelBridge account-link and installation issuance design without migrating customer licenses automatically.
4. Define partner FHIR profiles, authorization protocol, patient identity review, grant lifecycle and synthetic fixtures. Record unsupported resource types and mappings.

## Implementation sequence

| Phase | Build | Exit gate |
|---|---|---|
| 0: foundation | React/TypeScript portal and Laravel API skeletons, session/CSRF/MFA, tenancy, audit, CI, private environments, infrastructure-as-code, synthetic fixtures. | Tenant isolation, browser/API contract and access tests, type-checking and security review. |
| 1: facility and license | Facility/server enrollment, product account link, verified license projection, heartbeat and offline operational queue. | Actual integration in nonproduction; replay, key rotation, outage, revoked-license and existing-installation tests. |
| 2: MedPass pilot | Patient identity review, scoped grants, FHIR mapping, bounded outbox/transfer, receipt, provenance and revocation. | Legal/clinical approval, FHIR/profile validation, one partner end-to-end pilot and tested offline behavior. |
| 3: recovery | Encrypted backup control, upload, retention and restore procedures. | Independent cross-tenant/security tests and witnessed isolated restore. |
| 4: network | Provider verification, agreements, then separately approved clinical referrals/EHR integration. | Partner security and interoperability validation, policy and operational readiness. |

Features deploy behind separate flags; default clinical exchange, backup and network off until each tenant is approved. Use additive changes, tested migrations and reversible rollout procedures. Do not turn flags on or migrate production databases without operator approval and a verified backup.

## Test matrix

- **Unit/contract:** grant/limit logic, roles, schema validation, mapping, idempotency, timestamps, state transitions and malformed signatures.
- **Frontend/API:** TypeScript type-check, typed contract compatibility, session/CSRF and tenant-switch behavior, loading/error/accessibility states, and browser tests using synthetic data.
- **Integration:** actual SDK and sandbox KernelBridge, per-installation credentials, FHIR reads with least privilege, partner authentication and storage isolation; use nonproduction keys.
- **Resilience:** outage at every transfer step, restart, duplicate/mismatched IDs, partial failure, reordered events, queue full, stale data, token rotation and service outage.
- **Privacy/security:** cross-tenant reads/writes, unauthorized patient access, replay, revoked grant, restricted support, no PHI in logs, restore authorization and key access.
- **Clinical:** patient mismatch and manual review, invalid or missing units, corrected results, incomplete/stale records, source provenance and supported profile validation with clinician sign-off.
- **Operations:** backup restoration, monitoring alerts, credential compromise response, disaster recovery and upgrade/rollback drills.
- **Accessibility:** keyboard, screen reader, contrast, zoom and small-screen flows, including errors.

Maintain traceability from each requirement in [system requirements](03-system-requirements.md) to a test and an accountable owner. Test against the released artifacts and deployment topology, not only mocks.

## Operations and rollout

Instrument portal and API latency, accepted/rejected sync counts, queue lag, heartbeat age, verification errors, backup freshness, restore outcomes and worker health without exposing PHI. Load-test portal/API/worker bottlenecks independently; scale only from observed needs. Document alert thresholds and on-call ownership. Dashboard should distinguish outage from permission failure and list correlation IDs.

Pilot with one willing organization and synthetic records before approved real data. Establish a baseline, schedule review, train facility staff, test support and restore, and enable capabilities one at a time. Stop rollout for unresolved patient matching, clinical safety, leakage or unrecoverable sync defects.

Rollback application changes by disabling new integrations and reverting deploys where schema compatibility permits; never delete queued or delivered data as a shortcut. Keep migration, backup and key-rotation rollback steps specific to the actual release. Reconcile in-flight requests and document obligations to recipients before pausing clinical delivery.
