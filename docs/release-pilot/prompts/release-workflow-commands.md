## ReleasePilot Product Plan — Release Workflow Commands

START PROMPT

You are working in the separate ReleasePilot PHP project.

ReleasePilot is Product 2 of the product split.

Product 1 (ReleaseScribe) is the standalone “What’s new” / changelog / release communication generator. Product 1 owns:

- Conventional Commit parsing
- changelog generation
- “What’s new” generation
- renderer extensibility
- SemVer/release impact recommendation
- AI-assisted release communication

ReleasePilot owns the broader guided release workflow.

Goal: implement and document ReleasePilot’s release workflow commands as a standalone product that can integrate with
Product 1.

ReleasePilot should not duplicate Product 1’s changelog/release communication internals unless there is a deliberate
adapter or compatibility reason.

ReleasePilot should use Product 1 as:

- a Composer dependency, preferred when available
- a library/API integration
- a CLI integration, if necessary
- a generated artifact consumer
- a structured JSON output consumer

Product direction:

ReleasePilot is a guided release workflow tool.

It should help teams answer:

- Are we ready to release?
- What changed?
- What version should this release be?
- What files/artifacts will be generated?
- What warnings or blockers exist?
- What release notes should be reviewed?
- What tag would be created?
- What should CI validate?
- What local preparation steps are safe to perform?

ReleasePilot should be useful locally and in CI.

General implementation guidance:

- Inspect the existing project before making changes.
- Prefer factories, dependency injection, and lazy initialization where practical.
- Avoid unnecessary object construction, especially for services or clients that may not be used during a command run.
- Keep dependencies explicit and testable.
- Do not introduce global state or hidden runtime coupling unless there is a clear compatibility reason.
- Keep local workflows safe by default.
- Do not push to remotes by default.
- Do not make external network calls by default.
- Preserve deterministic behavior for release decisions.
- Treat AI-generated communication as reviewable content, not release authority.

Core command model:

ReleasePilot should support or plan the following command groups:

Priority 1:

- check
- plan

Priority 2:

- prepare

Priority 3:

- major
- minor
- patch

Priority 4 / later:

- publish
- checklist
- ci:init
- summary

The exact command names may be adjusted to match the chosen CLI style.

Because the binary is expected to be named release-pilot, commands should not need to repeat release unnecessarily.

Preferred command examples:

- release-pilot check
- release-pilot plan
- release-pilot prepare
- release-pilot major
- release-pilot minor
- release-pilot patch

Alternative Symfony-style command names are acceptable if consistent with the project architecture.

Core requirements:

1. Product split and integration boundary.

    - ReleasePilot is a separate product from the release communication generator.
    - ReleasePilot should integrate Product 1 instead of duplicating it.
    - Product 1 is responsible for generating “What’s new” documents, changelogs, AI release communication, and
      SemVer/release impact recommendations.
    - ReleasePilot is responsible for workflow decisions, validation, planning, preparation, and release orchestration.
    - ReleasePilot may consume:
        - Product 1 library APIs
        - Product 1 generated Markdown artifacts
        - Product 1 generated JSON summaries
        - Product 1 release recommendation results
    - ReleasePilot must remain useful even if AI is disabled.
    - ReleasePilot should not require AI unless explicitly configured.

2. Release readiness command.

   Implement or plan:

    - release-pilot check

   Purpose:

   Validate whether the project is ready to prepare a release.

   The command should be safe and non-mutating.

   Possible checks:

    - working tree is clean
    - branch is allowed
    - local branch is up to date if remote checks are enabled
    - version source exists
    - current version is valid
    - next version can be calculated
    - Product 1 integration is available if configured
    - “What’s new” generation can run if configured
    - release recommendation can be calculated
    - requested tag does not already exist locally
    - remote tag collision check if remote checks are explicitly enabled
    - config file is valid
    - required environment variables exist
    - AI configuration is valid if AI is required
    - no unresolved merge conflict markers
    - required quality commands are configured or have passed if ReleasePilot is responsible for checking them

   The command should support CI-friendly behavior.

   Suggested options:

    - --ci
    - --format=json
    - --config=/path/to/config.php
    - --config-mode=merge
    - --config-mode=replace
    - --fail-on=condition

3. Release plan command.

   Implement or plan:

    - release-pilot plan

   Purpose:

   Preview what ReleasePilot would do if a release were prepared now.

   The command should be safe and non-mutating.

   It should show:

    - current version
    - recommended bump
    - requested bump if provided
    - next version
    - target tag
    - commit range
    - detected breaking changes
    - Product 1 release communication artifacts that would be generated
    - “What’s new” preview or path to preview
    - technical changelog preview or path to preview if configured
    - files that would be written
    - whether a release commit would be created
    - whether a tag would be created
    - warnings
    - blockers
    - CI-readable status

   Suggested options:

    - --bump=major|minor|patch
    - --target=1.2.3
    - --format=text|json
    - --dry-run
    - --with-notes
    - --config=/path/to/config.php
    - --config-mode=merge|replace

