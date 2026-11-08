# MediFlow Platform: product and implementation documentation

This directory is the design workspace for a proposed hosted MediFlow Platform. It is **documentation only**: no application, infrastructure, integrations, or migrations have been implemented here.

The platform is intended to connect healthcare organizations and their MediFlow installations to licensing, operational monitoring, controlled MedPass exchange, backup coordination, and eventually a trusted provider network. Local healthcare operations remain on the facility server, including during internet outages.

## Read in this order

1. [Product scope and decisions](docs/01-product-scope.md)
2. [Architecture and technology stack](docs/02-architecture-and-stack.md)
3. [System requirements and nonfunctional targets](docs/03-system-requirements.md)
4. [Organizations, servers, and KernelBridge licensing](docs/04-organizations-and-licensing.md)
5. [Offline synchronization and server monitoring](docs/05-offline-sync.md)
6. [FHIR, MedPass, and interoperability](docs/06-fhir-medpass.md)
7. [Backups and disaster recovery](docs/07-backup-recovery.md)
8. [Provider network and services](docs/08-provider-network.md)
9. [Security, privacy, and clinical governance](docs/09-security-privacy.md)
10. [UX and UI design](docs/10-ux-ui.md)
11. [Data model and proposed contracts](docs/11-data-and-contracts.md)
12. [Delivery, testing, and operations](docs/12-delivery-and-operations.md)
13. [Implementation work packages and handoffs](docs/13-implementation-work-packages.md)

## Baseline and caveats

- The selected proposed stack is a React and TypeScript frontend backed by a Laravel API and background workers. This is a new hosted application, not KernelBridge and not a replacement for the facility EHR; the existing MediFlow server retains its own stack.
- The local MediFlow Server checkout currently documents a **disabled-by-default, limited, read-only FHIR R4 adapter** and a separate MediFlow-specific sync subsystem. See [MediFlow Server's FHIR plan](https://github.com/labboisah/mediflow-server/blob/main/docs/FHIR_READINESS_AND_ENABLEMENT_PLAN.md), [licensing documentation](https://github.com/labboisah/mediflow-server/blob/main/docs/CENTRALIZED_ACTIVATION_AND_LICENSING.md), and [Care Network operations](https://github.com/labboisah/mediflow-server/blob/main/docs/CARE_NETWORK_OPERATIONS.md). Their documentation and actual deployed version must be verified before integration.
- MedPass was described by the product owner, but its source location, contracts, deployment and capabilities have not been verified. All MedPass interactions in these documents are proposals, not claims of existing support.
- Initial clinical exchange is a proposed pilot, subject to legal, clinical, operational and interoperability approval. FHIR support alone is not certification or proof of compatibility.
- Sample URLs, keys, payloads, deadlines, limits and performance targets in these documents are proposals or placeholders. Do not copy secrets or patient data into these files.

## Decisions requiring approval before implementation

See the [decision register](docs/01-product-scope.md#decision-register). In particular: jurisdiction and legal roles; the clinical data hosting model; identity matching and authorization; emergency and license-expiry policy; backup key custody; external partner trust; and final release/service-level targets.

## External reference standards

- [HL7 FHIR R4](https://hl7.org/fhir/R4/) and [CapabilityStatement](https://hl7.org/fhir/R4/capabilitystatement.html).
- [SMART App Launch](https://hl7.org/fhir/smart-app-launch/) and [FHIR Bulk Data Access](https://hl7.org/fhir/uv/bulkdata/); these are candidate specifications, not claims that MediFlow currently supports them.
- [WHO SMART Guidelines reference architecture](https://smart.who.int/ra/).
- [CISA StopRansomware guide](https://www.cisa.gov/stopransomware/ransomware-guide).
