You are working in the existing PHP project that is being prepared to become **ReleaseScribe**.

Goal: reorganize the large product split and rebrand planning documentation into smaller, easier-to-reference
documentation files, while preserving the decisions and making the next execution steps clear.

This is a **documentation organization task only**.

Do **not** implement ReleaseScribe code changes.

Do **not** implement ReleasePilot code changes.

Do **not** create or modify files outside this repository.

As far as this task is concerned, the future `release-tools` parent workspace is **not available**. Keep all
ReleasePilot planning docs inside this repository under `docs/release-pilot/`.

## Important first action

Before splitting or reorganizing `docs/product-split-and-rebrand-plan.md`, update:

- `docs/generic-phpstorm-ai-chat-prompt-delivery-format.md`

Add drift prevention and prompt safety guidance.

The added guidance must cover:

- When a task affects product boundaries, shared terminology, integration expectations, command names, package names,
  roadmap sequencing, or ReleaseScribe/ReleasePilot responsibilities, relevant documentation should be checked for
  drift.
- ReleaseScribe-specific docs should stay focused on ReleaseScribe.
- ReleasePilot-specific docs should stay under `docs/release-pilot/` while this repo is still the organizing location.
- Prefer moving product-specific docs to the owning documentation area instead of duplicating content.
- If shared docs or shared context must exist in more than one place later, identify which copy is authoritative or
  update both intentionally.
- Do not allow ReleasePilot-specific plans to drift inside ReleaseScribe-focused docs.
- If a generated or extracted Junie prompt is encountered during an organization task, do **not** execute it
  automatically.
- Before running any extracted or generated prompt, stop and present the prompt for human review and approval.
- This review-before-execution rule applies even when Junie is operating in a more autonomous/brave mode.

Keep this update consistent with the existing final delivery expectations in that file.

## Files to inspect before making changes

Inspect the current documentation structure before making changes, including:

- `docs/product-split-and-rebrand-plan.md`
- `docs/project-fork/initial-steps.md`
- `docs/project-fork/organize-split-docs.md`
- `docs/generic-phpstorm-ai-chat-prompt-delivery-format.md`
- `docs/roadmap.md`
- `docs/rebrand.md`
- `docs/ai-assisted-changelog-generation-feature-prompt.md`
- `docs/release-pilot/`
- `readme.md`, only if documentation changes create user-facing inconsistency

Do not rely on or modify:

- files inside `docs/tmp`
- `docs/temp.md`

unless the user explicitly asks for those ignored locations in a separate task.

## Main documentation organization goals

Reorganize `docs/product-split-and-rebrand-plan.md` so it becomes a concise index/overview rather than one large
planning document.

Preserve the important decisions and move detailed content into smaller, focused files.

The goal is to reduce documentation noise and make the current process easier to track.

Do not force one file per section. Group sections together when they naturally belong together.

Keep each generated documentation file under 500 lines where practical. If a file would exceed 500 lines, split it by
topic unless doing so would make the documentation harder to follow. If a file intentionally exceeds 500 lines, explain
why in the final summary.

## Required file/path decisions

Use these locations for extracted prompts and organized planning docs:

### ReleaseScribe/general prompts

Put ReleaseScribe or project-fork prompts under:

- `docs/prompts/`

Expected extracted prompt files include, where applicable:

- `docs/prompts/shared-plan-file-cleanup.md`
- `docs/prompts/release-scribe-rebrand.md`
- `docs/prompts/release-scribe-phase-5-ai-assisted-release-communication.md`

### ReleasePilot prompts

Put ReleasePilot-specific prompts under:

- `docs/release-pilot/prompts/`

Expected extracted prompt files include, where applicable:

- `docs/release-pilot/prompts/release-workflow-commands.md`

If an equivalent ReleasePilot prompt already exists at `docs/release-pilot/release-workflow-commands-prompt.md`, move or
consolidate it into the approved prompt location above, then update links.

### Project fork execution tracking

Move `# Section 16 — Recommended Immediate Execution Order` from:

- `docs/product-split-and-rebrand-plan.md`

into:

- `docs/project-fork/initial-steps.md`

`docs/project-fork/initial-steps.md` should become the execution checklist for the split/fork process.

Structure it with clear headings and checkboxes where helpful.

It should include review-before-execution notes for prompt-based steps.

When a step says to run a prompt, replace the embedded instruction with a link to the extracted prompt file and clearly
state that the prompt must be reviewed and approved before execution.

Do **not** execute any of those prompts as part of this task.

### Current task prompt tracking

Use:

- `docs/project-fork/organize-split-docs.md`

to document this organization task if useful. If this file remains empty or redundant after the cleanup, either populate
it with the current task purpose and summary or leave a concise pointer to the relevant prompt/checklist file.

## Suggested grouping for split documentation

Use practical judgment, but this grouping is approved as a starting point.

