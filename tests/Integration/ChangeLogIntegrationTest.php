<?php

namespace Chance\GitToolkit\Test\Integration;

use Chance\GitToolkit\GitInformation;
use Chance\GitToolkit\Service\ChangeLogService;
use Chance\GitToolkit\Test\GitTestHelper;
use PHPUnit\Framework\TestCase;
use SplFileObject;

class ChangeLogIntegrationTest extends TestCase
{
    private GitTestHelper $helper;
    private string $tmpRepoPath;

    public function testGenerateChangeLogWithRealGitRepo(): void
    {
        $repo = $this->helper->initRepo();

        $this->helper->createFile('file1.txt', 'content1');
        $this->helper->commit($repo, 'initial commit');
        $this->helper->tag($repo, 'v1.0.0');

        $this->helper->createFile('file2.txt', 'content2');
        $this->helper->commit($repo, "second commit\n\nWith some body");
        $this->helper->tag($repo, 'v1.1.0');

        $this->helper->createFile('file3.txt', 'content3');
        $this->helper->commit($repo, 'third commit (unreleased)');

        $gitInfo = new GitInformation($repo);
        $service = new ChangeLogService($gitInfo);
        $service->setMainHeaderName('Test Project');

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
    }

    public function testEscapingMarkdownInCommits(): void
    {
        $repo = $this->helper->initRepo();
        $this->helper->createFile('file1.txt');
        $this->helper->commit($repo, 'Commit with *star* and [brackets]');
        $this->helper->tag($repo, 'v1.0.0');

        $gitInfo = new GitInformation($repo);
        $service = new ChangeLogService($gitInfo);

        $outputPath = $this->tmpRepoPath . '/CHANGELOG.md';
        $file = new SplFileObject($outputPath, 'wb+');

        $service->writeChangeLog($file);

        $content = file_get_contents($outputPath);
        $this->assertStringContainsString('Commit with \*star\* and \[brackets\]', $content);
    }

    public function testGitErrorHandlingNotARepo(): void
    {
        $notARepoPath = sys_get_temp_dir() . '/not-a-repo-' . uniqid();
        mkdir($notARepoPath);

        $gitRepo = new \CzProject\GitPhp\GitRepository($notARepoPath);
        $gitInfo = new GitInformation($gitRepo);
        $service = new ChangeLogService($gitInfo);

        $this->expectException(\CzProject\GitPhp\GitException::class);
        $service->getGitInformation()->getCurrentCommit();
    }

    protected function setUp(): void
    {
        $this->tmpRepoPath = sys_get_temp_dir() . '/git-toolkit-test-' . uniqid();
        $this->helper = new GitTestHelper($this->tmpRepoPath);
    }

    protected function tearDown(): void
    {
        $this->helper->cleanUp();
    }
}
