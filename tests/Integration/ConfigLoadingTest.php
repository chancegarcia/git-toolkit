<?php

namespace Chance\GitToolkit\Test\Integration;

use Chance\GitToolkit\GitInformation;
use Chance\GitToolkit\Service\ChangeLogService;
use PHPUnit\Framework\TestCase;

class ConfigLoadingTest extends TestCase
{
    private string $tmpConfigDir;
    private string $tmpConfigFile;

    public function testConfigValuesAreApplied(): void
    {
        $configContent = <<<'PHP'
<?php
return [
    'project_name' => 'Config Project Name',
    'filename' => 'config-changelog.md',
    'output_directory' => '/tmp/config-output',
];
PHP;
        file_put_contents($this->tmpConfigFile, $configContent);

        // Simulate logic from bin/toolkit
        $config = require $this->tmpConfigFile;

        $projectName = $config['project_name'] ?? null;
        $outputFilename = $config['filename'] ?? null;
        $outputPath = $config['output_directory'] ?? null;

        $gitInfo = $this->createMock(GitInformation::class);
        $service = new ChangeLogService($gitInfo);

        if ($outputFilename) {
            $service->setChangeLogFileName($outputFilename);
        }
        if ($outputPath) {
            $service->setChangeLogFilePath($outputPath);
        }
        if ($projectName) {
            $service->setMainHeaderName($projectName);
        }

        $this->assertEquals('Config Project Name', $service->getMainHeaderName());
        $this->assertEquals('config-changelog.md', $service->getChangeLogFileName());
        $this->assertEquals('/tmp/config-output/', $service->getChangeLogFilePath());
    }

    protected function setUp(): void
    {
        $this->tmpConfigDir = sys_get_temp_dir() . '/git-toolkit-config-' . uniqid();
        mkdir($this->tmpConfigDir);
        $this->tmpConfigFile = $this->tmpConfigDir . '/chancegarcia_git_toolkit.php';
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tmpConfigFile)) {
            unlink($this->tmpConfigFile);
        }
        if (is_dir($this->tmpConfigDir)) {
            rmdir($this->tmpConfigDir);
        }
    }
}
