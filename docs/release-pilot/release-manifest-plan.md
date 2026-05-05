# Release Manifest Plan

The Release Manifest is the central data structure for a planned or completed release in ReleasePilot.

## Purpose

- **Single Source of Truth:** Provides a machine-readable summary of what is in a release.
- **Inter-process Communication:** Allows CI steps to pass release data safely.
- **Traceability:** Records the inputs, decisions, and artifacts of a release.

## Content (JSON)

- `version`: The target version for the release.
- `previous_version`: The previous version/tag.
- `commits`: Reference to the commit range included.
- `recommendation`: The SemVer impact analysis from ReleaseScribe.
- `artifacts`: Links or paths to generated artifacts (`CHANGELOG.md`, `WHATS_NEW.md`).
- `metadata`: Release metadata (date, author, AI usage).

## Workflow

1. **`release-pilot plan`** generates a draft manifest.
2. **Human or CI** reviews the manifest.
3. **`release-pilot prepare/major/minor/patch`** uses the manifest to execute the release.
