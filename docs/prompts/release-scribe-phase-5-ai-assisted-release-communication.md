START PROMPT

You are working in the existing PHP project that is being rebranded as ReleaseScribe.

ReleaseScribe is Product 1 of a two-product split.

Product 1:

- Name: ReleaseScribe
- Package target: `chancegarcia/release-scribe`
- Binary target: `release-scribe`
- Role: standalone “What’s new” / changelog / release communication generator

Product 2:

- Name: ReleasePilot
- Package target: `chancegarcia/release-pilot`
- Binary target: `release-pilot`
- Role: guided release workflow tool that will consume or integrate ReleaseScribe later

Goal: plan and implement Phase 5 — AI-assisted release communication for ReleaseScribe.

Do not implement ReleasePilot workflow commands in this task.

Do not implement release preparation, release tagging, release publishing, release commits, branch policy enforcement,
or CI release gates in this task.

ReleaseScribe must remain useful without ReleasePilot.

ReleaseScribe must not depend on ReleasePilot.

AI must be optional.

Deterministic parsing and SemVer recommendation remain authoritative.

AI enhances communication; it does not drive release decisions.

Before making changes:

- Inspect the existing project structure.
- Inspect existing collectors, generators, renderers, services, data objects, commands, and tests.
- Inspect existing documentation, especially:
    - `docs/roadmap.md`
    - `docs/ai-assisted-changelog-generation-feature-prompt.md`
    - `docs/product-split-and-rebrand-plan.md`
- Do not rely on or modify files inside `docs/tmp`.
- Do not rely on or modify `docs/temp.md`.

Implementation guidance:

- Prefer factories, dependency injection, and lazy initialization.
- Avoid unnecessary object construction.
- Keep dependencies explicit and testable.
- Preserve backward compatibility where reasonable.
- Add or update tests for changed behavior.
- Avoid real external AI calls in tests.
- Use fake, mock, or stub AI clients.
- Do not commit real API keys or secrets.

Core requirements:

1. AI client abstraction.

   Add or refine an AI client abstraction suitable for release communication generation.

   It should allow tests to provide fake AI responses without network calls.

   It should not force a specific provider into core logic unless there is a deliberate adapter.

2. Structured AI input.

   AI input must be built from deterministic release communication data.

   Do not send raw full Git history by default.

   Include structured context such as:

    - generation mode
    - project name if available
    - target version or tag if available
    - commit range if available
    - grouped Conventional Commit sections
    - breaking changes
    - release recommendation
    - selected audience
    - selected summary style

3. Default to current release / “What’s new” mode.

   AI-assisted generation should default to current release / “What’s new” data.

   Complete-history AI generation must be opt-in.

4. Audience-aware release communication.

   Plan and implement audience support where practical.

   Suggested audiences:

    - general
    - developer
    - customer
    - marketing
    - management
    - support
    - internal

   The default audience should be general “What’s new”.

5. AI output model.

   AI output should be represented in structured data, not only raw text.

   Consider supporting:

    - overview
    - section summaries
    - polished bullet points
    - audience
    - warnings
    - source/traceability metadata if available

6. JSON summary output.

   Add or plan JSON summary output as part of Phase 5.

   Prefer implementing minimal JSON summary output during Phase 5 because ReleasePilot integration depends on structured
   outputs.

   JSON summary should include deterministic recommendation data and AI metadata when available.

7. Renderer integration.

   Existing renderers should be able to include AI output if present.

   Custom renderers should be able to decide how to display AI summaries.

   Do not break existing deterministic rendering.

8. Failure and fallback behavior.

   If AI is disabled, deterministic generation must continue normally.

   If AI fails and AI is optional, deterministic output should still be generated with a warning.

   If AI is configured as required, failure should be explicit and documented.

9. ReleasePilot readiness.

   Do not implement ReleasePilot behavior.

   However, design outputs so ReleasePilot can consume them later.

   ReleaseScribe should plan or expose:

    - PHP API
    - JSON summary
    - Markdown artifacts
    - SemVer recommendation result
    - AI summary metadata
    - traceability metadata

10. Configuration.

    Add or document AI configuration using the project’s current configuration approach.

    Suggested config fields:

    - enabled
    - provider
    - model
    - api key environment variable name
    - prompt template
    - summary style
    - default audience
    - mode
    - max commits or token-safety limits
    - review required
    - traceability enabled
    - fallback behavior

11. Documentation.

    Update README and relevant docs to explain:

    - AI is optional.
    - AI enhances release communication.
    - deterministic parsing remains authoritative.
    - SemVer recommendation remains deterministic.
    - current release / “What’s new” mode is the AI default.
    - complete-history AI is opt-in.
    - JSON summary output is supported or planned.
    - ReleaseScribe does not implement ReleasePilot workflow commands.
    - ReleaseScribe is intended to expose outputs ReleasePilot can consume later.

Testing requirements:

Add or update tests for:

- AI input generation from structured data
- default current release / “What’s new” mode
- complete history not used accidentally
- AI disabled behavior
- AI optional failure fallback
- AI required failure behavior if implemented
- JSON summary output if implemented
- renderer behavior when AI output exists
- audience-aware prompt/input behavior if implemented
- no real external AI calls
- no ReleasePilot workflow behavior introduced

Run relevant project tooling:

- Composer validation, if available
- PHPUnit
- PHPStan/static analysis, if configured
- linting/coding standards checks, if configured

If a tool cannot be run, clearly document why.

Acceptance criteria:

- Phase 5 AI-assisted release communication is implemented or advanced according to the existing architecture.
- AI uses structured deterministic data as input.
- AI defaults to current release / “What’s new” mode.
- AI is optional.
- AI output is reviewable and grounded in commit data.
- JSON summary output is implemented or clearly planned.
- Deterministic parsing and SemVer recommendation remain authoritative.
- ReleaseScribe does not depend on ReleasePilot.
- ReleasePilot-compatible outputs are implemented or clearly planned.
- README and docs are consistent.
- Tests are added or updated.
- Relevant tooling has been run or inability to run it has been documented.

END PROMPT
