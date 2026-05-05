# ReleasePilot Product Plan

ReleasePilot is Product 2 of the two-product split. It is a guided release workflow tool that orchestrates the release
process by consuming outputs from ReleaseScribe.

## Product Goal

> “Help teams navigate the release process with confidence by providing a guided, automated, and verifiable workflow.”

## Core Responsibilities

1. **Release Orchestration:** Guided steps from development to tagged release.
2. **Verification:** CI-friendly checks to ensure a release is safe.
3. **Planning:** Previewing the impact and artifacts of a release before execution.
4. **Integration:** Consuming ReleaseScribe for communication and SemVer recommendations.
5. **Automation:** Handling the "mutating" steps of a release (tagging, commits, etc.).

## Command Identity

ReleasePilot commands focus on the workflow state and execution:

- `release-pilot check`: Verify release readiness (CI gates).
- `release-pilot plan`: Preview the next release impact and artifacts.
- `release-pilot prepare`: Perform local preparation steps.
- `release-pilot major`: Execute a major release.
- `release-pilot minor`: Execute a minor release.
- `release-pilot patch`: Execute a patch release.

## Integration

See [Release Workflow Commands Prompt](prompts/release-workflow-commands.md) for detailed implementation plans.

## Key Concepts

### Release Manifest

A structured file that defines the state and intent of a release. It serves as the single source of truth for downstream
automation and human review.

### Guided Workflow

A series of interactive or automated steps that ensure all release criteria are met (tests pass, docs generated, version
bumped correctly).

### ReleaseScribe Integration

ReleasePilot does not implement changelog parsing or AI summarization itself. It calls ReleaseScribe to get:

- Recommended version bump.
- "What's new?" content.
- Developer changelog updates.
- JSON metadata for the release.
