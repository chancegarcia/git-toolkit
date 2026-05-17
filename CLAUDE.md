# ReleaseScribe — AI Config

## Repository Identity

This is the **ReleaseScribe** product repository.

- **Package:** `chancegarcia/release-scribe`
- **Role:** Standalone release communication tool — changelogs, "What's new?", and SemVer recommendations.
- **Coordination repository:** `release-tools` (separate repo, local workspace only)

## Product Boundaries

- ReleaseScribe is **standalone**. It must not depend on ReleasePilot.
- ReleasePilot may consume ReleaseScribe, not the other way around.
- Guided release workflow orchestration (tagging, pushing, CI pipelines) is out of scope — that belongs to ReleasePilot.
- Phase 5 AI features are post-v2. See `docs/roadmap.md`.

## Repository Boundary Rules

- Treat the root of this directory as the root of the ReleaseScribe repository.
- Do not create links to sibling repositories (`../release-pilot/`, `../release-tools/`) for end-users, CI, or automation.
- Cross-product coordination documents live in `release-tools/` — reference them as local workspace paths only.
- Do not modify coordination documents from within this repository.

## Path Guidance

- Use root-relative paths within this repo: `src/`, `docs/`, `bin/`, etc.
- When referencing coordination materials: `release-tools/docs/...` *(local workspace path — not available in CI or for external contributors)*

## PHP Coding Standards

- **Base standard:** PSR-12
- **Imports:** Remove unused `use` statements; prefer imported class names over fully qualified class names (FQCN)
- **Arrays:** Short syntax `[]` — not `array()`
- **PHP version:** Respect the version in `composer.json`; default to PHP 8.4 if unconfigured; do not use features from higher versions
- **Enforcement:** `slevomat/coding-standard` removes unused imports and unnecessary FQCN

Authoritative reference: `release-tools/docs/ai-guidelines/php-coding-standards.md` *(local workspace path)*

- **Check:** `composer cs:check`
- **Fix:** `composer cs:fix`
- **Run all QA:** `composer qa` (linting, coding standards, static analysis, tests)

## Validation Commands

```bash
composer validate
vendor/bin/phpunit
vendor/bin/phpstan
composer qa
```

Minor line-length warnings from `composer qa` are non-blocking.

## Other Rules

- **No automatic prompt execution.** Do not run extracted or generated prompts automatically. Stop and present for human review first.
- Git submodules are intentionally NOT used.
