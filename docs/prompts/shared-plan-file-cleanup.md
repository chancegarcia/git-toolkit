START PROMPT

You are working in the existing PHP project that is being rebranded as ReleaseScribe.

Goal: clean up and organize planning documentation for the ReleaseScribe / ReleasePilot product split.

ReleaseScribe is Product 1.

ReleasePilot is Product 2.

ReleaseScribe remains in this repository.

ReleasePilot is a separate repository, but transitional ReleasePilot planning docs may remain under
`docs/release-pilot/` until they are moved into `release-tools/release-pilot/docs`.

Before making changes:

- Inspect the existing docs directory.
- Inspect:
    - `docs/product-split-and-rebrand-plan.md`
    - `docs/roadmap.md`
    - `docs/ai-assisted-changelog-generation-feature-prompt.md`
    - `docs/release-pilot/`
    - `docs/generic-phpstorm-ai-chat-prompt-delivery-format.md`
- Do not rely on or modify files inside `docs/tmp`.
- Do not rely on or modify `docs/temp.md`.

Documentation goals:

1. Ensure `docs/product-split-and-rebrand-plan.md` contains the current decisions.

   Current decisions:

    - Product 1 is ReleaseScribe.
    - Product 2 is ReleasePilot.
    - Existing repository becomes ReleaseScribe.
    - ReleaseScribe is both library and CLI.
    - ReleaseScribe binary is `release-scribe`.
    - ReleasePilot binary is `release-pilot`.
    - ReleaseScribe commands use end-user CLI style:
        - `release-scribe whats-new`
        - `release-scribe changelog`
        - `release-scribe init`
        - `release-scribe recommend`
    - AI is optional in both products.
    - deterministic parsing and recommendation remain authoritative.
    - AI enhances communication, not release decisions.
    - old binaries, commands, namespaces, and package identity do not need compatibility wrappers.
    - ReleasePilot remains documentation/planning-only until ReleaseScribe is stable.

2. Ensure ReleaseScribe roadmap is scoped to Product 1.

   ReleaseScribe phases:

    - Phase 1: Conventional Commit changelog generation
    - Phase 2: Changelog generation modes
    - Phase 3: Renderer extensibility
    - Phase 4: Release recommendation / SemVer impact analysis
    - Phase 5: AI-assisted release communication

   Phase 6 / release workflow commands should be documented as moved to ReleasePilot.

3. Ensure ReleasePilot planning is separated.

   Keep ReleasePilot docs under:

    - `docs/release-pilot/`

   Make clear these are transitional cross-product planning docs or ReleasePilot reference docs that will be moved into
   the ReleasePilot repository.

4. Ensure Phase 5 prompt remains aligned.

   `docs/ai-assisted-changelog-generation-feature-prompt.md` should describe AI-assisted release communication for
   ReleaseScribe, not ReleasePilot workflow automation.

   It should include JSON summary output as a Phase 5 goal or explicit Phase 5-adjacent deliverable.

5. Ensure release workflow prompt remains aligned.

   ReleasePilot workflow planning should live under:

    - `docs/release-pilot/prompts/release-workflow-commands.md`

   It should describe ReleasePilot as a separate product.

6. Update shared prompting guidance.

   Update `docs/generic-phpstorm-ai-chat-prompt-delivery-format.md` to include guidance about preventing documentation
   drift between ReleaseScribe and ReleasePilot when both projects are open under `release-tools`.

   Include guidance such as:

    - Check both projects’ relevant docs when a task affects product boundaries, shared terminology, integration
      expectations, command names, package names, or roadmap sequencing.
    - Prefer moving product-specific docs to the owning project rather than duplicating them.
    - If shared docs exist in both projects, identify which copy is authoritative or update both intentionally.
    - Do not let ReleasePilot-specific plans drift inside ReleaseScribe docs.

7. Avoid duplication where practical.

   If documentation repeats itself, prefer concise references rather than copying large sections into multiple files.

8. Update README only if the current documentation changes affect user-facing project direction, usage, commands,
   configuration, or product identity.

Implementation guidance:

- Keep the documentation concise.
- Preserve important decisions.
- Remove outdated uncertainty where decisions are now made.
- Do not introduce implementation changes.
- Do not modify source code unless necessary for documentation links.
- Do not modify `docs/tmp`.
- Do not modify `docs/temp.md`.

Run relevant documentation or project checks if available.

If no documentation checks exist, say so clearly.

Acceptance criteria:

- Product split docs reflect current decisions.
- ReleaseScribe docs no longer treat ReleasePilot workflow commands as Phase 6 of the same product.
- ReleasePilot planning docs are clearly separated.
- AI Phase 5 docs align with ReleaseScribe and mention JSON summary output.
- Rebrand docs align with the confirmed ReleaseScribe name.
- Generic prompting guidance includes documentation drift prevention for the two-project workspace.
- No `docs/tmp` or `docs/temp.md` changes are made.
- Any checks run are documented.

END PROMPT