4. Release prepare command.

   Implement or plan:

    - release-pilot prepare

   Purpose:

   Perform safe local release preparation steps.

   Possible responsibilities:

    - determine current version
    - determine recommended bump
    - determine requested or target version
    - warn/fail on recommendation mismatch unless forced
    - call Product 1 to generate “What’s new” document
    - call Product 1 to generate technical changelog if configured
    - write release artifacts
    - write release manifest
    - update configured version source if supported
    - optionally create a release checklist
    - optionally create a local release commit
    - optionally create a local annotated tag

   Safe defaults:

    - no remote push by default
    - no publish by default
    - no destructive file changes without clear output
    - dry-run available
    - fail on dangerous recommendation mismatch unless forced
    - annotated tags by default if tagging is enabled
    - lightweight tags opt-in only

   Suggested options:

    - --bump=major|minor|patch
    - --target=1.2.3
    - --dry-run
    - --force
    - --no-notes
    - --with-notes
    - --no-tag
    - --tag
    - --no-commit
    - --commit
    - --format=text|json
    - --config=/path/to/config.php
    - --config-mode=merge|replace

5. Convenience bump commands.

   Implement or plan:

    - release-pilot major
    - release-pilot minor
    - release-pilot patch

   These should be convenience wrappers around:

    - release-pilot prepare --bump=major
    - release-pilot prepare --bump=minor
    - release-pilot prepare --bump=patch

   Each command should:

    - determine current version
    - request the corresponding SemVer bump
    - integrate release recommendation
    - warn or fail on mismatch unless forced
    - generate/update “What’s new” output through Product 1 by default if configured
    - avoid full-history generation unless explicitly configured
    - support dry-run
    - support config overrides
    - support safe tagging behavior if configured

6. Version source.

   Initial implementation may assume APP_VERSION if that matches the project direction.

   Document this clearly.

   Future configuration should allow users to configure where the version comes from.

   Possible future sources:

    - .env
    - Composer metadata
    - project config file
    - package manager metadata
    - custom version provider
    - Git tag-derived version
    - callback/service implementation

7. Release recommendation integration.

   Use Product 1’s release recommendation/impact analysis if available.

   Example behavior:

    - If the user requests patch but commits indicate minor, warn or fail unless forced.
    - If the user requests minor but commits contain breaking changes, strongly warn or fail unless forced.
    - If only non-release-impacting commits are present, recommend no release or require explicit force.
    - If Product 1 is unavailable and recommendation is required, fail with a clear error.

   ReleasePilot should treat deterministic recommendation as authoritative.

   AI output should not change the recommended bump.

8. Product 1 release communication integration.

   ReleasePilot should use Product 1 for release communication artifacts.

   Product 1 may provide:

    - “What’s new” Markdown
    - technical changelog Markdown
    - JSON change summary
    - SemVer recommendation result
    - AI-generated release summary
    - traceability metadata

   ReleasePilot should be able to configure:

    - whether to generate release communication
    - where to write generated files
    - which audience outputs to request
    - whether AI is allowed, disabled, or required
    - whether Product 1 failures block release preparation
    - whether generated artifacts are included in release manifest

9. Release manifest.

   Implement or plan a release manifest artifact.

   Suggested file:

    - release-manifest.json

   Possible contents:

    - project name
    - current version
    - next version
    - tag name
    - date/time
    - commit range
    - requested bump
    - recommended bump
    - recommendation mismatch status
    - breaking changes status
    - generated artifacts
    - Product 1 output paths
    - AI enabled/disabled status
    - release check results
    - release plan summary

   The manifest should be useful for CI and later publish steps.

10. CI-friendly output.

    ReleasePilot should be designed for CI adoption.

    Support or plan:

    - --format=json
    - stable exit codes
    - dry-run mode
    - no network calls by default
    - no remote mutation by default
    - release manifest output
    - generated release notes as artifacts
    - machine-readable warnings and blockers
    - configurable failure policies

    Possible failure policy options:

    - --fail-on=recommendation-mismatch
    - --fail-on=breaking-change
    - --fail-on=invalid-commits
    - --fail-on=dirty-working-tree
    - --fail-on=tag-exists

11. Tagging workflow.

    Document and/or prepare support for:

    - tag prefix, such as v
    - creating Git tags
    - annotated tags by default
    - lightweight tags as opt-in
    - custom tag message configuration
    - dry-run mode
    - optional push behavior later

    Annotated tags must be the default for release auto-tagging.

    Default annotation message:

    - <tag-name> - <YYYY-MM-DD HH:MM:SS>

    Explain why annotated tags are preferred:

    - release/tag date visibility in Git metadata
    - compatibility with git push --follow-tags

    Do not push tags by default unless explicitly required and documented.

