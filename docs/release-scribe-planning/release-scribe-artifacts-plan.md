# ReleaseScribe Artifacts Plan

ReleaseScribe produces several types of artifacts used for release communication and integration.

## Markdown Artifacts

### What's New? (`WHATS_NEW.md`)

- **Default Audience:** General / Non-technical.
- **Content:** AI-assisted summaries of major features and improvements.
- **Purpose:** Customer-facing communication.

### Changelog (`CHANGELOG.md`)

- **Default Audience:** Developers.
- **Content:** Deterministic list of all commits grouped by type.
- **Purpose:** Technical change tracking.

## Integration Artifacts

### JSON Summary

- **Content:** Machine-readable representation of the release data and recommendations.
- **Purpose:** Consumption by ReleasePilot and CI pipelines.

## Artifact Directory Convention

(Deferred) - Currently, artifacts are generated in the project root or specified output directory. A formal convention
like `docs/releases/` or `.release-scribe/` will be revisited.
