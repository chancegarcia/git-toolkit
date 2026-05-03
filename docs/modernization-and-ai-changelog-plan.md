# Modernization and AI Changelog Plan

## Current Architecture Summary

The `ChanceGitToolkit` project generates changelogs by:

1. **Git Information Gathering**: Using `czproject/git-php` to fetch tags and commit ranges.
2. **Data Orchestration**: `ChangeLogService` coordinates the retrieval of tags and commits.
3. **Formatting**: `MarkdownFormatter` handles the escaping of special characters for Markdown output.
4. **CLI Interface**: Symfony Console commands (`toolkit:changelog`) provide the user interface to trigger the
   generation.

## Suggested Code Optimizations

- **Interface Isolation**: We have already introduced `GeneratorInterface`, `CollectorInterface`, and
  `RendererInterface`. This separates data collection, rendering, and the overall generation process.
- **Typed Properties and Return Types**: Updated the codebase to use PHP 8.4 features, including typed properties and
  strict return types, improving reliability and IDE support.
- **Dependency Injection**: Improved the way services are instantiated in `bin/toolkit` to facilitate better testing and
  future containerization if needed.
- **Error Handling**: Centralized error handling in the command layer, providing clearer feedback to the user when Git
  operations fail.

## AI Changelog Feature Plan

The future AI feature will be implemented as an alternative `GeneratorInterface` implementation.

### User Experience

- **Default Behavior**: Remains exactly as it is today (generic Markdown list of commits).
- **Optional AI Mode**: Enabled via flags like `--ai`.
- **Configurable Prompts**: Users can provide their own prompt via `--prompt-file`.

### AI Provider Abstraction

- `AiGenerator`: An implementation of `GeneratorInterface` that uses an AI provider.
- `AiClientInterface`: An abstraction for AI services (OpenAI, Anthropic, etc.).
- `PromptTemplateLoader`: Responsible for loading and rendering the prompt template (e.g., from
  `docs/changelog-prompt.md`).

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
