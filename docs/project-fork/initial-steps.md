# Project Fork Initial Steps

This document serves as the execution checklist for the Product Split and Rebrand process.

## Review Policy

- **Stop and Review:** If a step instructs you to "run prompt," you must first review and approve the linked prompt file
  before execution.
- **Human Approval Required:** This review-before-execution rule applies even when Junie is operating in a more
  autonomous/brave mode.

## Phase 1 — Preparation and Workspace Setup

### Step 1 — Create Shared Parent Workspace

Create the shared parent folder: **`release-tools`**

- This folder is only a local workspace.
- Do **not** initialize `release-tools` as a Git repository.
- Each child project should remain its own Git repository.

### Step 2 — Create Initial ReleasePilot Folder

Create: **`release-tools/release-pilot`**

- At this stage, this folder is documentation/planning-only.
- It does not need to be a full PHP project yet.
- Suggested initial structure: `release-tools/release-pilot/docs/`
- Initialize a local Git repository inside `release-tools/release-pilot` if desired so documentation changes can be
  tracked.
- Do not begin ReleasePilot product code implementation yet.
- **Note:** This is a future manual step.

### Step 3 — Organize Split and Rebrand Documentation

Organize the documentation in the current repository before cloning as `release-scribe`.

- Update or create:
    - `docs/product-split-and-rebrand-plan.md`
    - `docs/ai-assisted-changelog-generation-feature-prompt.md`
    - `docs/release-pilot/prompts/release-workflow-commands.md`
- Optionally create:
    - `docs/release-pilot/release-scribe-integration-notes.md`
    - `docs/release-scribe-api-plan.md`
    - `docs/release-scribe-artifacts-plan.md`
    - `docs/migration-to-release-scribe.md`
- Update `docs/generic-phpstorm-ai-chat-prompt-delivery-format.md` with drift prevention guidance.

### Step 4 — Run Documentation Cleanup Prompt

- **Action:** Review and approve the prompt before execution.
- **Prompt File:** [Shared Plan File Cleanup](../prompts/shared-plan-file-cleanup.md)
- **Purpose:** Ensure ReleaseScribe docs are scoped correctly, ReleasePilot docs are separated, and README/roadmap are
  updated.

### Step 5 — Commit and Push Documentation Changes

1. Review the documentation changes.
2. Run available documentation/project checks.
3. Commit changes with Junie co-author trailer.
4. Push to `develop`.

## Phase 2 — Project Splitting (Future Steps)

*Note: These steps are future/manual and not part of the current documentation organization task.*

### Step 6 — Clone Current Repository as ReleaseScribe

Clone the current repository into: **`release-tools/release-scribe`**

### Step 7 — Archive and Remove Old Working Copy

1. Create an archive of the old `ChanceGitToolkit` folder.
2. Remove the old folder from the IDE.
3. Use `release-tools/release-scribe` as the active working copy.

### Step 8 — Move ReleasePilot Documentation

Move transitional ReleasePilot documentation from `release-scribe/docs/release-pilot/` to `release-pilot/docs/`.

- Update links and references in both projects.

### Step 9 — Configure the Shared Workspace

Open both projects in the IDE under the **`release-tools`** parent folder.

Validate:

- IDE recognizes both projects.
- Git roots are separate.
- Composer projects are indexed.
- AI Assistant/Junie can see both folders.

## Phase 3 — Execution (Future Steps)

*Note: These steps involve executing prompts and implementation, which require human review.*

### Step 10 — Finish Separating Products

- Continue with any remaining cleanup.
- Recommended prompt: Review and approve `docs/prompts/shared-plan-file-cleanup.md` if further cleanup is needed.

### Step 11 — Rebrand ReleaseScribe

- **Action:** Review and approve the prompt before execution.
- **Prompt File:** [ReleaseScribe Rebrand](../prompts/release-scribe-rebrand.md)
- **Order:** Rebrand ReleaseScribe *before* Phase 5 AI work to avoid rework.

### Step 12 — Complete External Rebrand Tasks

- Rename GitHub repository to `release-scribe`.
- Register/update Packagist package.
- Mark old package as abandoned.
- Update badges, links, and integrations.

### Step 13 — Implement Phase 5 AI-Assisted Release Communication

- **Action:** Review and approve the prompt before execution.
- **Prompt File:** [Phase 5 AI Implementation](../prompts/release-scribe-phase-5-ai-assisted-release-communication.md)

### Step 14 — Stabilize ReleaseScribe

- Ensure all tests and checks pass.
- Stabilize CLI behavior and API contracts.

### Step 15 — Begin ReleasePilot Implementation

- Begin ReleasePilot work after ReleaseScribe is stable.
- Focus on foundation, `check`, `plan`, and manifest commands first.
- **Action:** Review and approve the prompt before execution.
- **Prompt File:** [Release Workflow Commands](../release-pilot/prompts/release-workflow-commands.md)
