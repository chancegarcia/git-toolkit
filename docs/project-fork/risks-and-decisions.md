# Product Split Risks and Decisions

This document captures potential issues, risks, and important decisions regarding the ReleaseScribe / ReleasePilot
product split.

## Potential Issues and Risks

### Risk: Documentation Drift Between Products

If ReleasePilot docs live in both repositories, shared assumptions may drift.

**Mitigation:**

- Move ReleasePilot-specific docs from ReleaseScribe to ReleasePilot instead of copying them.
- Keep ReleaseScribe docs focused on ReleaseScribe.
- Keep ReleasePilot docs focused on ReleasePilot.
- Update generic AI/Junie prompting guidance to explicitly check for drift.
- When both projects are open under `release-tools`, ask AI Assistant or Junie to inspect both relevant docs if a change
  affects shared terminology, integration expectations, command names, roadmap sequencing, or package names.

### Risk: Parent Folder Git Confusion

If `release-tools` becomes a Git repository while child folders are also Git repositories, Git tooling can become
confusing.

**Mitigation:**

- Do not initialize Git in `release-tools`.
- Let each child folder be an independent Git repo.
- Use `release-tools` only as a containing workspace folder.

### Risk: Rebrand After AI Creates Extra Work

If Phase 5 AI work happens before rebrand, new AI classes, config keys, docs, tests, and prompts may need to be renamed
soon after.

**Mitigation:**

- Rebrand ReleaseScribe first.
- Then implement Phase 5.

### Risk: ReleasePilot Starts Before ReleaseScribe Has Stable Outputs

If ReleasePilot starts too early, it may depend on unstable ReleaseScribe internals.

**Mitigation:**

- ReleasePilot remains documentation/planning-only until ReleaseScribe is stable.
- ReleasePilot implementation begins only after ReleaseScribe has stable enough outputs and/or API contracts.
- Once ReleasePilot implementation begins, it can depend on ReleaseScribe directly.

### Risk: Old Working Copy Accidentally Used

If the old `ChanceGitToolkit` folder remains available, changes may accidentally happen there.

**Mitigation:**

- Archive/tarball `ChanceGitToolkit` after `release-scribe` is cloned.
- Remove `ChanceGitToolkit` from PhpStorm.
- Use only `release-tools/release-scribe` as the active ReleaseScribe working copy.

## Decisions and Revisit Items

### Old Binary and Command Aliases

**Decision:** No wrapper or aliasing is required. Adoption is low enough that the rebrand can be a clean cutover. Old
identity (binary, commands, namespace, package) can be removed.

### JSON Summary Output

**Decision:** ReleaseScribe should publish JSON summary output as part of Phase 5 if practical. If not completed, Phase
5 should at least define the JSON output contract.

### ReleasePilot Dependency on ReleaseScribe

**Decision:** ReleasePilot can depend on ReleaseScribe immediately when implementation starts, as ReleaseScribe will be
stabilized first.

### AI Optionality

**Decision:** AI remains optional in both products. Deterministic parsing and recommendation remain authoritative. AI
enhances communication but does not drive release decisions.

### Artifact Directory Convention

- **Status:** Deferred.
- **Question:** Should ReleaseScribe have a formal artifact directory convention (e.g., `docs/releases/`,
  `release-artifacts/`, `.release-scribe/`)?
- **Action:** Revisit after ReleaseScribe’s real output workflows are clearer.

### AI Traceability

**Decision:** Traceability should be optional or best-effort at first. The data model should not prevent stronger
traceability later. Include in Phase 5 planning.
