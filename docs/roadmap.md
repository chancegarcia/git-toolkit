# Project Roadmap

This document outlines the planned future direction for the Git Toolkit project.

> [!NOTE]
> This roadmap documents **planned** and **proposed** future work. Features described in later phases may not be
> implemented yet. Each implementation phase may further refine this documentation.

## Project Direction and Rebranding

The project may be rebranded into a new GitHub repository and Composer package. This is being considered because the new
direction is substantially different from the original project, historical adoption has been low, and the planned
feature set justifies a new package identity.

## Guiding Principles

The project's evolution is guided by the following principles:

- **Conventional Commits by default**: Standardize on Conventional Commits for parsing and generation.
- **SemVer compatibility**: Ensure generated recommendations and releases align with Semantic Versioning.
- **"What’s new?" by default**: Focus on generating the current release notes by default rather than the entire history.
- **Complete history on request**: Full history generation remains available but must be explicitly requested.
- **Changelog initialization**: Support for projects that already have tags but lack a changelog file.
- **Highly configurable**: Commit types, labels, section order, and rendering should be user-configurable.
- **Symfony-bundle-friendly**: Configuration shape should align with standard Symfony bundle patterns.
- **Runtime overrides**: Support for overriding configuration via CLI options.
- **AI assistance**: Introduce AI enhancements after deterministic behavior is stable and reliable.
- **Release management**: Provide simple release commands as the final evolution of the tool.
- **Legacy support**: Maintain opt-in support for non-conventional commit repositories.

---

## Planned Phases

The project will be developed in the following order:

### Phase 1: Conventional Commit Changelog Generation (Completed)

Conventional Commits are now the default input model for changelog generation.

**Implemented Behavior:**

- Commits are parsed by type, optional scope, subject, and breaking-change metadata.
- Breaking changes are detected from `!` in the type/scope and `BREAKING CHANGE:`/`BREAKING-CHANGE:` style footers.
- Changelog entries are grouped by commit type.
- Legacy (non-conventional) generation remains available as an opt-in behavior.
- Configuration via environment variable: `CHANGELOG_USE_CONVENTIONAL_COMMITS`.

**Default Included Types:**

- `feat` &rarr; Features
- `fix` &rarr; Bug Fixes
- `perf` &rarr; Performance Improvements
- `refactor` &rarr; Refactoring
- `docs` &rarr; Documentation
- `security` &rarr; Security
- `deprecated` &rarr; Deprecations

**Excluded by Default:**

- `test`, `build`, `ci`, `chore`, `style`

### Phase 2: Changelog Generation Modes (Completed)

The tool supports several distinct modes of operation, with "What's new?" as the default.

**Modes:**

- **Current Release / "What’s new?" (Default)**: `toolkit:changelog`. Replaces the changelog file with only the
  newest/recent changes.
  - Default title: `What's new?`.
- **Initialize Changelog**: `toolkit:init` (alias `toolkit:initialize`). Designed for projects with or without tags that
  lack an existing changelog.
  - If tags exist: Generates a starting changelog from complete history.
  - If no tags exist: Generates an initial release header (defaults to `v1.0.0`).
- **Complete History**: `toolkit:changelog --mode=full`. Regenerates the full changelog history from the beginning of
  the repository or a requested tag range.
  - Replaces the changelog file with regenerated history.
  - Default title: project default title.

**Behavioral Requirements:**

- **Tag Ordering**: Newest-first tag ordering is the default collector behavior.
- **Collector Responsibility**: Sorting and ordering is handled in the collector layer.
- **Custom Ordering**: Users can implement and configure their own collector for alternative ordering strategies.
- **Commit Grouping**: Conventional commit grouping happens within each tag section and is separate from tag ordering.
- **Alternate Output**: Supports generating separate history files via `--mode=full` with `--filename` and/or
  `--output-dir`.

### Phase 3: Rendering Extensibility

Support for custom renderers will be introduced to allow for diverse output requirements.

**Planned Extensions:**

- **Custom Renderers**: Ability to provide custom classes for rendering.
- **Use Cases**:
    - Alternative Markdown layouts.
    - GitHub Release-style notes.
    - Monorepo/package-specific output.
    - Issue/PR linking and comparison URLs.
    - AI-generated summaries alongside deterministic sections.

### Phase 4: Release Recommendation / SemVer Impact Analysis

The tool will be able to analyze commits to recommend the next version bump according to SemVer.

**Proposed Recommendation Logic:**

- **Major**: If any breaking changes are detected.
- **Minor**: If `feat` commits are present (and no breaking changes).
- **Patch**: For `fix`, `perf`, `security`, or `deprecated` changes.
- **No Release**: If only non-release-impacting commits (e.g., `docs`, `refactor`) are present.

*Note: This phase focuses on analysis and recommendation; it does not perform the actual release.*

### Phase 5: AI-Assisted Changelog Generation

AI will be used to enhance the quality of changelogs after the deterministic parsing logic is stable.

**AI Principles:**

- AI enhances quality (e.g., summaries, better wording) but does not replace deterministic parsing.
- Data provided to AI will be structured and filtered.
- "What's new?" mode will be the default for AI generation.
- AI summaries must be reviewable and should never "invent" changes.

### Phase 6: Release Commands

The final phase introduces commands to automate the release process.

**Planned Commands:**

- `release:major`
- `release:minor`
- `release:patch`

**Planned Behavior:**

- Determine current version (initially from `APP_VERSION` or environment).
- Bump version according to the command.
- Generate/update changelog using the "What's new?" mode.
- Use recommendation logic to warn if the requested bump doesn't match the commit history.
- Support for tagging and configurable push behavior.

---

## Configuration Strategy

The project will move toward a centralized PHP configuration strategy. A default configuration file (e.g.,
`config/git_toolkit.php`) will return an array of settings.

### Proposed Configuration Shape

```php
return [
    'changelog' => [
        'file' => 'CHANGELOG.md',
        'header' => '# Changelog',
        'mode' => 'whats_new', // whats_new, complete, initialize, legacy
    ],
    'conventional_commits' => [
        'enabled' => true,
        'legacy_mode' => false,
        'types' => [
            'included' => ['feat', 'fix', 'perf', 'refactor', 'docs', 'security', 'deprecated'],
            'labels' => [
                'feat' => 'Features',
                'fix' => 'Bug Fixes',
                // ...
            ],
            'order' => ['feat', 'fix', 'security', 'deprecated', 'perf', 'refactor', 'docs'],
        ],
    ],
    'renderer' => [
        'default' => 'LegacyRenderer',
        'options' => [],
    ],
    'release_recommendation' => [
        'enabled' => true,
        'type_impact' => [
            'feat' => 'minor',
            'fix' => 'patch',
            // ...
        ],
    ],
    'ai' => [
        'enabled' => false,
        'mode' => 'summary',
    ],
    'release' => [
        'version_env' => 'APP_VERSION',
        'tag_prefix' => 'v',
        'create_tag' => true,
    ],
];
```

### Runtime Overrides

CLI commands will support runtime configuration overrides:

- `--config=/path/to/config.php`: Specify an alternate configuration file.
- `--config-mode=merge|replace`:
    - `merge` (default): Overrides only the specific keys provided in the alternate config.
    - `replace`: Fully replaces the default configuration.

---

## Relationship to Existing Tools

This project draws inspiration from tools like `php-conventional-changelog`, which demonstrate the value of configurable
commit grouping and release workflows. Our goal is to provide a simpler default workflow with a strong focus on "What's
new?" generation and future AI-assisted summaries.
