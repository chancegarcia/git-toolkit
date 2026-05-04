<?php

namespace Chance\GitToolkit\Tests\Integration;

use PHPUnit\Framework\TestCase;

class ToolkitHelpTest extends TestCase
{
    private string $tmpDir;

    public function testHelpDoesNotCrashWithoutGitRepo(): void
    {
        $toolkitBin = __DIR__ . '/../../bin/toolkit';

        // Run help command in a directory that is not a git repo
        $command = sprintf('PROJECT_ROOT=%s php %s help', escapeshellarg($this->tmpDir), escapeshellarg($toolkitBin));
        exec($command, $output, $returnCode);

        $this->assertSame(0, $returnCode, 'help command should exit with 0');
        $this->assertStringContainsString('Usage:', implode("\n", $output));
    }

    public function testListDoesNotCrashWithoutGitRepo(): void
    {
        $toolkitBin = __DIR__ . '/../../bin/toolkit';

        $command = sprintf('PROJECT_ROOT=%s php %s list', escapeshellarg($this->tmpDir), escapeshellarg($toolkitBin));
        exec($command, $output, $returnCode);

        $this->assertSame(0, $returnCode, 'list command should exit with 0');
        $this->assertStringContainsString('Available commands:', implode("\n", $output));
    }

    public function testChangelogHelpDoesNotCrashWithoutGitRepo(): void
    {
        $toolkitBin = __DIR__ . '/../../bin/toolkit';

        $command = sprintf(
            'PROJECT_ROOT=%s php %s toolkit:changelog --help',
            escapeshellarg($this->tmpDir),
            escapeshellarg($toolkitBin)
        );
        exec($command, $output, $returnCode);

        $this->assertSame(0, $returnCode, 'toolkit:changelog --help command should exit with 0');
        $this->assertStringContainsString('Usage:', implode("\n", $output));
        $this->assertStringContainsString('toolkit:changelog', implode("\n", $output));
    }

    public function testChangelogFailsGracefullyWithoutGitRepo(): void
    {
        $toolkitBin = __DIR__ . '/../../bin/toolkit';

        // Use custom filename to bypass "not initialized" check and trigger Git check
        $command = sprintf(
            'PROJECT_ROOT=%s php %s toolkit:changelog --filename=test.md',
            escapeshellarg($this->tmpDir),
            escapeshellarg($toolkitBin)
        );
        exec($command, $output, $returnCode);

        $this->assertNotSame(0, $returnCode, 'toolkit:changelog should fail without git repo');
        $outputStr = implode("\n", $output);
        $this->assertStringContainsString('Git error', $outputStr);
        $this->assertStringContainsString('repository path', $outputStr);
    }

    protected function setUp(): void
    {
        $this->tmpDir = sys_get_temp_dir() . '/toolkit_test_' . uniqid();
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
