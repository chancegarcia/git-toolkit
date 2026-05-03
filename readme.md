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

Default values for some command arguments and options can be set via a config file in
`config/chancegarcia_git_toolkit.php`.

The following values are configurable:

- `project_root`: the directory where the repository resides
- `project_name`: The main header value
- `filename`: name of the markdown file to write out.
- `output_directory`: path to where the markdown file should be written

## ChangeLog Command Usage

The `toolkit:changelog` command generates a markdown document with a main header. Tag names are the subheaders and the
commits between the tags are listed below them.

```bash
./vendor/bin/toolkit toolkit:changelog "My Project"
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

Available composer scripts for local development:

- `composer qa`: Run all quality checks (lint, cs, stan, test)
- `composer test`: Run PHPUnit tests
- `composer test:coverage`: Run PHPUnit tests with HTML coverage report
- `composer lint`: Run parallel-lint
- `composer cs`: Check coding standards (PSR-12)
- `composer cs:fix`: Fix coding standards automatically
- `composer stan`: Run static analysis (PHPStan)