### Product split index/overview

Keep `docs/product-split-and-rebrand-plan.md`, but convert it into a concise index.

It should include:

- purpose
- current product split summary
- authoritative current direction
- links to the detailed split-out docs
- clear note that ReleaseScribe remains this repository
- clear note that ReleasePilot docs are transitional under `docs/release-pilot/` for now
- clear note that the future move into the ReleasePilot repository is separate and not part of this task

Do not leave the full original long document in this file.

### Project fork docs

Create or update files under `docs/project-fork/` for process-oriented material.

Recommended files:

- `docs/project-fork/initial-steps.md`
- `docs/project-fork/risks-and-decisions.md`
- `docs/project-fork/organize-split-docs.md`, if useful

Move or summarize material from:

- Section 16 — Recommended Immediate Execution Order
- Section 17 — Potential Issues With the Proposed Plan
- Section 18 — Decisions and Questions to Revisit Later

### ReleaseScribe docs

Keep or create ReleaseScribe-focused docs for:

- product responsibilities and boundaries
- ReleaseScribe roadmap
- rebrand checklist
- immediate ReleaseScribe priorities
- ReleaseScribe integration outputs/API/artifacts for ReleasePilot
- external rebrand checklist
- migration notes if useful

Use existing files when appropriate:

- `docs/roadmap.md`
- `docs/rebrand.md`
- `docs/ai-assisted-changelog-generation-feature-prompt.md`

Optional new docs if they make the split clearer:

- `docs/release-scribe-api-plan.md`
- `docs/release-scribe-artifacts-plan.md`
- `docs/migration-to-release-scribe.md`

Do not duplicate large blocks unnecessarily. Prefer concise references between docs.

### ReleasePilot docs

Keep ReleasePilot planning inside this repository under:

- `docs/release-pilot/`

Do not move ReleasePilot docs into an external `release-tools` folder during this task.

Create or update ReleasePilot docs as needed, such as:

- `docs/release-pilot/product-plan.md`
- `docs/release-pilot/release-scribe-integration-plan.md`
- `docs/release-pilot/ci-workflow-plan.md`
- `docs/release-pilot/release-manifest-plan.md`
- `docs/release-pilot/prompts/release-workflow-commands.md`

Only create these files if there is enough relevant content to justify them.

The ReleasePilot docs should clearly say:

- ReleasePilot is Product 2.
- ReleasePilot is separate from ReleaseScribe.
- ReleasePilot remains documentation/planning-only until ReleaseScribe is stable enough to integrate with.
- ReleasePilot owns guided release workflow orchestration.
- ReleasePilot should consume ReleaseScribe rather than duplicate ReleaseScribe internals.
- ReleasePilot docs in this repository are transitional organization docs for later movement into the ReleasePilot
  repository.

## Embedded prompt extraction requirements

The large plan currently contains embedded Junie prompts.

Extract each embedded prompt into its own appropriately named file.

At minimum, handle:

- ReleaseScribe Phase 5 Planning and Implementation
- ReleaseScribe Rebrand
- Shared Plan File Cleanup
- ReleasePilot Release Workflow Commands

When extracting prompts:

- Preserve the `START PROMPT` and `END PROMPT` boundary lines.
- Keep each prompt as Markdown.
- Do not wrap the prompt in code fences.
- Make sure each prompt uses the current file name:
    - `docs/generic-phpstorm-ai-chat-prompt-delivery-format.md`
- Update outdated references to the old typo version of the filename:
    - `docs/generic-phpstorm-ai-chat-prompt-delivery-format.md`
- Make sure prompts instruct Junie to inspect the project before changes.
- Make sure prompts instruct Junie not to rely on or modify `docs/tmp` or `docs/temp.md`.
- Make sure prompts instruct Junie to run relevant available tooling or document why tooling cannot be run.
- Make sure prompts do not introduce unrelated scope.
- Make sure ReleaseScribe prompts do not implement ReleasePilot workflow behavior.
- Make sure ReleasePilot prompts stay under `docs/release-pilot/prompts/`.

After extracting prompts, replace the embedded prompt sections in `docs/product-split-and-rebrand-plan.md` with links to
the extracted prompt files or remove them from the index in favor of a prompt list.

## Prompt execution safety requirement

During this documentation organization task:

- Do not execute any extracted prompt.
- Do not begin the rebrand implementation.
- Do not begin Phase 5 AI implementation.
- Do not begin ReleasePilot implementation.
- Do not run the Shared Plan File Cleanup prompt as an implementation prompt.
- If a step says “run prompt,” change it to “review and approve prompt before execution” and link to the prompt file.

This task prepares prompts and documentation for review. It does not execute those prompts.

## Content requirements to preserve

Make sure the reorganized docs preserve these current decisions:

