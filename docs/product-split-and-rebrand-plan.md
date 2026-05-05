# Product Split and Rebrand Plan Index

This document serves as the high-level index and summary for the ReleaseScribe / ReleasePilot product split and rebrand.

## Purpose

The goal is to split the original project into two distinct products to allow each to evolve independently while
maintaining a clear integration path.

## Product Split Summary

- **Product 1: ReleaseScribe** (Current Repository)
    - **Role:** Standalone release communication generator (Changelogs, "What's new?", AI summaries).
    - **Identity:** `chancegarcia/release-scribe`, `release-scribe` binary, `Chance\ReleaseScribe` namespace.
- **Product 2: ReleasePilot** (Separate Repository)
    - **Role:** Guided release workflow orchestration (Checking, Planning, Tagging, Publishing).
    - **Identity:** `chancegarcia/release-pilot`, `release-pilot` binary, `Chance\ReleasePilot` namespace.

## Current Repository Status

- **This Repository:** Remains **ReleaseScribe**.
- **ReleasePilot Docs:** Transitional organization only; move to separate repository is a future/manual task.
- **Shared Context:** Both projects should be checked for drift when changes affect product boundaries. Refer
  to [Prompt Delivery Format](misc/generic-phpstorm-ai-chat-prompt-delivery-format.md) for drift prevention guidance.

## ReleaseScribe Product Direction

ReleaseScribe turns technical changes into release-ready communication artifacts.

- **Primary Focus:** Release communication, including "What's new?" documents, changelogs, release notes, audience-aware
  summaries, and AI-assisted summaries.
- **Independence:** ReleaseScribe is a standalone tool and does not depend on ReleasePilot.
- **AI Optionality:** AI is an optional enhancement for communication quality; deterministic parsing and SemVer
  recommendation remain authoritative.
- **Boundary:** Release workflow orchestration (checking, planning, tagging, publishing) belongs to ReleasePilot.

## ReleaseScribe Identity Summary

- **Name:** ReleaseScribe
- **Package:** `chancegarcia/release-scribe`
- **Binary:** `release-scribe`
- **Namespace:** `Chance\ReleaseScribe`
- **Positioning:** AI-assisted “What’s new” documents and changelogs for PHP projects.

## ReleasePilot Identity Summary

- **Name:** ReleasePilot
- **Package:** `chancegarcia/release-pilot`
- **Binary:** `release-pilot`
- **Namespace:** `Chance\ReleasePilot`
- **Positioning:** Guided release preparation for PHP projects.

## Key Current Decisions

- **Rebrand First:** Rebrand ReleaseScribe before implementing Phase 5 AI features.
- **Clean Cutover:** No legacy aliasing or wrappers for the old Git Toolkit identity.
- **Transitional Docs:** ReleasePilot docs remain under `docs/release-pilot/` until the ReleasePilot repository is
  established.
- **Manual Move:** Moving ReleasePilot docs into a separate repository is a future/manual task, not part of the current
  documentation organization.

## Authoritative Documentation

### Process and Execution

- [Project Fork Initial Steps](project-fork/initial-steps.md) - **Execution Checklist**
- [Risks and Decisions](project-fork/risks-and-decisions.md) - Decisions, revisit items, and mitigations.
- [Organize Split Docs](project-fork/organize-split-docs.md) - History of the documentation organization task.

### ReleaseScribe Documentation

- [Roadmap](roadmap.md) - Phases 1-5 development plan.
- [Migration Guide](release-scribe-planning/migration-to-release-scribe.md) - How to move from Git Toolkit to
  ReleaseScribe.
- [API Plan](release-scribe-planning/release-scribe-api-plan.md) - Integration surface for downstream tools.
- [Artifacts Plan](release-scribe-planning/release-scribe-artifacts-plan.md) - Generated outputs and conventions.

### ReleasePilot Documentation (Transitional)

*Note: These files currently reside in `docs/release-pilot/` and will be moved to the ReleasePilot repository later.*

- [Product Plan](release-pilot/product-plan.md) - Goals and command structure.
- [ReleaseScribe Integration Plan](release-pilot/release-scribe-integration-plan.md) - How ReleasePilot consumes Product
  1.
- [CI Workflow Plan](release-pilot/ci-workflow-plan.md) - CI gates and automation.
- [Release Manifest Plan](release-pilot/release-manifest-plan.md) - Central release data structure.

## Extracted Prompts

### ReleaseScribe Prompts

- [Phase 5 AI Implementation](prompts/release-scribe-phase-5-ai-assisted-release-communication.md)
- [ReleaseScribe Rebrand](prompts/release-scribe-rebrand.md)
- [Shared Plan File Cleanup](prompts/shared-plan-file-cleanup.md)

### ReleasePilot Prompts

- [Release Workflow Commands](release-pilot/prompts/release-workflow-commands.md)

---
*End of Index*
