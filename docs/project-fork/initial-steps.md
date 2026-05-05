# Remaining Project Split and Rebrand Steps

This document serves as the execution checklist for the remaining work in the Product Split and Rebrand process.

## Review Policy
- **Stop and Review:** If a step instructs you to "run prompt," you must first review and approve the linked prompt file before execution.
- **Human Approval Required:** This review-before-execution rule applies even when Junie is operating in a more autonomous/brave mode.

## Completed Foundation
The following setup and split steps are already complete:
- `release-tools` parent workspace exists and is used for local coordination.
- `release-tools` is NOT a Git repository.
- `release-scribe` and `release-pilot` are separate child repositories.
- ReleasePilot documentation has been moved to `release-pilot/docs/`.
- Both projects are available together for AI Assistant/Junie context.
- ReleasePilot remains documentation/planning-only for now.

## Phase 1 — Final Documentation Housekeeping
- [ ] Revalidate all ReleaseScribe docs for stale split references (pointing to `docs/release-pilot/`).
- [ ] Update `product-split-and-rebrand-plan.md` to reflect current split state.
- [ ] Remove completed/stale prompt files (e.g., `shared-plan-file-cleanup.md`).
- [ ] Verify links between `release-scribe` and `release-pilot` documentation are correct.

## Phase 2 — ReleaseScribe Rebrand
This section covers the transition from the old "Git Toolkit" identity to **ReleaseScribe**.

- [ ] **Review Rebrand Prompt:** [ReleaseScribe Rebrand](../prompts/release-scribe-rebrand.md)
- [ ] **Identity Update:** Update package name, namespace (`Chance\ReleaseScribe`), and binary name (`release-scribe`).
- [ ] **Codebase Scrub:** Replace occurrences of "Git Toolkit" or "toolkit" with "ReleaseScribe" or "scribe" as appropriate.
- [ ] **Configuration:** Update default config file names and internal keys.
- [ ] **External Tasks:** 
    - [ ] Update GitHub repository name (if applicable).
    - [ ] Update Packagist entry.
    - [ ] Update any external CI/CD references.

## Phase 3 — Phase 5 AI-Assisted Release Communication
This phase introduces advanced AI features to ReleaseScribe after the rebrand is stable.

- [ ] **Review AI Prompt:** [Phase 5 AI-Assisted Release Communication](../prompts/release-scribe-phase-5-ai-assisted-release-communication.md)
- [ ] **Audience-Aware Summaries:** Implement logic to generate different summary styles (e.g., technical, marketing, executive).
- [ ] **AI-Assisted "What's New":** Integrate AI for richer, more narrative release summaries.
- [ ] **Structured JSON Output:** Ensure Phase 5 provides or defines a stable JSON summary output for integration.
- [ ] **Stabilization:** Ensure AI features are optional and do not break deterministic parsing or SemVer recommendations.

## Phase 4 — ReleaseScribe Stabilization and ReleasePilot Implementation
- [ ] **Stabilize ReleaseScribe:** Ensure all new features are tested and the API/output format is stable.
- [ ] **Begin ReleasePilot Implementation:** Once ReleaseScribe is stable, start building the ReleasePilot tool in `release-pilot/`.
- [ ] **Integration:** Configure ReleasePilot to depend on and consume ReleaseScribe for changelog and summary generation.
