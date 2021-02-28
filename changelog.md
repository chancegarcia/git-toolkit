# Git-Toolkit

## 1.1.0

feat: allow configurable values `project_root`, `project_name`, `filename`, `output_directory`

- remove setting of these values from the command object
- bin executable can now read a set repo \(project_root\). if none is given, then the current working directory is
  assumed to be the root
- read in a config array and set `project_name` as the service main header
- read in a config array and set `filename` as the service filename
- read in a config array and set `output_directory` as the service file path

ci: add phpcoveralls to after_script

feat: command now has options to set filename and output directory

- passing null to `setChangeLogFileName` or `setChangeLogFilePath` will cause the service to use the default values for
  those properties

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

