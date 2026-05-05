# ReleaseScribe Roadmap

This document outlines the planned future direction for **ReleaseScribe**.

> [!NOTE]
> This roadmap documents **planned** and **proposed** future work. Features described in later phases may not be
> implemented yet. Each implementation phase may further refine this documentation.

## Project Direction and Rebranding

The project is being rebranded from Git Toolkit to **ReleaseScribe**.

ReleaseScribe is Product 1 of a two-product split:

1. **ReleaseScribe**: Standalone release communication generator (this repository).
2. **ReleasePilot**: Guided release workflow tool (separate repository).

ReleaseScribe turns technical changes into release-ready communication artifacts for developers, maintainers, release
reviewers, and stakeholders. It is not a full release orchestration tool; workflow orchestration features belong to
ReleasePilot.

## Guiding Principles

The project's evolution is guided by the following principles:

- **Conventional Commits by default**: Standardize on Conventional Commits for parsing and generation.
- **SemVer compatibility**: Ensure generated recommendations align with Semantic Versioning.
- **"What’s new?" by default**: Focus on generating the current release notes by default.
- **Complete history on request**: Full history generation remains available.
- **Highly configurable**: Commit types, labels, section order, and rendering should be user-configurable.
- **AI assistance**: Introduce AI enhancements for communication after deterministic behavior is stable.
- **Output for Integration**: Provide structured outputs (JSON/API) for consumption by tools like ReleasePilot.

---

## Planned Phases

The project will be developed in the following order:

### Phase 1: Conventional Commit Changelog Generation (Completed)

Conventional Commits are now the default input model for changelog generation.

### Phase 2: Changelog Generation Modes (Completed)

The tool supports several distinct modes of operation, with "What's new?" as the default.

- **Current Release / "What’s new?" (Default)**: `release-scribe whats-new`
- **Initialize Changelog**: `release-scribe init`
- **Complete History**: `release-scribe changelog`

### Phase 3: Rendering Extensibility (Completed)

Support for custom renderers has been introduced to allow for diverse output requirements.

### Phase 4: Release Recommendation / SemVer Impact Analysis (Completed)

The tool analyzes commits since the last tag to recommend the next version bump according to SemVer.

- **Command:** `release-scribe recommend`

### Phase 5: AI-Assisted Release Communication (Planned)

AI will be used to enhance the quality of release communication.

**AI Principles:**

- AI enhances quality (summaries, audience-aware wording) but does not replace deterministic parsing.
- Data provided to AI will be structured and filtered.
- "What's new?" mode will be the default for AI generation.
- AI summaries must be reviewable and grounded in commit data.

### Phase 6: Release Workflow Orchestration (Moved)

Release workflow commands (checking, planning, tagging, publishing) have been moved to **ReleasePilot**. ReleaseScribe
will provide the underlying communication and recommendation engine that ReleasePilot consumes.

---

## Configuration Strategy

The project uses a centralized PHP configuration strategy. A default configuration file (e.g.,
`config/release_scribe.php`) will return an array of settings.

### Proposed Configuration Shape

```php
return [
    'changelog' => [
        'file' => 'CHANGELOG.md',
        'header' => '# Changelog',
        'mode' => 'whats_new', // whats_new, complete, initialize
    ],
    // ... conventional_commits, renderer, release_recommendation config
    'ai' => [
        'enabled' => false,
        'provider' => 'openai',
        'model' => 'gpt-4',
        'default_audience' => 'general',
    ],
];
```
