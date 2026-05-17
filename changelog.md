# ReleaseScribe

## v2.0.0

### Summary

- Complete rebrand from `git-toolkit` to `release-scribe` — new package name, binary, and namespace with no backward-compatibility shims.
- Four new CLI commands: `whats-new`, `changelog`, `recommend`, and `init` replace the old single-command structure.
- Conventional Commits parsing added for deterministic SemVer recommendations and structured changelog generation.
- Renderer architecture overhauled with a `ChangeLogData` model, making custom output formats straightforward.
- PHP 8.4 is now the minimum required version; CI migrated from Travis CI to GitHub Actions.

### Developer Details

#### Identity and Rebrand
- Package renamed from `chancegarcia/git-toolkit` to `chancegarcia/release-scribe`.
- Binary renamed from `git-toolkit` to `release-scribe`.
- All source namespaces migrated from the old root to `Chance\ReleaseScribe`.
- ReleaseScribe is now a standalone tool; guided release workflow orchestration belongs to ReleasePilot (future planned product).

#### New Commands
- `whats-new` — generates release notes for the current or latest release.
- `changelog` — generates a full-history changelog with support for range modes and `--full-history`.
- `recommend` — outputs a deterministic SemVer release level (`major`, `minor`, or `patch`) based on commit history; impact mapping is configurable.
- `init` — initializes a new changelog file for a project.
- All commands wrapped with Symfony Console for consistent argument/option handling and help output.

#### Conventional Commits
- Added `ConventionalCommitsParser` for structured commit parsing.
- `ReleaseRecommender` service derives a SemVer recommendation from parsed commit history.
- Impact mapping between commit types and release levels is configurable.

#### Data Models and Rendering
- New `ChangeLogData` and release data models decouple generation from rendering.
- `LegacyRenderer` and `LegacyGenerator` updated to support both modern `ChangeLogData` and legacy data structures for gradual migration.
- Custom renderers can now be injected for alternative output formats.

#### AI Generator (Experimental)
- `AiGenerator` class and supporting infrastructure added as foundational plumbing for AI-powered release notes. Not yet production-ready; gated for post-v2.0.0 work.

#### Configuration
- `.env` file loading via `symfony/dotenv` for local configuration and credential management.
- `GitRepositoryFactory` introduced for cleaner dependency injection of the Git client.

#### Infrastructure
- PHP minimum bumped to `>=8.4`.
- CI migrated from Travis CI to GitHub Actions; matrix covers multiple PHP versions and OS targets.
- Integration tests added for CLI commands, config loading, and behavior outside Git repositories.
- Improved Git error handling and user-facing error messages in `changelog` and `init` commands.

### Breaking Changes

- **Package renamed**: `chancegarcia/git-toolkit` → `chancegarcia/release-scribe`. No compatibility aliases provided.
- **Binary renamed**: `git-toolkit` → `release-scribe`. Existing scripts invoking `git-toolkit` will break.
- **Namespace changed**: All classes moved to `Chance\ReleaseScribe`. Library consumers must update all `use` statements and fully qualified references.
- **PHP 8.4 required**: Installations running PHP < 8.4 are no longer supported.

### Migration Notes

**Composer install**

```bash
# Before
composer require chancegarcia/git-toolkit

# After
composer require chancegarcia/release-scribe
```

**Binary invocation**

```bash
# Before
./vendor/bin/git-toolkit changelog

# After
./vendor/bin/release-scribe changelog
```

**Namespace updates (library use)**

```php
// Before
use ChanceGarcia\GitToolkit\Service\ChangeLogService;

// After
use Chance\ReleaseScribe\Service\ChangeLogService;
```

## v1.1.2

### Summary

- Dependency maintenance release. No functional changes.

### Developer Details

- `czproject/git-php` bumped from `3.18.2` to `4.0.3` (runtime dependency).
- `guzzlehttp/guzzle` bumped from `7.2.0` to `7.7.x-dev` (dev dependency).

