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

`config/chancegarcia_git_toolkit.php`

A `.dist` file provides an example of configuration options.

The following values are configurable:

- `project_root`: the directory where the repository resides
- `project_name`: The main header value
- `filename`: name of the markdown file to write out.
- `output_directory`: path to where the markdown file should be written

## ChangeLog Command Usage

```bash
./vendor/bin/toolkit toolkit:changelog "I am not a cat."
```

The changelog file will produce a **markdown** document with a main header. Tag names are the subheaders and the commits
between the tags are printed after the subheader.

### Prepare a new or initial release tag/number

If no tags are present, the subheader will be the commit id.

In order to "create" a new tag, use the `--new-tag=<NEWTAG>` to set the new header and write all recent commits since
the previous tag (if there is one) into the changelog.

```bash
./vendor/bin/toolkit toolkit:changelog "We Love Kittens" --new-tag="1.0.0"
```

## Development

Available composer scripts for local development:

- `composer qa`: Run all quality checks (lint, cs, stan, test)
- `composer test`: Run PHPUnit tests
- `composer test:coverage`: Run PHPUnit tests with HTML coverage report
- `composer lint`: Run parallel-lint
- `composer cs`: Check coding standards (PSR-12)
- `composer cs:fix`: Fix coding standards automatically
- `composer stan`: Run static analysis (PHPStan)
