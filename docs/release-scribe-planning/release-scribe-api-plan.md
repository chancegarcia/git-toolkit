# ReleaseScribe API Plan

ReleaseScribe will provide a stable PHP API for consumption by other tools, primarily ReleasePilot.

## Core Services

### Communication Generator

- Input: `ChangeLogData` or commit range.
- Output: Rendered strings or structured `Communication` objects.

### SemVer Recommender

- Input: Commit collection.
- Output: `Recommendation` object (Major/Minor/Patch/None).

### AI Service

- Input: Structured deterministic data.
- Output: AI-generated summaries and audience-aware content.

## Structured Outputs

### JSON Summary

ReleaseScribe will support a `--format=json` option for its commands. The JSON output will include:

- `recommendation`: The SemVer bump recommendation.
- `releases`: Array of release data.
- `metadata`: AI generation details, token usage (if applicable), and traceability links.