## 1.1.1
docs: final changelog update before release

docs: update changelog for v1.1.1

fix: automatically set trailing slash for file path if it was not set; empty strng is still treated as `./` \(empty string means current working directory\)

fix: make sure vendor autoload file is loaded from current directory \(previously would try to run from `project_root` directory if that config value was set\)

docs: update readme with config instructions and details

refactor: move formatting logic from GitInformation to a formatter class

## 1.1.0
final changelog update

docs: update changelog for v1.1.0

feat: allow configurable values `project_root`, `project_name`, `filename`, `output_directory`

- remove setting of these values from the command object
- bin executable can now read a set repo \(project_root\). if none is given, then the current working directory is assumed to be the root
- read in a config array and set `project_name` as the service main header
- read in a config array and set `filename` as the service filename
- read in a config array and set `output_directory` as the service file path

ci: add phpcoveralls to after_script

feat: command now has options to set filename and output directory

- passing null to `setChangeLogFileName` or `setChangeLogFilePath` will cause the service to use the default values for those properties

style: add more flair to the `readme.md`

ci: fast finish jobs

ci: use our phpunit.xml.dist for testing config

ci: use non-abandoned lint and console highlighter

test: update phpunit config and dist file

style: run code beautify fixer and also manually fix code smells

ci: add more to the jobs and build testing

ci: add new php versions to test against

## 1.0.1
chore: update changelog for v1.0.1

docs: update `readme.md` with build status

build: make sure dependencies are installed?

build: specify os and dist; and php version

build: use simple config

since we copypasta'd ramsey's config, we might be missing something or mis-understanding what config things do...

build: add requirements for travis config

chore: cleanup and organize the `composer.json` file; add more meta information and correct bin definition

## 1.0.0
chore: dogfooding to create our initial release changelog

chore: update lock for testing

docs: create content for readme

chore: complete coverage with test stub

refactor: rename this tool and namespaces to fit with package name

feat: add output feedback to `ChangeLog` command

test: test changelog command

test: test path methods and spl fileobject creation

- remove git info object setter since construction requires setting that value
- add type declaration to `setChangeLogFileName` and `setChangeLogFilePath`

test: test write change log with no tag history and no new tag passed

test: finish information tests

- fix false positives for `getCommits` test
- there's no reason to have a `setGitRepo` method since the constructor requires a repo object

test: add test coverage for blank tag found

test: test for write change log with new tag and no history

test: test for write change log with no new tag and no new history

fix: write tag histor when passing a new tag

- when a new tag name was passed, writing of commit history was being skipped

refactor: inject spl file object into `writeChangeLog`

- test for `writeChangeLog` with new new tag parameter passed and has existing tag history

test: cleanup and add test notes

chore: update `phpunit.xml`

- add dist
- backup migrate `phpunit.xml` config \(as suggested when running tests\)

refactor: move `changelog` command `execute` logic to a service

- update bin file to use wire in service and set service for the `ChangeLog` command
- update help to display default main header name constant value
- start tests for service

feat: test `GitInformation` class; add git repo client dependency

- version script determines current directory
- method `escapeCommitsForMarkdown` is now static
- use git repo client instead of our `shell_exec` calls; repo client has it's own tests so we don't have to concern ourselves with testing `shell_exec`

refactor: clearer object and method names

refactor: use `\SPLFileObject` instead of resource

feat: add ability to write latest commits as a tag

- of the `new-tag` option is given, we find all the latest commits by finding all commits between the current commit hash and the last release tag known

feat: allow customization of the initial project header of the changelog file

fix: handle multiple values for first commit found

feat: introduce ability to configure file name and path

refactor: rename util class

chore: skeleton release command

refactor: cleanup util and changelog command

- make application script into executable
- update composer information

feat: add changelog command

- add gitlog parser
- use parser in changelog command

