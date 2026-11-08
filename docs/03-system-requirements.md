# System requirements

## Functional requirements and acceptance

| ID | Requirement | Acceptance evidence |
|---|---|---|
| ORG-01 | A platform admin creates an organization and invites an authorized initial administrator. | Invitation expires, is single-use, verified, auditable, and cannot grant another tenant access. |
| ORG-02 | Organization admin manages facilities and assigns scoped users. | Cross-tenant and cross-facility access tests deny unintended access. |
| SRV-01 | Enroll a server to one facility using time-limited bootstrap and a device-specific credential. | Expiry, replay, revocation and wrong-tenant tests pass. |
| SRV-02 | Show heartbeat, version, sync freshness and incidents. | UI distinguishes online, stale, offline and unknown and displays timestamps. |
| LIC-01 | Link account and installations to KernelBridge and display verified plan and effective grants. | Test wrong product/account, expired/revoked snapshots, signature rotation and existing-installation migration. |
| SYN-01 | Queue outbound allowlisted events while offline and resume safely. | Simulated outage, restart, duplicate, reordered and partial batch tests pass without lost or duplicated committed effects. |
| SYN-02 | Surface rejected events, retry state and manual resolution. | An authorized operator sees actionable errors without PHI in logs. |
| CLN-01 | Link a patient only after defined identity review and authorized sharing. | Ambiguous match stays pending; revocation blocks future deliveries. |
| CLN-02 | Transfer only an agreed supported patient-scoped dataset with source and freshness. | Independent clinical mapping review and FHIR/profile validation with synthetic cases pass. |
| BAK-01 | Track encrypted backup and recovery status. | Corrupt and incomplete uploads fail; a restore drill demonstrates usable data and key recovery. |
| NET-01 | Onboard providers before any external clinical exchange. | Independently verified organization, agreement, membership and explicit activation. |
| AUD-01 | Record security and disclosure actions. | Audit includes actor, tenant, action, result, timestamp and correlation ID without secrets or payloads. |

## Initial nonfunctional targets (proposed, not SLAs)

- Availability: pilot target 99.5% monthly hosted control-plane availability, excluding agreed maintenance; measure independently from facility internet availability.
- Performance: target p95 < 2 seconds for ordinary dashboard views at pilot load, excluding large exports, background jobs and network outages; define workload before benchmarking.
- Portal scaling: measure frontend asset delivery, API latency and throughput, concurrent sessions, database load and queue lag separately; horizontally scale stateless API instances and workers where appropriate. Validate limits with load tests rather than assuming React alone provides scalability.
- Sync: target 95% of eligible queued operational messages acknowledged within 10 minutes **after connectivity and service recover**, subject to bandwidth and queue capacity. Clinical transfer has separate safety and authorization gates.
- Data durability: committed outbox entries survive restart; remote acknowledgements represent durable acceptance, not completion of clinical delivery.
- Time: store UTC timestamps, show local time zone and source update time; do not use wall-clock time alone for ordering.
- Backups: propose 24-hour recovery point and 48-hour recovery time for an online pilot facility; offline sites may exceed these targets. Neither is guaranteed until tested and contracted.
- Accessibility: target WCAG 2.2 AA for supported screens, with keyboard, screen reader, contrast and focus tests.
- Capacity: select pilot number of tenants, installations, events/day, peak payload size, concurrent users and retention before sizing. Enforce quotas and backpressure; measure using realistic synthetic load.

## Connectivity and deployment requirements

Facility: supported MediFlow release, reliable local storage, local administrators, durable queue and outbound HTTPS when available; local clock synchronization when feasible. No inbound internet port is required for the proposed connector. Monitor storage to avoid exhausting clinical server capacity.

Platform: hosted React/TypeScript static assets, Linux-hosted Laravel API runtime, scheduler, supervised queue workers, relational database, private object storage, key management, backup, TLS, alerting, and administrator MFA. Use shared session infrastructure when running multiple API instances. Define supported browsers and operating systems during pilot discovery.

## Failure and safety requirements

- Internet outage must not block local registration, clinical access or record export required for care.
- Display source update time and last successful sync, and indicate incomplete or stale information; no assertion that a lack of uploaded data means no clinical findings exist.
- License or platform outage must not silently erase local data; approved clinical continuity policy governs restrictions.
- Clinical conflicts, uncertain patient matches, invalid mappings and withdrawn grants require explicit workflow; no automatic last-write-wins clinical merge.
- Audit and backup failures must generate observable operator alerts. Security verification failures fail closed for sharing.
