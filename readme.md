# ReleaseScribe

![CI](https://github.com/chancegarcia/release-scribe/actions/workflows/ci.yml/badge.svg)
[![Latest Stable Version](https://badgen.net/packagist/v/chancegarcia/release-scribe)](//packagist.org/packages/chancegarcia/release-scribe) 
![GitHub tag (latest SemVer)](https://img.shields.io/github/v/tag/chancegarcia/release-scribe) 
[![Total Downloads](https://poser.pugx.org/chancegarcia/release-scribe/downloads)](//packagist.org/packages/chancegarcia/release-scribe) 
[![Latest Unstable Version](https://poser.pugx.org/chancegarcia/release-scribe/v/unstable)](//packagist.org/packages/chancegarcia/release-scribe) 
[![License](https://poser.pugx.org/chancegarcia/release-scribe/license)](//packagist.org/packages/chancegarcia/release-scribe) 
[![PHP](https://badgen.net/packagist/php/chancegarcia/release-scribe)](//php.net)

---

ReleaseScribe is a standalone tool and library for creating release communication ("What's new?", changelogs, release notes) and providing deterministic SemVer release recommendations.

ReleaseScribe owns the generation of release communication and authoritative release recommendation based on commit history. Guided release workflow orchestration is out of scope for ReleaseScribe.

## Requirements

- PHP >= 8.4
- Git

## Installation

```bash
composer require --dev chancegarcia/release-scribe
```

## Usage

The `release-scribe` binary provides four primary commands.

### `init`

Initializes a changelog for your project.

- **With existing tags**: Generates a complete history changelog from all previous tags.
- **Without tags**: Generates an initial release header. Defaults to `v1.0.0`.

**Options:**

- `--initial-version=<version>`: (or `-iv`) Specify the initial version header if no tags exist (default: `v1.0.0`).
- `--output-dir=<path>`: Directory where the changelog file should be written.
- `--filename=<name>`: Name of the changelog file.

**Example:**
```bash
vendor/bin/release-scribe init
```

### `whats-new`

Generates release notes for the **current release** (the "What's new?" section) by default. It replaces or creates the changelog file with these changes.

**Options:**

- `--new-tag=<tag>`: Adds a section for an upcoming release and uses the provided value as that section heading.
- `--header=<text>`: Main file header in output.

**Example:**
```bash
vendor/bin/release-scribe whats-new --new-tag=v2.0.0
```

### `changelog`

Generates the **full changelog history** from git tags.

**Example:**
```bash
vendor/bin/release-scribe changelog
```

### `recommend`

Recommends a SemVer release level (`major`, `minor`, or `patch`) based on the commits since the last tag.

> [!NOTE]
> This command is **analysis-only**. It is deterministic and authoritative based on commit parsing. It does **not** create tags or perform a release.

**How it works:**

- **Major**: Recommended if any breaking changes are detected.
- **Minor**: Recommended if there are feature (`feat`) commits and no breaking changes.
- **Patch**: Recommended if there are fix (`fix`), performance (`perf`), etc., commits.
- **None**: Recommended if only non-release-impacting commits are found.

**Example:**
```bash
vendor/bin/release-scribe recommend
```

## Configuration (optional)

### Environment variables

ReleaseScribe supports `.env` files.

- `PROJECT_ROOT`: the directory where the repository resides.
- `PROJECT_NAME`: The main header value.
- `CHANGELOG_USE_CONVENTIONAL_COMMITS`: Whether to use Conventional Commits parsing (default: `true`).
- `OUTPUT_FILENAME`: name of the Markdown file to write out (default: `changelog.md`).
- `OUTPUT_DIRECTORY`: path to where the Markdown file should be written.

### PHP Config file

Alternatively, use `config/release_scribe.php`.

Example:
```php
return [
    'project_name' => "My Project",
    'filename' => "CHANGELOG.md",
];
```

## Future Roadmap: Phase 5 AI (post-v2)

AI-assisted release communication (e.g., summarizing commits into human-readable prose) is planned for **Phase 5**, after the v2.0.0 release. ReleaseScribe's deterministic parsing and recommendation remain the authoritative foundation for these future AI features. Phase 5 is not part of the v2 release.

## Migration from GitToolkit

ReleaseScribe is the successor to `chancegarcia/git-toolkit`. 

- Package: `chancegarcia/git-toolkit` → `chancegarcia/release-scribe`
- Namespace: `Chance\GitToolkit` → `Chance\ReleaseScribe`
- Binary: `toolkit` → `release-scribe`
- Commands:
    - `toolkit:init` → `init`
    - `toolkit:changelog` (default) → `whats-new`
    - `toolkit:changelog --mode=full` → `changelog`
    - `toolkit:release:recommend` → `recommend`
- Config: `config/chancegarcia_git_toolkit.php` → `config/release_scribe.php`

No backward compatibility wrappers or aliases are provided for the old identity.

## Development

For our planned future direction, see [docs/roadmap.md](docs/roadmap.md).

### Coding Standards

This project follows [PSR-12](https://www.php-fig.org/psr/psr-12/) and workspace-default [PHP Coding Standards](../docs/ai-guidelines/php-coding-standards.md). 

Additionally, we use `slevomat/coding-standard` to enforce:
- Removal of unused `use` statements.
- Cleanup of unnecessary fully qualified class names.

- **Check standards:** `composer cs:check`
- **Fix standards:** `composer cs:fix`
- **Run all QA:** `composer qa` (includes linting, coding standards, static analysis, and tests)

### Other Composer Scripts

- `composer test`: Run PHPUnit tests.
- `composer lint`: Run parallel-lint.
- `composer stan`: Run PHPStan static analysis.
