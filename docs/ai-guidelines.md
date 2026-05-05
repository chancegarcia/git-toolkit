# AI and Junie Operating Guidelines - ReleaseScribe

This document provides identity and boundary rules for AI tools (like Junie) operating within the ReleaseScribe repository.

## Repository Identity

- **Current Repository:** ReleaseScribe
- **Role:** Standalone release communication generator (Changelogs, "What's new?", AI summaries).
- **Identity:** `chancegarcia/release-scribe`
- **Boundary:** This repository is independent. It must not depend on ReleasePilot.

## Boundary Rules

- **Root Context:** Treat the `release-scribe/` directory as the absolute root of this repository.
- **Inaccessibility:** Anything outside this root is inaccessible to users, CI, and automation.
- **No Cross-Repo Links:** Do not use local relative links (e.g., `../release-pilot/`) in documentation. 
- **Coordination Repository Paths:** When referring to the coordination repository (`release-tools`) from this repository, use workspace-style paths like `release-tools/docs/...`. 
- **Workspace Context:** Clearly state that `release-tools/...` paths are separate coordination repository paths available only in local multi-repository workspaces.
- **Coordination Docs:** Cross-product coordination docs live in the separate `release-tools` repository.
- **Tooling Exception:** AI/dev tools may assume local access to sibling repositories (`release-pilot`, `release-tools`) ONLY when the task explicitly states it is a multi-repository workspace task.

## Product Rules

- **ReleaseScribe** owns release communication.
- **ReleasePilot** is a separate repository that handles workflow orchestration.
- ReleaseScribe must remain standalone and useful without ReleasePilot.
