# ReleaseScribe Rebrand Announcement

## Summary
`Git Toolkit` is now `ReleaseScribe`.

ReleaseScribe is the same powerful tool for deterministic SemVer recommendations and changelog generation, but with a clearer identity focused on its core mission: owning the "What's new" in your release process.

## Key Changes
- **New Package Name:** `chancegarcia/release-scribe`
- **New Binary Name:** `release-scribe`
- **New Namespace:** `Chance\ReleaseScribe`
- **Config File:** `config/release_scribe.php`

## Migration Guide
To migrate to the new version:

1. Update your `composer.json` or run:
   ```bash
   composer remove chancegarcia/git-toolkit
   composer require --dev chancegarcia/release-scribe
   ```
2. Update any CI/CD scripts to use the new binary name:
   ```bash
   # Old
   vendor/bin/toolkit recommend
   # New
   vendor/bin/release-scribe recommend
   ```
3. Update your config file name from `config/chancegarcia_git_toolkit.php` to `config/release_scribe.php`.

**Note:** No backward compatibility wrappers or aliases are provided for the old `toolkit` binary or namespace. This is a clean break to the new identity.

## Why the change?
The name `Git Toolkit` was too broad. `ReleaseScribe` better reflects the tool's specialized role in the release ecosystem—documenting changes and recommending version bumps based on your commit history.

## Links
- **GitHub:** [https://github.com/chancegarcia/release-scribe](https://github.com/chancegarcia/release-scribe)
- **Packagist:** [https://packagist.org/packages/chancegarcia/release-scribe](https://packagist.org/packages/chancegarcia/release-scribe)
