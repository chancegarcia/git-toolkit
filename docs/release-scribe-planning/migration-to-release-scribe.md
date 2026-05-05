# Migration to ReleaseScribe

This document provides guidance for migrating from the old Git Toolkit project identity to **ReleaseScribe**.

## Package and Namespace Changes

| Feature       | Old Identity                        | New Identity (ReleaseScribe)  |
|---------------|-------------------------------------|-------------------------------|
| Package Name  | `chancegarcia/git-toolkit`          | `chancegarcia/release-scribe` |
| PHP Namespace | `Chance\GitToolkit` (or equivalent) | `Chance\ReleaseScribe`        |
| Binary Name   | `toolkit`                           | `release-scribe`              |

## Command Mapping

The commands have been moved to a more user-friendly, non-namespaced style.

| Old Command                     | New Command                | Description                      |
|---------------------------------|----------------------------|----------------------------------|
| `toolkit:changelog`             | `release-scribe whats-new` | Generate current release notes.  |
| `toolkit:changelog --mode=full` | `release-scribe changelog` | Generate full change history.    |
| `toolkit:init`                  | `release-scribe init`      | Initialize a new changelog.      |
| `toolkit:release:recommend`     | `release-scribe recommend` | Recommend the next version bump. |

## Configuration Changes

- Configuration file: `config/git_toolkit.php` &rarr; `config/release_scribe.php`
- Environment variables: Use `RELEASE_SCRIBE_` prefix where applicable.

## Release Workflow Commands

Note that mutating commands like tagging or publishing are no longer part of ReleaseScribe and will be provided by *
*ReleasePilot**.
