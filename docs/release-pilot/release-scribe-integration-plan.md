# ReleaseScribe Integration Plan

ReleasePilot consumes ReleaseScribe to handle the data-heavy and communication-heavy parts of the release process.

## Dependency

ReleasePilot will include `chancegarcia/release-scribe` as a Composer dependency.

## Integration Points

### 1. SemVer Recommendation

- ReleasePilot calls `release-scribe recommend --format=json`.
- It uses the recommendation to suggest the next version bump to the user or to automatically set the version for the
  next tag.

### 2. "What's New" and Changelog Generation

- ReleasePilot calls `release-scribe whats-new` and `release-scribe changelog` during the preparation phase.
- It ensures these artifacts are generated and included in the release commit.

### 3. AI Summaries

- ReleasePilot leverages ReleaseScribe's AI capabilities to provide high-level summaries in its own planning output (
  `release-pilot plan`).

## Consumption Strategy

ReleasePilot should prefer using the structured JSON output from ReleaseScribe for its internal decision-making. For
human-facing artifacts, it should let ReleaseScribe handle the rendering.
