START PROMPT

You are working in the existing PHP project that is being rebranded from its current Git Toolkit identity to
ReleaseScribe.

ReleaseScribe is Product 1 of a two-product split.

Product 1:

- Name: ReleaseScribe
- Package target: `chancegarcia/release-scribe`
- Binary target: `release-scribe`
- Namespace target: `Chance\ReleaseScribe`
- Role: standalone “What’s new” / changelog / release communication generator

Product 2:

- Name: ReleasePilot
- Package target: `chancegarcia/release-pilot`
- Binary target: `release-pilot`
- Role: separate guided release workflow tool that will consume or integrate ReleaseScribe later

Goal: rebrand the existing project to ReleaseScribe while preserving current functionality.

Because adoption of the old project identity is low, do not preserve old binaries, old command names, old namespaces, or
old package identity unless there is a very small, low-risk reason discovered during implementation.

Before making changes:

- Inspect the existing project structure.
- Inspect `composer.json`.
- Inspect the current binary file.
- Inspect commands, namespaces, tests, config files, and docs.
- Inspect README and roadmap docs.
- Inspect `docs/product-split-and-rebrand-plan.md`.
- Do not rely on or modify files inside `docs/tmp`.
- Do not rely on or modify `docs/temp.md`.

Core rebrand requirements:

1. Composer package identity.

   Update Composer metadata to reflect ReleaseScribe.

   Target package:

    - `chancegarcia/release-scribe`

   Update description, keywords, homepage, support links, and bin entry as appropriate.

2. Namespace rebrand.

   Update PHP namespace from the old project namespace to:

    - `Chance\ReleaseScribe`

   Update source files, tests, autoload config, and references.

3. Binary rename.

   Rename or add the target binary:

    - `bin/release-scribe`

   The old binary does not need to remain as a wrapper.

4. CLI command style.

   Move to end-user CLI style.

   Target commands:

    - `release-scribe whats-new`
    - `release-scribe changelog`
    - `release-scribe init`
    - `release-scribe recommend`

   Old Symfony-style commands do not need to remain as aliases.

   Map existing behavior carefully:

    - current changelog/current mode behavior should map to `release-scribe whats-new`
    - full changelog behavior should map to `release-scribe changelog`
    - initialization behavior should map to `release-scribe init`
    - release recommendation behavior should map to `release-scribe recommend`

5. Config rename.

   Rename config files and config references where appropriate.

   Prefer names based on `release_scribe`.

   Avoid introducing ReleasePilot config into ReleaseScribe.

6. Documentation.

   Update README and docs to reflect:

    - project name is ReleaseScribe
    - ReleaseScribe is both a library and CLI tool
    - ReleaseScribe owns “What’s new”, changelog, release notes, SemVer recommendation, and AI-assisted release
      communication
    - ReleasePilot is separate and owns guided release workflow
    - AI is optional
    - deterministic parsing and recommendation are authoritative
    - ReleaseScribe can be used standalone
    - ReleasePilot may consume ReleaseScribe outputs later
    - no backward compatibility wrappers/aliases are planned for the old project identity

7. Roadmap update.

   Update roadmap so ReleaseScribe owns Phases 1–5.

   Remove Phase 6 as an implementation phase of ReleaseScribe.

   Document that release workflow commands have been split into ReleasePilot.

8. ReleasePilot planning docs.

   Keep ReleasePilot planning clearly separated.

   If ReleasePilot planning remains in this repo temporarily, keep it under:

    - `docs/release-pilot/`

   Make clear that those docs are transitional or cross-product planning.

9. Tests.

   Update tests for namespace, binary, command names, and docs expectations where applicable.

   Preserve existing behavior.

10. Migration notes.

    Add or update migration documentation.

    Include old-to-new mapping:

    - `chancegarcia/git-toolkit` → `chancegarcia/release-scribe`
    - old namespace → `Chance\ReleaseScribe`
    - old binary → `release-scribe`
    - old changelog command → `release-scribe whats-new` / `release-scribe changelog`
    - old init command → `release-scribe init`
    - old release recommendation command → `release-scribe recommend`

11. External rebrand checklist.

    Add documentation for external rebrand tasks, including:

    - GitHub repository rename
    - Packagist new package registration
    - Packagist old package abandonment pointing to `chancegarcia/release-scribe`
    - badge/link updates
    - webhook/integration checks

Implementation guidance:

- Prefer small, reviewable changes.
- Avoid unrelated refactors.
- Keep changes practical and maintainable.
- Do not introduce ReleasePilot as a dependency.
- Do not implement ReleasePilot workflow behavior.
- Do not remove working features unless replacement behavior exists.
- Update docs consistently with implementation.

Run relevant project tooling:

- Composer validation, if available
- PHPUnit
- PHPStan/static analysis, if configured
- linting/coding standards checks, if configured

If a tool cannot be run, clearly document why.

Acceptance criteria:

- Project identity is updated to ReleaseScribe.
- Composer metadata reflects ReleaseScribe.
- Namespace references are updated.
- Binary target `release-scribe` exists.
- CLI commands align with the chosen end-user style.
- README and docs are consistent.
- Roadmap reflects ReleaseScribe Phases 1–5.
- ReleasePilot is documented as a separate product.
- No unnecessary backward compatibility wrappers are introduced.
- Existing behavior is preserved under the new identity.
- Tests are updated and pass.
- Relevant tooling has been run or inability to run it has been documented.

END PROMPT
