# Security, privacy, and clinical governance

## Governance before production

Appoint product, clinical safety, security, privacy, facility and MedPass owners. Determine applicable law and roles for each processing activity: local EHR, platform, MedPass, backups and external provider. Complete a documented privacy impact assessment, clinical safety assessment, threat model, data-flow inventory and agreements, including incident reporting, data residency and subprocessors. Legal compliance depends on deployment jurisdiction; FHIR and encryption alone do not establish it.

## Data classification and minimization

Classify operational aggregates, tenant configuration, identifiers, clinical content, security events, secrets and encrypted backups separately. Collect and retain the minimum required for a documented purpose. Clinical staging and backups are encrypted and access-controlled; operational dashboards do not need full patient charts. Prohibit real patient data in tests, tickets, developer logs and analytics unless a separately approved controlled process exists.

Define per-class retention, legal holds, deletion, subject access/correction and export. Reconcile downstream copies with agreements: a revocation or deletion request does not automatically delete independent recipients' records.

## Access and isolation

- MFA for administrators and privileged support, with secure recovery, short sessions and step-up for sensitive actions.
- Deny-by-default tenant, facility and object scoping on every query, worker and object-storage key. A global admin role must not bypass patient authorization by default.
- Separate duties for enrollment, user approval, data disclosure, backup restore and provider approval. Support access is time-bound, approved and audited.
- Enforce clinical purpose, patient link and authorization separately from subscription grants. Do not rely on hidden UI buttons.
- Service credentials are tenant- and installation-bound, revocable and rotated. Store only secret hashes when possible; use KMS/secret manager, never commit, log or return credentials after initial provisioning.
- HTTPS with certificate validation; authenticated, encrypted storage; independent trust provisioning and signing-key rotation for KernelBridge snapshots.

## Audit and response

Capture actor or installation, tenant, facility, action, purpose where relevant, object reference, decision, time, source, correlation and outcome. Protect audit integrity, restrict access, monitor storage and alert on audit pipeline failure. Avoid full clinical payloads, access tokens, passwords and patient search terms in logs. Define retention and review.

Maintain incident playbooks for credential compromise, cross-tenant exposure, false patient match, invalid clinical data, ransomware, lost backup keys, expired certificate, unavailable license verification and data breach. Include containment, communication, evidence preservation, local continuity, legal notification and recovery. No unapproved destructive action during investigation.

## Clinical safety

Clearly identify source, currentness, partial content and known mapping limitations. Require clinical review for high-risk mappings, terminology, conflicts and corrective workflows. No automatic overwrite, unsafe identity merge, or inferred negative clinical result. Define clinical responsibility for reviewing incoming records and a safe emergency procedure. Licensing restrictions must be reviewed for patient safety.

## Release controls

Dependency and secret scanning, security-focused code review, tenant isolation tests, penetration testing proportionate to risk, backup restore drills and FHIR/profile validation are release gates. Monitor alerts, failed jobs, latency, abnormal downloads and privileged access; assign accountable responders.
