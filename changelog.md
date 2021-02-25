# Chance Git-Toolkit

## 1.0.0

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
- use git repo client instead of our `shell_exec` calls; repo client has it's own tests so we don't have to concern
  ourselves with testing `shell_exec`

refactor: clearer object and method names

refactor: use `\SPLFileObject` instead of resource

feat: add ability to write latest commits as a tag

- of the `new-tag` option is given, we find all the latest commits by finding all commits between the current commit
  hash and the last release tag known

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