12. Release commit support.

    Implement or plan optional release commit support.

    Suggested option:

    - --commit

    Possible commit message:

    - chore(release): prepare v1.2.3

    Safe defaults:

    - do not commit by default unless configured
    - show files that would be committed
    - support dry-run
    - support custom commit message
    - fail if working tree has unrelated changes unless forced/configured

13. Release checklist.

    Implement or plan optional checklist generation.

    Possible output:

    - release-checklist.md

    Checklist items may include:

    - confirm version bump
    - review “What’s new” document
    - review technical changelog
    - confirm breaking changes
    - run tests
    - run static analysis
    - confirm tag name
    - create annotated tag
    - push release commit
    - push tag
    - publish release

14. Publish workflow.

    Publishing should be planned as a later feature.

    Possible command:

    - release-pilot publish

    Possible integrations:

    - GitHub Releases
    - GitLab Releases
    - Slack/Teams notifications
    - package registry metadata
    - website/docs update hooks

    Constraints:

    - local-first by default
    - no network calls by default
    - explicit provider configuration
    - dry-run support
    - tests must use mocked clients only

15. Runtime config support.

    CLI commands should support specifying an alternate config file at runtime.

    Suggested option:

    - --config=/path/to/config.php

    Alternate config modes:

    - merge
    - replace

    Suggested option:

    - --config-mode=merge
    - --config-mode=replace

    Merge mode should allow users to override only specific settings.

    Replace mode should be explicit and predictable.

16. Symfony bundle compatibility.

    Keep configuration shape compatible with a future Symfony bundle if relevant.

    Avoid hard-coding assumptions that would make a Symfony configuration tree difficult later.

17. Documentation requirements.

    Document:

    - ReleasePilot is a standalone product.
    - Product 1 (ReleaseScribe) is the standalone “What’s new” / changelog / release communication generator.
    - ReleasePilot integrates Product 1 where possible.
    - ReleasePilot owns release workflow orchestration.
    - Product 1 owns release communication generation.
    - check command
    - plan command
    - prepare command
    - major/minor/patch convenience commands
    - APP_VERSION assumption if used
    - version source configuration
    - release recommendation mismatch behavior
    - Product 1 integration
    - AI behavior and limitations
    - current release / “What’s new” default when generating notes
    - config override support
    - release manifest behavior
    - CI-friendly output
    - tagging behavior
    - annotated tags by default
    - dry-run behavior
    - no push by default
    - publish is later/optional if not implemented

    Update README when behavior, configuration, usage, commands, output, or extension points change.

    Ignore docs/tmp/ and docs/temp.md when making documentation updates unless the user explicitly asks to modify those
    paths.

Testing/tooling requirements:

- Add or update tests for:
    - release check behavior
    - release plan behavior
    - major bump
    - minor bump
    - patch bump
    - prepare command behavior
    - recommendation mismatch behavior
    - force behavior
    - dry-run behavior
    - config override behavior
    - Product 1 integration or adapter behavior
    - Product 1 unavailable behavior
    - release manifest output
    - JSON output
    - no accidental complete-history generation by default
    - no accidental remote mutation
    - annotated tag configuration if implemented
    - release commit behavior if implemented

- Avoid tests that require real external network calls.
- Avoid tests that mutate real Git remotes.
- Use fakes/mocks/stubs for Product 1 integrations where needed.
- Run relevant project tooling:
    - Composer validation if available
    - PHPUnit
    - PHPStan if configured
    - Any configured linting/static analysis commands

If a tool cannot be run, clearly document why.

Acceptance criteria:

- ReleasePilot is documented as a standalone product.
- Product 1 integration boundary is clear.
- check and plan are implemented or clearly documented as priority commands.
- prepare is implemented or clearly documented as the central release workflow command.
- major, minor, and patch are implemented or documented as convenience wrappers around prepare.
- ReleasePilot uses current release / “What’s new” generation through Product 1 by default when generating notes.
- ReleasePilot integrates deterministic release recommendation logic.
- Requested release type mismatch behavior is safe and documented.
- Runtime config override behavior is supported or clearly documented.
- Version source behavior is documented.
- Release manifest behavior is implemented or planned.
- CI-friendly structured output is implemented or planned.
- README and docs are consistent.
- Documentation updates do not modify docs/tmp/ or docs/temp.md unless explicitly requested.
- Implementation prefers factories, dependency injection, and lazy initialization.
- ReleasePilot does not duplicate Product 1 internals unnecessarily.
- ReleasePilot remains safe by default: no push, no publish, no remote mutation unless explicitly configured.

END PROMPT