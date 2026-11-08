# FHIR, MedPass, and interoperable patient exchange

## Current baseline versus proposal

MediFlow documents an opt-in R4 (4.0.1) read-only adapter for Patient, Encounter, structured vital-sign Observation, ServiceRequest, DiagnosticReport, Medication, MedicationRequest and MedicationDispense, with bounded search/read and a CapabilityStatement. It requires resource-specific Sanctum abilities and user permissions. Consult the current deployed CapabilityStatement and [MediFlow Server FHIR plan](https://github.com/labboisah/mediflow-server/blob/main/docs/FHIR_READINESS_AND_ENABLEMENT_PLAN.md) for precise limitations. It does **not** establish support for all FHIR resources, write, SMART, bulk export or cross-system patient identity.

MedPass functionality is unverified. Jointly agree a versioned API contract, profiles, legal responsibilities, supported R4 operations and clinical dataset before implementation. R4 is the proposed first pilot baseline, not a guarantee of other EHR compatibility.

## Patient link and authorization lifecycle

1. Patient initiates or accepts a link to a named facility through a jointly approved workflow. Verify the patient or authorized representative without using a demographic guess as proof.
2. Connector/facility performs a scoped lookup. Ambiguous matches enter an authorized manual review queue; no record merges or clinical access until verified.
3. Create a pseudonymous link scoped to the organization, facility, local patient identity and MedPass subject. Maintain history, verification method, reviewer, status and expiry without distributing universal identifiers unnecessarily.
4. Record legal basis and policy evidence. Where consent is used, capture what data, recipient, purpose, duration and withdrawal mechanism were presented; separately handle other legally permitted bases and representative authority.
5. For each request, evaluate current link, recipient, purpose, resource scope and grant status at both source and delivery boundaries. Deny missing/expired/revoked grants; log a non-sensitive decision.
6. Revocation stops future authorized deliveries where applicable. Show queued, delivered and propagation-pending states; notify recipients through agreed procedures. It cannot guarantee deletion of data already received.

No platform administrator gets patient content simply by administering a facility. Define dispute, misidentification, correction, guardian and deceased-patient processes with clinical and legal owners.

## Pilot data and mapping

Define a jointly approved profile for each resource; enumerate mandatory fields, terminology, references, units, validation and redaction rules. Start with Patient and a small set of approved, supported read-only Encounter/Observation/DiagnosticReport and medication information as appropriate. Do **not** promise allergies: MediFlow's documented adapter does not currently list AllergyIntolerance. Unsupported fields/resources remain unavailable, not inferred or fabricated. Preserve unknown, partial, amended and corrected status.

Never infer standard codes from free text. Diagnostic report mappings and medication dose/unit gaps need clinical sign-off. Reference source organization, source resource logical ID and version, patient link, last clinical update, extraction time, original author where available, and transfer receipt. Maintain references between resources and ensure resource identifiers are stable and scoped by source system; never assume IDs from two EHRs identify the same patient or record.

## Exchange protocol

1. Receive an authorized patient-scoped request with recipient, purpose, resource scope and expiry.
2. Queue request for the facility, indicating that offline sites may not respond immediately.
3. Facility independently evaluates policy, reads only supported local resources and packages approved versions. Validate against agreed profiles before queueing.
4. Transfer encrypted, bounded payloads using durable sync; re-check current grant before platform forwards to MedPass.
5. MedPass validates, persists provenance and acknowledges per item. Track `requested`, `awaiting_facility`, `prepared`, `delivered`, `failed`, `revoked` and `expired` separately. Report partial/incomplete data and freshness to the patient.
6. Corrections and withdrawals produce versioned messages; recipients decide how to reconcile under agreements and law. No destructive overwrite or automatic clinical merging.

FHIR `Bundle` and `OperationOutcome` are useful for clinical exchange and error reporting, but envelopes, signatures and receipts need a documented partner contract. SMART App Launch/OAuth can be evaluated where both partners support the relevant flow; do not claim support without conformance tests. Patient-specific sharing is not a justification for broad population export.

## External EHR onboarding

Confirm endpoint ownership, identity and transport; obtain CapabilityStatement and version/profile list; agree authentication, patient matching, scopes, terminology and resource interactions; execute test fixtures and negative cases; validate data quality with clinicians; complete contracts, privacy/security review, a limited pilot and monitoring. Record partner and API versions. If no compatible agreement exists, show the limitation rather than presenting the partner as connected.

## Tests and clinical safety

Use synthetic cases for duplicate names, uncertain matches, records from multiple facilities, partial encounters, invalid units, report amendment, missing fields, stale data, revoked permissions and delayed delivery. Test FHIR base/profile validation plus partner-specific behavior; clinician review remains necessary. A warning must communicate when data is incomplete or stale, not merely when an API call succeeds.
