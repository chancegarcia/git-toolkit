# CI Workflow Plan

ReleasePilot is designed to be CI-friendly, providing clear signals and gates for automated release pipelines.

## CI Gates

### `release-pilot check`

- Validates the current state of the repository.
- Ensures the branch is up-to-date.
- Confirms all required release metadata is present.
- Returns non-zero exit code on failure.

### `release-pilot plan`

- Generates a preview of the upcoming release.
- Can output a **Release Manifest** (JSON) for downstream CI steps.

## Automated Execution

In a CI environment, ReleasePilot can be configured to:

- Automatically bump versions and create tags upon successful merge to a release branch.
- Push tags to the remote repository.
- Trigger external publishing webhooks.

## Safety Mechanisms

- **Dry-run mode:** All mutating commands will support a `--dry-run` flag.
- **Verification steps:** CI can be configured to require manual approval of the Release Manifest before proceeding with
  the actual release.
