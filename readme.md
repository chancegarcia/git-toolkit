# Git Toolkit

![CI](https://github.com/chancegarcia/git-toolkit/actions/workflows/ci.yml/badge.svg)
[![Latest Stable Version](https://badgen.net/packagist/v/chancegarcia/git-toolkit)](//packagist.org/packages/chancegarcia/git-toolkit) ![GitHub tag (latest SemVer)](https://img.shields.io/github/v/tag/chancegarcia/git-toolkit) [![Total Downloads](https://poser.pugx.org/chancegarcia/git-toolkit/downloads)](//packagist.org/packages/chancegarcia/git-toolkit) [![Latest Unstable Version](https://poser.pugx.org/chancegarcia/git-toolkit/v/unstable)](//packagist.org/packages/chancegarcia/git-toolkit) [![License](https://poser.pugx.org/chancegarcia/git-toolkit/license)](//packagist.org/packages/chancegarcia/git-toolkit) [![PHP](https://badgen.net/packagist/php/chancegarcia/git-toolkit)](//php.net)

---

This toolkit creates a `changelog.md` for a project using the git repository tags and the git commit history.

## Requirements

- PHP >= 8.4
- Git

## Installation

```bash
composer require --dev chancegarcia/git-toolkit
```

## General Usage

Unless specified in a config file, the repository found in the current working directory will be used.

## Configuration (optional)

The toolkit supports Symfony-style environment variable loading and an optional PHP config file.

### Environment variables

You can use `.env` files to configure the toolkit. The following files are supported and loaded in this order (later
files override earlier ones):

1. `.env` - Default values
2. `.env.local` - Local overrides (ignored by git, except for `test` environment)
3. `.env.{APP_ENV}` - Environment-specific defaults (e.g., `.env.dev`, `.env.prod`, `.env.test`)
4. `.env.{APP_ENV}.local` - Environment-specific local overrides

**Note:** `.env.local` is NOT loaded when `APP_ENV=test` to ensure test reproducibility.

The following environment variables are supported:

- `APP_ENV`: The active environment (`dev`, `test`, `prod`). Defaults to `dev`.
- `PROJECT_ROOT`: the directory where the repository resides.
- `PROJECT_NAME`: The main header value.
- `CHANGELOG_USE_CONVENTIONAL_COMMITS`: Whether to use Conventional Commits parsing (default: `true`).
- `CHANGELOG_INCLUDE_NON_CONVENTIONAL`: Whether to include non-conventional commits in "Other" section when using
  Conventional Commits mode (default: `true`).
- `OUTPUT_FILENAME`: name of the markdown file to write out.
- `OUTPUT_DIRECTORY`: path to where the markdown file should be written.

### PHP Config file

Alternatively, you can use a PHP config file in `config/chancegarcia_git_toolkit.php`. Values in the PHP config file
will override environment variables if present.

The following keys are supported in the config array:

- `project_root`
- `project_name`
- `filename`
- `output_directory`

Example `.env` file:

```dotenv
PROJECT_NAME="My Project"
OUTPUT_FILENAME="CHANGELOG.md"
OUTPUT_DIRECTORY="./"
```

## ChangeLog Command Usage

The `toolkit:changelog` command generates a markdown document with a main header.

By default, it uses **Conventional Commits** parsing to group commits into sections like "Features", "Bug Fixes", etc.
Breaking changes are also automatically detected and highlighted.

### Conventional Commits (Default)

The following commit types are included by default:

- `feat` → Features
- `fix` → Bug Fixes
- `perf` → Performance Improvements
- `refactor` → Refactoring
- `docs` → Documentation
- `security` → Security
- `deprecated` → Deprecations

Non-conventional commits are included by default under the "Other" section. You can disable this by setting
`CHANGELOG_INCLUDE_NON_CONVENTIONAL=false`.

Breaking changes are detected if the commit message contains `!` after the type/scope (e.g., `feat!: ...`) or a
`BREAKING CHANGE:` footer.

### Legacy Mode

If you prefer the old behavior (listing all raw commit messages under each tag), you can disable Conventional Commits
via the environment variable:

```dotenv
CHANGELOG_USE_CONVENTIONAL_COMMITS=false
```

### Options

- `--new-tag=<tag>`: Adds a changelog section for an upcoming release and uses the provided value as that section
  heading.
- `--previous-tag=<tag>`: When used with `--new-tag`, explicitly compares the upcoming release against this previous tag
  instead of auto-selecting the latest known tag. Requires `--new-tag`.
- `--output-dir=<path>`: Directory where the changelog file should be written.
- `--filename=<name>`: Name of the changelog file.

### Examples

#### Prepare a new release heading

Use `--new-tag` to set the heading for unreleased commits.

```bash
./vendor/bin/toolkit toolkit:changelog --new-tag="1.0.0"
```

#### Explicit comparison range

Use `--previous-tag` to specify exactly which tag to compare against for the upcoming release. This is useful when you
want to bypass auto-selection of the latest tag.

```bash
./vendor/bin/toolkit toolkit:changelog --new-tag="2.0.0" --previous-tag="1.9.0"
```

This generates a `2.0.0` section containing commits from `1.9.0..HEAD`.

## Development

For planned future direction and the development roadmap, see [docs/roadmap.md](docs/roadmap.md).

Available composer scripts for local development:

- `composer qa`: Run all quality checks (lint, cs, stan, test)
- `composer test`: Run PHPUnit tests
- `composer test:coverage`: Run PHPUnit tests with HTML coverage report
- `composer lint`: Run parallel-lint
- `composer cs`: Check coding standards (PSR-12)
- `composer cs:fix`: Fix coding standards automatically
- `composer stan`: Run static analysis (PHPStan)
