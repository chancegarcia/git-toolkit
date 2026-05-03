# Testing and Coverage Review

## Summary of Current Test Files

- `tests/Command/ChangeLogTest.php`: Tests the `toolkit:changelog` command.
- `tests/Command/ReleaseTest.php`: Tests another command (likely `toolkit:release`).
- `tests/Service/ChangeLogServiceTest.php`: Extensive tests for `ChangeLogService`, focusing on file writing and data
  orchestration.
- `tests/GitInformationTest.php`: Tests the wrapper around the Git repository.
- `tests/Formatter/MarkdownFormatterTest.php`: Tests Markdown escaping/formatting.
- `tests/Service/ChangeLogServiceTest.php`: Tests the core service.

## Behaviors Covered

- **Command Execution**: Basic CLI execution, handling of arguments (header) and options (new-tag, output-dir,
  filename).
- **Changelog Generation Logic**:
    - Generating changelog when tags exist.
    - Generating changelog when no tags exist.
    - Handling of "new tag" (unreleased commits).
    - Handling of empty tags.
- **Git Information**: Retrieving tags, first commit, current commit, and commit ranges.
- **Formatting**: Escaping commits for Markdown.
- **Service Configuration**: Getters/setters for file paths, names, and headers.

## Behaviors Missing or Weakly Covered

- **Git Error Handling**: While there are some try-catch blocks in code, tests for various Git failure scenarios (e.g.,
  not a git repo, git binary missing) are sparse.
- **Output Content Validation**: Many tests use mocks to verify that `fwrite` is called a certain number of times, but
  don't strictly validate the *content* being written to the file.
- **Edge Cases in Commit Messages**: Unusual characters, multi-line messages beyond what's in basic tests.
- **Configuration File Loading**: The logic in `bin/toolkit` that loads `config/chancegarcia_git_toolkit.php` is not
  covered by unit/integration tests as it resides in the entry point.
- **Complex Tag Ranges**: More complex versioning schemes (though it uses `--sort=-v:refname`).

## Risk Areas

- **Direct Dependency on `czproject/git-php`**: The `GitInformation` class is tightly coupled to this specific library.
- **File System Side Effects**: Tests currently use `php://memory` via mocks, which is good, but real-world file
  permission issues or path resolution issues might not be caught.
- **Legacy Symfony Console Patterns**: Use of `protected static $defaultName` and other older patterns might break or
  become deprecated in Symfony 7/8.

## Suggested Tests to Add

- **Integration Tests**: A test that runs the command against a real (temporary) Git repository to ensure end-to-end
  functionality.
- **Content-based Assertions**: Verify that the generated Markdown actually looks like expected Markdown.
- **Input Validation Tests**: More thorough testing of CLI input (e.g., invalid paths).
- **AI Feature Seams**: Once refactored, tests for the new collector/renderer interfaces.

## Test Classification

The current tests are primarily **Unit Tests** with heavy use of mocks. They are close to **Integration Tests** in
`ChangeLogServiceTest` because they exercise the interaction between the service and `GitInformation` (often mocked),
but they mostly isolate the logic.

## Recommendations

- **Introduce a Test Repository Fixture**: Use a script to create a temporary Git repository with specific commits and
  tags for integration testing.
- **Shift from "Number of Writes" to "Content" Verification**: Use `vfsStream` or similar if needed, or just better mock
  expectations to verify the actual strings being passed to `fwrite`.
- **Modernize Mocking**: The tests use PHPUnit's built-in mock builder. Consider if any modern features can simplify the
  verbose mock setups.
- **Coverage Tooling**: Configure `php-coveralls` or local HTML reports to identify specific line-by-line gaps in `src`.
