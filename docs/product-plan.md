# ReleaseScribe Product Plan

ReleaseScribe is the standalone release communication tool in the ReleaseScribe / ReleasePilot product family.

## Product Goal

> "Turn technical changes into release-ready communication artifacts for developers, maintainers, release reviewers, and stakeholders."

## Core Responsibilities

1. **Release Communication:** Generate "What's new?" and full changelog from commit history.
2. **SemVer Recommendation:** Provide deterministic, authoritative version bump recommendations.
3. **Conventional Commit Parsing:** Parse and structure commit data as the basis for all output.
4. **Renderer Extensibility:** Support custom output formats via pluggable renderers.

ReleaseScribe is standalone and does not depend on ReleasePilot.

## Command Identity

- `release-scribe init` — Initialize a changelog for a project.
- `release-scribe whats-new` — Generate release notes for the current release (default).
- `release-scribe changelog` — Generate the full changelog history from git tags.
- `release-scribe recommend` — Recommend the next SemVer bump based on commits since the last tag.

All commands are analysis/generation only. Mutating release steps (tagging, pushing) belong to ReleasePilot.

## Outputs and Artifacts

### Markdown Artifacts

| Artifact | Audience | Content |
|---|---|---|
| `WHATS_NEW.md` (default) | General / Non-technical | Current release notes |
| `CHANGELOG.md` | Developers | Full commit history grouped by type |

### Integration Artifacts

- **JSON output** (`--format=json`): Machine-readable release data and recommendations for ReleasePilot and CI pipelines.
  - Fields: `recommendation`, `releases`, `metadata`

Artifact directory convention (e.g., `docs/releases/`) is deferred post-v2.

## Integration

ReleaseScribe provides its output for consumption by ReleasePilot and CI pipelines via:

- CLI (`--format=json`)
- PHP Library API: Communication Generator, SemVer Recommender

ReleaseScribe must not depend on ReleasePilot.

## Key Concepts

### Deterministic Recommendation

The `recommend` command is analysis-only and authoritative based on commit parsing. It does not create tags or perform a release. AI must not authorize breaking-change decisions — deterministic code handles final decisions.

### Renderer Extensibility

Custom renderers allow diverse output formats (GitHub release notes, internal formats, etc.). See `docs/rendering.md`.

### Phase 5 AI (post-v2)

AI-assisted communication (summaries, audience-aware wording) is planned for Phase 5, after the v2.0.0 release. AI output will be suggestive, not authoritative. Deterministic parsing and recommendation remain the foundation. See `docs/roadmap.md`.

## Status

v2.0.0 local revalidation complete as of 2026-05-10. External rebrand tasks (GitHub rename, Packagist registration) pending human execution.

Cross-product planning: `release-tools/docs/core-planning-review.md` *(local workspace path — not available in CI or for external contributors)*
