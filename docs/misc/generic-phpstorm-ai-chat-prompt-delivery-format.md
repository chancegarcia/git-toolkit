Final delivery expectations:

- If the user asks a question:
    - Answer the question directly.
    - Then ask whether they want a Junie prompt created from the answer.
    - Do not create a Junie prompt unless the user explicitly asks for one or the original request clearly asks for
      implementation planning.

- If the user provides a task, statement, implementation request, planning request, or asks for a prompt:
    - Produce a Junie prompt based on the user’s request.
    - The Junie prompt must be written in Markdown.
    - Do not wrap the Junie prompt in code fences.
    - Use literal boundary lines to clearly mark the prompt boundaries:
        - `START PROMPT`
        - `END PROMPT`

Junie prompt requirements:

- When a task affects product boundaries, shared terminology, integration expectations, command names, package names,
  roadmap sequencing, or ReleaseScribe/ReleasePilot responsibilities, relevant documentation should be checked for
  drift.
- ReleaseScribe-specific docs should stay focused on ReleaseScribe.
- ReleasePilot-specific docs should stay in the ReleasePilot project, currently:
    - `release-pilot/docs/`
- Prefer moving product-specific docs to the owning documentation area instead of duplicating content.
- If shared docs or shared context must exist in more than one place later, identify which copy is authoritative or
  update both intentionally.
- Do not allow ReleasePilot-specific plans to drift inside ReleaseScribe-focused docs.
- If a generated or extracted Junie prompt is encountered during an organization task, do **not** execute it
  automatically.
- Before running any extracted or generated prompt, stop and present the prompt for human review and approval.
- This review-before-execution rule applies even when Junie is operating in a more autonomous/brave mode.
- Instruct Junie to inspect the existing project before making changes.
- Instruct Junie to not rely on or modify `docs/tmp` or `docs/temp.md`.
- Instruct Junie to run available project tooling (e.g., Composer validation, PHPUnit, PHPStan, linting) to verify
  changes or document why tooling was not run.

Documentation requirements:

- Instruct Junie to keep all documentation consistent with the implementation.
- Instruct Junie to update the README when behavior, configuration, usage, commands, output, or extension points change.
- Instruct Junie to avoid updating or relying on files inside `docs/tmp`.
- Instruct Junie to avoid updating or relying on `docs/temp.md`.
- The only exception is when the user explicitly requests changes to those ignored documentation locations.

Implementation guidance:

- Prefer factories, dependency injection, and lazy initialization.
- Avoid unnecessary object construction.
- Keep changes practical, maintainable, and consistent with the current architecture.
- Preserve backward compatibility where reasonable.
- Add or update tests for changed behavior.
- Keep generated prompts specific to the current user request and avoid introducing unrelated scope.