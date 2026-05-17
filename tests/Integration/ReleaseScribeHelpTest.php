<?php

namespace Chance\ReleaseScribe\Test\Integration;

use PHPUnit\Framework\TestCase;

class ReleaseScribeHelpTest extends TestCase
{
    private string $tmpDir;

    public function testHelpDoesNotCrashWithoutGitRepo(): void
    {
        $releaseScribeBinary = __DIR__ . '/../../bin/release-scribe';

        // Run help command in a directory that is not a git repo
        $command = sprintf('PROJECT_ROOT=%s php %s help', escapeshellarg($this->tmpDir), escapeshellarg($releaseScribeBinary));
        exec($command, $output, $returnCode);

        $this->assertSame(0, $returnCode, 'help command should exit with 0');
        $this->assertStringContainsString('Usage:', implode("\n", $output));
    }

    public function testListDoesNotCrashWithoutGitRepo(): void
    {
        $releaseScribeBin = __DIR__ . '/../../bin/release-scribe';

        $command = sprintf('PROJECT_ROOT=%s php %s list', escapeshellarg($this->tmpDir), escapeshellarg($releaseScribeBin));
        exec($command, $output, $returnCode);

        $this->assertSame(0, $returnCode, 'list command should exit with 0');
        $this->assertStringContainsString('Available commands:', implode("\n", $output));
    }

    public function testChangelogHelpDoesNotCrashWithoutGitRepo(): void
    {
        $releaseScribeBin = __DIR__ . '/../../bin/release-scribe';

        $command = sprintf(
            'PROJECT_ROOT=%s php %s whats-new --help',
            escapeshellarg($this->tmpDir),
            escapeshellarg($releaseScribeBin)
        );
        exec($command, $output, $returnCode);

        $this->assertSame(0, $returnCode, 'whats-new --help command should exit with 0');
        $this->assertStringContainsString('Usage:', implode("\n", $output));
        $this->assertStringContainsString('whats-new', implode("\n", $output));
    }

    public function testChangelogFailsGracefullyWithoutGitRepo(): void
    {
        $releaseScribeBinary = __DIR__ . '/../../bin/release-scribe';

        // Use custom filename to bypass "not initialized" check and trigger Git check
        $command = sprintf(
            'PROJECT_ROOT=%s php %s whats-new --filename=test.md',
            escapeshellarg($this->tmpDir),
            escapeshellarg($releaseScribeBinary)
        );
        exec($command, $output, $returnCode);

        $this->assertNotSame(0, $returnCode, 'whats-new should fail without git repo');
        $outputStr = implode("\n", $output);
        $this->assertStringContainsString('Git error', $outputStr);
        $this->assertStringContainsString('repository path', $outputStr);
    }

    protected function setUp(): void
    {
        $this->tmpDir = sys_get_temp_dir() . '/release_scribe_test_' . uniqid();
        mkdir($this->tmpDir);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->tmpDir);
    }

    private function removeDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->removeDir("$dir/$file") : unlink("$dir/$file");
        }
        rmdir($dir);
    }
}
