<?php

namespace Chance\ReleaseScribe\Test\Integration;

use Chance\ReleaseScribe\GitInformation;
use Chance\ReleaseScribe\Service\ChangeLogService;
use Chance\ReleaseScribe\Service\GitRepositoryFactory;
use Chance\ReleaseScribe\Test\GitTestHelper;
use PHPUnit\Framework\TestCase;
use SplFileObject;

class ChangeLogIntegrationTest extends TestCase
{
    private GitTestHelper $helper;
    private string $tmpRepoPath;

    public function testGenerateChangeLogWithRealGitRepo(): void
    {
        $_ENV['CHANGELOG_USE_CONVENTIONAL_COMMITS'] = 'false';
        try {
            $repo = $this->helper->initRepo();

            $this->helper->createFile('file1.txt', 'content1');
            $this->helper->commit($repo, 'initial commit');
            $this->helper->tag($repo, 'v1.0.0');

            $this->helper->createFile('file2.txt', 'content2');
            $this->helper->commit($repo, "second commit\n\nWith some body");
            $this->helper->tag($repo, 'v1.1.0');

            $this->helper->createFile('file3.txt', 'content3');
            $this->helper->commit($repo, 'third commit (unreleased)');

            $factory = new GitRepositoryFactory($this->tmpRepoPath);
            $gitInfo = new GitInformation($factory);
            $service = new ChangeLogService($gitInfo);
            $service->setMainHeaderName('Test Project');
            $service->setFullHistory(true);

            $outputPath = $this->tmpRepoPath . '/CHANGELOG.md';
            $file = new SplFileObject($outputPath, 'wb+');

            $service->writeChangeLog($file, 'v1.2.0');

            $content = file_get_contents($outputPath);

            $this->assertStringContainsString('# Test Project', $content);
            $this->assertStringContainsString('## v1.2.0', $content);
            $this->assertStringContainsString('third commit', $content);
            $this->assertStringContainsString('## v1.1.0', $content);
            $this->assertStringContainsString('second commit', $content);
            $this->assertStringContainsString('## v1.0.0', $content);
            $this->assertStringContainsString('initial commit', $content);
        } finally {
            unset($_ENV['CHANGELOG_USE_CONVENTIONAL_COMMITS']);
        }
    }

    public function testEscapingMarkdownInCommits(): void
    {
        $_ENV['CHANGELOG_USE_CONVENTIONAL_COMMITS'] = 'false';
        try {
            $repo = $this->helper->initRepo();
            $this->helper->createFile('file1.txt');
            $this->helper->commit($repo, 'Commit with *star* and [brackets]');
            $this->helper->tag($repo, 'v1.0.0');

            $factory = new GitRepositoryFactory($this->tmpRepoPath);
            $gitInfo = new GitInformation($factory);
            $service = new ChangeLogService($gitInfo);

            $outputPath = $this->tmpRepoPath . '/CHANGELOG.md';
            $file = new SplFileObject($outputPath, 'wb+');

            $service->writeChangeLog($file);

            $content = file_get_contents($outputPath);
            $this->assertStringContainsString('Commit with \*star\* and \[brackets\]', $content);
        } finally {
            unset($_ENV['CHANGELOG_USE_CONVENTIONAL_COMMITS']);
        }
    }

    public function testGitErrorHandlingNotARepo(): void
    {
        $notARepoPath = sys_get_temp_dir() . '/not-a-repo-' . uniqid();
        mkdir($notARepoPath);

        $factory = new GitRepositoryFactory($notARepoPath);
        $gitInfo = new GitInformation($factory);
        $service = new ChangeLogService($gitInfo);

        $this->expectException(\CzProject\GitPhp\GitException::class);
        $service->getGitInformation()->getCurrentCommit();
    }

    public function testGenerateChangeLogWhatsNewMode(): void
    {
        $_ENV['CHANGELOG_USE_CONVENTIONAL_COMMITS'] = 'false';
        try {
            $repo = $this->helper->initRepo();

            $this->helper->createFile('file1.txt', 'content1');
            $this->helper->commit($repo, 'initial commit');
            $this->helper->tag($repo, 'v1.0.0');

            $this->helper->createFile('file2.txt', 'content2');
            $this->helper->commit($repo, 'second commit');
            $this->helper->tag($repo, 'v1.1.0');

            $this->helper->createFile('file3.txt', 'content3');
            $this->helper->commit($repo, 'unreleased commit');

            $factory = new GitRepositoryFactory($this->tmpRepoPath);
            $gitInfo = new GitInformation($factory);
            $service = new ChangeLogService($gitInfo);
            $service->setFullHistory(false); // whats-new mode

            $outputPath = $this->tmpRepoPath . '/CHANGELOG.md';
            $file = new SplFileObject($outputPath, 'wb+');

            $service->writeChangeLog($file, 'v1.2.0');

            $content = file_get_contents($outputPath);

            $this->assertStringContainsString('## v1.2.0', $content);
            $this->assertStringContainsString('unreleased commit', $content);
            $this->assertStringNotContainsString('## v1.1.0', $content);
            $this->assertStringNotContainsString('second commit', $content);
            $this->assertStringNotContainsString('## v1.0.0', $content);
            $this->assertStringNotContainsString('initial commit', $content);
        } finally {
            unset($_ENV['CHANGELOG_USE_CONVENTIONAL_COMMITS']);
        }
    }

    public function testGenerateChangeLogFullMode(): void
    {
        $_ENV['CHANGELOG_USE_CONVENTIONAL_COMMITS'] = 'false';
        try {
            $repo = $this->helper->initRepo();

            $this->helper->createFile('file1.txt', 'content1');
            $this->helper->commit($repo, 'initial commit');
            $this->helper->tag($repo, 'v1.0.0');

            $this->helper->createFile('file2.txt', 'content2');
            $this->helper->commit($repo, 'second commit');
            $this->helper->tag($repo, 'v1.1.0');

            $factory = new GitRepositoryFactory($this->tmpRepoPath);
            $gitInfo = new GitInformation($factory);
            $service = new ChangeLogService($gitInfo);
            $service->setFullHistory(true); // full mode

            $outputPath = $this->tmpRepoPath . '/CHANGELOG.md';
            $file = new SplFileObject($outputPath, 'wb+');

            $service->writeChangeLog($file);

            $content = file_get_contents($outputPath);

            $this->assertStringContainsString('## v1.1.0', $content);
            $this->assertStringContainsString('second commit', $content);
            $this->assertStringContainsString('## v1.0.0', $content);
            $this->assertStringContainsString('initial commit', $content);
        } finally {
            unset($_ENV['CHANGELOG_USE_CONVENTIONAL_COMMITS']);
        }
    }

    protected function setUp(): void
    {
        $this->tmpRepoPath = sys_get_temp_dir() . '/release-scribe-test-' . uniqid('', true);
        $this->helper = new GitTestHelper($this->tmpRepoPath);
    }

    protected function tearDown(): void
    {
        $this->helper->cleanUp();
    }
}
