# Modernization and AI Changelog Plan

> [!IMPORTANT]
> This document contains historical context for modernization and AI planning. For the current consolidated project
> roadmap, see [docs/roadmap.md](../roadmap.md).

## Current Architecture Summary

The `ChanceGitToolkit` project generates changelogs by:

1. **Git Information Gathering**: Using `czproject/git-php` to fetch tags and commit ranges.
2. **Data Orchestration**: `ChangeLogService` coordinates the retrieval of tags and commits.
3. **Formatting**: `MarkdownFormatter` handles the escaping of special characters for Markdown output.
4. **CLI Interface**: Symfony Console commands (`toolkit:changelog`) provide the user interface to trigger the
   generation.

## Suggested Code Optimizations (Completed)

- **Interface Isolation**: Separated data collection (`CollectorInterface`), rendering (`RendererInterface`), and the
  overall generation process (`GeneratorInterface`).
- **Typed Properties and Return Types**: Updated the codebase to use PHP 8.4 features, including typed properties and
  strict return types.
- **Dependency Injection**: Improved the way services are instantiated in `bin/toolkit` to facilitate better testing and
  future containerization.
- **Error Handling**: Centralized error handling in the command layer (`ChangeLog::renderError`), providing clearer
  feedback to the user when Git operations fail.

## AI Changelog Feature Plan

The future AI feature will be implemented as an alternative `GeneratorInterface` implementation. The following
abstractions have been prepared:

- `AiGenerator`: An implementation of `GeneratorInterface` that uses an AI provider.
- `AiClientInterface`: An abstraction for AI services (OpenAI, Anthropic, etc.).
- `PromptTemplateLoader`: Responsible for loading and rendering the prompt template (currently a stub).

### Next Steps for AI Implementation

### Data for AI

- Repository context (name, description).
- Tag range being processed.
- Structured commit data (subject, body, author, date).
- *Note: Sensitive data (secrets, private paths) will be excluded by default.*

## SDKs and Libraries to Consider

- `openai-php/client`: For direct OpenAI integration.
- `symfony/http-client`: For provider-agnostic API calls.
- `league/commonmark`: If advanced Markdown parsing/transformation is required for AI responses.
- `symfony/yaml` or `symfony/config`: For more robust configuration management as the tool grows.

## Testing Strategy for AI Feature

- **Mock AI Clients**: Use mocks to test the generator logic without making real API calls.
- **Prompt Snapshot Testing**: Verify that the generated prompts contain the expected data and follow the template.
- **Fallback Tests**: Ensure the tool gracefully falls back to the default generator if the AI service is unavailable or
  errors out.