- Product 1 is ReleaseScribe.
- Product 2 is ReleasePilot.
- Existing repository becomes ReleaseScribe.
- ReleaseScribe is both a library and CLI tool.
- ReleaseScribe package target is `chancegarcia/release-scribe`.
- ReleaseScribe binary target is `release-scribe`.
- ReleaseScribe namespace target is `Chance\ReleaseScribe`.
- ReleasePilot package target is `chancegarcia/release-pilot`.
- ReleasePilot binary target is `release-pilot`.
- ReleasePilot namespace target is `Chance\ReleasePilot`.
- ReleaseScribe commands use end-user CLI style:
    - `release-scribe whats-new`
    - `release-scribe changelog`
    - `release-scribe init`
    - `release-scribe recommend`
- ReleasePilot commands use end-user CLI style:
    - `release-pilot check`
    - `release-pilot plan`
    - `release-pilot prepare`
    - `release-pilot major`
    - `release-pilot minor`
    - `release-pilot patch`
- ReleaseScribe creates release communication.
- ReleasePilot guides the release workflow.
- Correct dependency direction is:
    - ReleasePilot → ReleaseScribe
- Incorrect dependency direction is:
    - ReleaseScribe → ReleasePilot
- AI is optional in both products.
- Deterministic parsing and recommendation remain authoritative.
- AI enhances communication; it does not drive release decisions.
- ReleaseScribe must not depend on ReleasePilot.
- ReleasePilot should consume ReleaseScribe.
- ReleasePilot remains documentation/planning-only until ReleaseScribe is stable enough to integrate with.
- Rebrand ReleaseScribe before implementing Phase 5 AI-assisted release communication.
- No old binary, command, namespace, or package compatibility wrappers are required unless an extremely small, low-risk
  reason is discovered later.
- JSON summary output should be included in ReleaseScribe Phase 5 if practical, or at least have a concrete plan.
- ReleasePilot-specific docs should stay organized under `docs/release-pilot/` for now.
- Future movement into a separate ReleasePilot repository is separate and should not be performed by this task.

## Documentation consistency requirements

Update links and references after moving content.

Make sure references to the generic prompt format use:

- `docs/generic-phpstorm-ai-chat-prompt-delivery-format.md`

Do not refer to the old typo filename.

Make sure README is updated only if the documentation organization changes user-facing project direction, usage,
commands, configuration, or product identity in a way that makes README misleading.

Do not update source code.

Do not update generated changelog content unless there is a clear documentation navigation reason.

## Checks and tooling

This is documentation-only, but still inspect available project tooling.

Run appropriate lightweight checks if practical, such as:

- Composer validation, if available and reasonable
- Markdown/documentation checks, if configured
- PHPUnit only if documentation changes affect behavior expectations or project validation policy requires it
- PHPStan/static analysis only if relevant or configured as a standard project check

If a tool cannot be run or is not relevant for this documentation-only task, clearly document why.

Do not invent tooling.

## Final response requirements

When finished, provide a clear final summary.

The final summary must include:

1. Files created.
2. Files modified.
3. Files moved or consolidated.
4. Which original plan sections were moved where.
5. Which prompts were extracted and their new paths.
6. Confirmation that no extracted prompt was executed.
7. Confirmation that ReleasePilot docs stayed inside `docs/release-pilot/`.
8. Confirmation that `docs/product-split-and-rebrand-plan.md` is now a concise index.
9. Confirmation that `docs/project-fork/initial-steps.md` now contains the Section 16 execution order/checklist.
10. Confirmation that prompt review-before-execution guidance was added to
    `docs/generic-phpstorm-ai-chat-prompt-delivery-format.md`.
11. Any files that intentionally exceed 500 lines and why.
12. Checks/tooling run and results.
13. Any checks/tooling not run and why.
14. Any remaining follow-up recommendations or human review items.

## Acceptance criteria

- `docs/generic-phpstorm-ai-chat-prompt-delivery-format.md` is updated first with drift prevention and prompt
  review-before-execution policy.
- `docs/product-split-and-rebrand-plan.md` is converted into a concise index/overview.
- Section 16 content is moved into `docs/project-fork/initial-steps.md`.
- Prompt-based steps in `docs/project-fork/initial-steps.md` link to extracted prompt files and require review before
  execution.
- Embedded prompts are extracted into approved prompt folders:
    - ReleaseScribe/general prompts under `docs/prompts/`
    - ReleasePilot prompts under `docs/release-pilot/prompts/`
- ReleasePilot docs remain inside `docs/release-pilot/`.
- No files are moved outside this repository.
- No extracted prompt is executed.
- No source code implementation changes are made.
- No `docs/tmp` or `docs/temp.md` changes are made.
- Important product split, rebrand, AI, dependency direction, and execution-order decisions are preserved.
- Links and references are updated to the current prompt delivery filename.
- Documentation noise is reduced and split into easier-to-reference files.
- Final summary includes the required mapping report and tooling report.