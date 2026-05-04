<?php

namespace Chance\GitToolkit\Tests\Integration;

use Chance\GitToolkit\Collector\CollectorInterface;
use Chance\GitToolkit\Generator\ConventionalCommitGenerator;
use Chance\GitToolkit\Renderer\ConventionalMarkdownRenderer;
use PHPUnit\Framework\TestCase;
use SplFileObject;

class ConventionalCommitGroupingTest extends TestCase
{
    public function testGrouping(): void
    {
        $collector = $this->createMock(CollectorInterface::class);
        $collector->method('collect')->willReturn([
            'v1.1.0' => [
                'feat: new feature',
                'fix(ui): fix bug',
                'feat!: breaking change',
                'chore: ignored',
                'docs: update readme',
            ],
        ]);

        $renderer = new ConventionalMarkdownRenderer();
        $generator = new ConventionalCommitGenerator($collector, $renderer, 'Test Project');

        $tmpFile = tempnam(sys_get_temp_dir(), 'changelog');
        $file = new SplFileObject($tmpFile, 'wb+');

        $generator->generate($file);

        $content = file_get_contents($tmpFile);

        $this->assertStringContainsString('# Test Project', $content);
        $this->assertStringContainsString('## v1.1.0', $content);
        $this->assertStringContainsString('### Breaking Changes', $content);
        $this->assertStringContainsString('### Features', $content);
        $this->assertStringContainsString('### Bug Fixes', $content);
        $this->assertStringContainsString('### Documentation', $content);

        $this->assertStringContainsString('- new feature', $content);
        $this->assertStringContainsString('- \*\*ui:\*\* fix bug', $content);
        $this->assertStringContainsString('- breaking change', $content);

        $this->assertStringNotContainsString('### chore', $content);
        $this->assertStringNotContainsString('ignored', $content);

        unlink($tmpFile);
    }

    public function testLegacyModeToggle(): void
    {
        // This is more of a ChangeLogService test
        // But we can check if it respects the env var
        $oldEnv = $_ENV['CHANGELOG_USE_CONVENTIONAL_COMMITS'] ?? null;

        try {
            $_ENV['CHANGELOG_USE_CONVENTIONAL_COMMITS'] = 'false';
            $service = new \Chance\GitToolkit\Service\ChangeLogService(
                $this->createMock(\Chance\GitToolkit\GitInformation::class)
            );
            $this->assertInstanceOf(\Chance\GitToolkit\Generator\LegacyGenerator::class, $service->getGenerator());

            $_ENV['CHANGELOG_USE_CONVENTIONAL_COMMITS'] = 'true';
            $service = new \Chance\GitToolkit\Service\ChangeLogService(
                $this->createMock(\Chance\GitToolkit\GitInformation::class)
            );
            $this->assertInstanceOf(
                \Chance\GitToolkit\Generator\ConventionalCommitGenerator::class,
                $service->getGenerator()
            );
        } finally {
            if ($oldEnv === null) {
                unset($_ENV['CHANGELOG_USE_CONVENTIONAL_COMMITS']);
            } else {
                $_ENV['CHANGELOG_USE_CONVENTIONAL_COMMITS'] = $oldEnv;
            }
        }
    }
}
