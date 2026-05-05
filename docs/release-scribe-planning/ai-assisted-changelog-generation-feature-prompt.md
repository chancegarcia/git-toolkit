## Phase 5 — AI-Assisted Release Communication

START PROMPT

You are working in the PHP project that is being rebranded as the standalone release communication product.

This project is no longer planned to become the full ReleasePilot workflow tool. Its scope is ReleaseScribe:

- “What’s new” document generation
- changelog generation
- release-note generation
- Conventional Commit parsing
- renderer extensibility
- SemVer/release impact recommendation
- AI-assisted release communication

ReleasePilot is the guided release workflow tool in the ReleaseScribe / ReleasePilot product split. ReleasePilot will consume or integrate ReleaseScribe output later.

Goal: implement and document AI-assisted release communication for the standalone “What’s new” / changelog generation
product.

This is Phase 5 of the ReleaseScribe roadmap. It should happen after:

- Conventional Commit changelog generation
- Changelog generation modes
- Renderer extensibility
- Release recommendation / SemVer impact analysis

Do not implement ReleasePilot workflow commands in this phase.

Do not implement full release orchestration in this phase.

Do not implement release preparation, release tagging, release publishing, release commits, branch policy enforcement,
release manifests owned by ReleasePilot, or CI release gates in this phase unless specifically scoped as ReleaseScribe-compatible output artifacts.

Product split guidance:

- ReleaseScribe is the standalone release communication tool in the ReleaseScribe / ReleasePilot product split.
- ReleasePilot is the separate guided release workflow product.
- ReleaseScribe may be designed so ReleasePilot can consume it later.
- ReleaseScribe must remain useful without ReleasePilot.
- ReleaseScribe must not depend on ReleasePilot.
- ReleaseScribe may expose library APIs, structured outputs, and artifacts that make ReleasePilot integration easier.
- ReleasePilot-specific workflow decisions must remain outside ReleaseScribe.

AI feature principles:

- AI should enhance release communication quality, not replace deterministic parsing.
- Conventional Commit parsing, filtering, grouping, and SemVer impact analysis should happen before AI is used.
- AI should operate on focused, structured data.
- The default AI mode should use current release / “What’s new?” data, not complete history.
- Complete-history AI generation should be explicitly requested.
- AI output should be reviewable and predictable enough for release workflows.
- AI should not be treated as the authority for SemVer impact.
- Deterministic release recommendation remains the source of truth for release impact.
- AI may explain or summarize release impact, but it must not invent or silently change the recommendation.
- Prefer factories, dependency injection, and lazy initialization to avoid unnecessary object construction and improve
  performance.

Core requirements:

1. Use deterministic changelog/release communication data as AI input.

    - Do not send raw full git history by default.
    - Send grouped/filtered Conventional Commit data.
    - Include useful context such as:
        - generation mode
        - release recommendation
        - commit sections
        - breaking changes
        - selected commit types
        - target version or tag if available
        - output audience if configured
        - project name or package name if available
    - Keep data structured so AI output can be traced back to source changes.

2. Keep AI input small by default.

    - Default to current release / “What’s new?” mode.
    - Avoid complete history unless explicitly configured.
    - Document that this reduces cost, latency, and noisy summaries.
    - Include token/cost safety controls where practical.
    - Support limits such as max commits, max body length, max section size, or summary-only mode if needed.

3. Generate human-friendly release communication.

   AI may produce:

    - release overview
    - “What’s new?” summary
    - section summaries
    - polished bullet points
    - customer-facing release notes
    - management/stakeholder summaries
    - developer-friendly technical summaries
    - support-team notes if configured

   AI should not silently invent changes.

   AI output should be grounded in the parsed commit data.

4. Support audience-aware output.

   Plan for configurable audiences such as:

    - developer
    - customer
    - marketing
    - management
    - support
    - internal

   The default audience should remain practical and broadly useful.

   Recommended default:

    - “What’s new?” / general release summary

   Audience-specific output should be optional and configurable.

5. Support review/edit workflows.

    - Document and/or implement a workflow where generated AI content can be reviewed before final release usage.
    - Avoid making AI output automatically authoritative without user review.
    - Consider a config option such as review_required.
    - Consider outputting drafts to a separate file or section if useful.
    - Consider marking AI-generated output clearly when configured.

