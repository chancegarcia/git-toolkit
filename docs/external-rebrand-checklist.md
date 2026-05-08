# External Rebrand Checklist for ReleaseScribe

This checklist covers tasks that must be performed in external systems to complete the ReleaseScribe rebrand.

## Status Legend

- `AI/local`: Can be completed by local repository edits, validation, or documentation.
- `AI/verifiable`: Can be inspected locally but may still require human confirmation.
- `Human/external`: Must be completed in an external system by a maintainer.
- `Decision`: Requires maintainer decision before completion.

## Pre-flight

Local readiness tasks and decisions.

- [x] Verify internal rebrand is complete and consistent across package metadata, README, docs, CLI naming, config examples, tests, badges, and generated artifacts. `Owner: AI/local`
- [x] Decide whether old names should remain temporarily supported through aliases, deprecation warnings, or documentation-only migration guidance. `Owner: Maintainer decision` - not supporting old names.
- [x] Choose the first public ReleaseScribe version/release strategy. `Owner: Maintainer decision` - doing v2 release to finalize the rebrand then moving on to ai features, etc.
- [x] Decide whether to tag a final old-name release before the external rename. `Owner: Maintainer decision` - we'll tag the current main as final before renaming. current main has some security PRs that are merged. not sure if those need to be applied to develop. probably not since we did updates for release-scribe that also bumped updates on previous gittoolbox depenedencies
- [x] Draft the public migration note or release announcement before changing GitHub or Packagist. `Owner: AI/local`
- [x] Confirm ownership of the existing Packagist package `chancegarcia/git-toolkit`. `Owner: Human/external`
- [x] Confirm the new Packagist package name `chancegarcia/release-scribe` is available or ready to register. `Owner: Human/external`
- [x] Confirm the intended GitHub repository name, description, and homepage URL. `Owner: Human/external`
- [x] Run local validation before external changes: `Owner: AI/local`
  - [x] `composer validate`
  - [x] PHPUnit
  - [x] PHPStan/static analysis, if configured
  - [x] coding standards/linting, if configured (Verified: All functional coding standard issues fixed; minor line-length warnings remain but do not block release. Fixed RuntimeException FQN errors during 2026-05-08 preflight.)
- [x] Review CI, coverage, badges, and external integration references for hardcoded old repository, package, or binary names. `Owner: AI/local`
- [x] Confirm required secrets/tokens/integration access for GitHub Actions, Packagist, Coveralls, and any other services. `Owner: Human/external`
- [x] Confirm external task order and rollback expectations. `Owner: Human/external`
- [x] Add 2.0.0 release entry to `changelog.md`. `Owner: AI/local`
- [x] Review README for rebrand consistency and migration guidance. `Owner: AI/local`

## GitHub

External GitHub tasks.

- [ ] Rename repository from `git-toolkit` to `release-scribe`. `Owner: Human/external`
- [ ] Update repository description: `ReleaseScribe: "What's new", changelog, release notes, and SemVer recommendation tool`. `Owner: Human/external`
- [ ] Update repository website/homepage link if applicable. `Owner: Human/external`
- [x] Check and update GitHub Actions workflows (if they rely on hardcoded repository names). `Owner: AI/local` (Verified: workflows use GITHUB_REPOSITORY or relative paths where possible; badges updated in README)
- [ ] Update branch protection rules (should stay intact but worth verifying). `Owner: Human/external`

## Packagist

External Packagist tasks.

- [ ] Register new package: `chancegarcia/release-scribe`. `Owner: Human/external`
- [ ] Abandon old package `chancegarcia/git-toolkit` and point it to `chancegarcia/release-scribe`. `Owner: Human/external`
- [ ] Verify webhooks/integrations for the new package. `Owner: Human/external`

## CI/CD & Integrations

Mixed local and external integration tasks.

- [x] Update CI configurations (GitHub Actions, etc.) to use the new package name and binary if applicable. `Owner: AI/local`
- [ ] Update code coverage services (e.g., Coveralls) with the new repository name. `Owner: Human/external`
- [ ] Update any external badges/links in other project READMEs. `Owner: Human/external`

## Communication

Announcement and portfolio/update tasks.

- [ ] Publish a release note or announcement about the rebrand. `Owner: Human/external`
- [ ] Update any personal or project portfolio links. `Owner: Human/external`

## Human-only action summary

- Rename GitHub repository.
- Register new Packagist package.
- Abandon old Packagist package and point to new one.
- Update Coveralls project settings/reconnect.
- Verify GitHub Actions secrets and Packagist webhooks.
- Make decisions on versioning and final old-name release.
- Publish the announcement.

## AI/local completion notes

- 2026-05-05: Verified `composer.json`, `readme.md`, and `bin/release-scribe` are updated.
- 2026-05-05: Classified checklist items and assigned ownership.
- 2026-05-05: Ran `composer validate`, `composer qa`, and `phpunit`. PHPStan and PHPUnit passed. PHPCS issues fixed (minor line-length warnings remain).
- 2026-05-05: Added 2.0.0 section to `changelog.md`.
- 2026-05-05: Drafted `docs/rebrand-announcement-draft.md`.
- 2026-05-05: Final local rebrand consistency check completed.
- 2026-05-08: Re-ran preflight after coding standards update. Fixed RuntimeException FQN errors in `Init.php` and `ChangeLog.php`. Verified identity consistency and documentation across `release-scribe/` and `release-pilot/`. All QA tools pass (except minor line-length warnings).