6. Support AI traceability.

   Where practical, AI-assisted output should be traceable back to deterministic source data.

   Possible traceability features:

    - include source commit references in generated metadata
    - include section-level source mappings
    - include “generated from” data in JSON output
    - include optional hidden comments in Markdown if configured
    - include an AI summary metadata object for library consumers

   This is especially important because ReleasePilot may later use these outputs in release workflows.

7. Configuration planning.

   AI configuration should fit into the same future PHP config shape.

   Include placeholders only for sensitive provider settings.

   Do not commit real API keys or sensitive values.

   Suggested config areas:

    - enabled
    - provider
    - model
    - api key environment variable name
    - prompt template
    - summary style
    - default audience
    - mode, defaulting to current release / “What’s new?”
    - max commits or token-safety limits
    - review-required setting
    - traceability setting
    - output files/artifacts
    - fallback behavior when AI fails

8. Renderer integration.

    - AI summaries should be able to appear alongside deterministic changelog sections.
    - The default renderer should support a simple AI summary section if AI is enabled.
    - Custom renderers should be able to decide how AI summaries are displayed.
    - Renderers should receive enough structured data to display:
        - deterministic sections
        - AI overview
        - audience-specific summaries
        - warnings
        - traceability metadata if configured

9. Structured output and ReleasePilot readiness.

   ReleaseScribe should be ready to integrate with ReleasePilot later.

   Do not implement ReleasePilot workflows here, but ensure ReleaseScribe can expose useful outputs.

   Consider supporting:

    - Markdown output for humans
    - JSON output for tools
    - structured PHP result objects for library usage
    - release communication artifacts that can be consumed by ReleasePilot
    - deterministic recommendation data alongside AI summaries

   ReleaseScribe should be usable in CI without ReleasePilot.

   ReleaseScribe should also be usable as a dependency by ReleasePilot.

10. Failure and fallback behavior.

    - If AI is disabled, deterministic generation must continue to work.
    - If AI fails and AI is optional, deterministic output should still be generated with a warning.
    - If AI is configured as required, failure should be explicit and documented.
    - Avoid external network calls in tests.
    - Use fake, mock, or stub AI clients for tests.

11. Documentation requirements.

    Update documentation to reflect the product split:

    - ReleaseScribe is the standalone “What’s new” / changelog / release communication generator.
    - ReleasePilot owns guided release workflow features.
    - ReleaseScribe does not implement release workflow commands.
    - ReleaseScribe may expose outputs that ReleasePilot can consume later.
    - AI enhances release communication, not release authority.
    - Deterministic Conventional Commit parsing and SemVer recommendation remain the source of truth.
    - AI uses current release / “What’s new?” mode by default.
    - Complete-history AI generation is opt-in.
    - AI output should be reviewable and grounded in commit data.
    - ReleaseScribe remains useful without AI.

    Update README when behavior, configuration, usage, commands, output, or extension points change.

    Ignore docs/tmp/ and docs/temp.md for documentation updates unless the user explicitly requests changes there.

Testing/tooling requirements:

- Add or update tests for:
    - AI input generation from structured changelog/release communication data
    - current release / “What’s new?” mode used by default
    - complete history not used accidentally
    - audience-aware prompt/input generation if implemented
    - renderer behavior when AI summary exists
    - structured output containing deterministic data and AI summary if implemented
    - traceability metadata if implemented
    - fallback behavior when AI provider/client fails
    - behavior when AI is disabled
    - behavior when AI is required and fails, if supported
    - no ReleasePilot workflow behavior is introduced into ReleaseScribe

- Avoid tests that require real external AI calls.
- Use fakes, mocks, or stubs for AI clients.
- Run relevant project tooling:
    - Composer validation if available
    - PHPUnit
    - PHPStan if configured
    - Any configured linting/static analysis commands

If a tool cannot be run, clearly document why.

Acceptance criteria:

- AI-assisted release communication uses structured, filtered data.
- Current release / “What’s new?” mode is the AI default.
- Complete-history AI generation is opt-in.
- AI output is documented as reviewable and grounded in commit data.
- AI does not replace deterministic parsing or SemVer recommendation.
- ReleaseScribe remains useful without AI.
- ReleaseScribe does not depend on ReleasePilot.
- ReleaseScribe exposes or plans stable outputs suitable for future ReleasePilot integration.
- No real secrets are committed.
- README and docs are consistent.
- No ReleasePilot workflow commands are implemented in this phase.
- Documentation clearly reflects the product split.

END PROMPT
