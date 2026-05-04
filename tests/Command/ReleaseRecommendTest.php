<?php

namespace Chance\GitToolkit\Tests\Command;

use Chance\GitToolkit\Command\ReleaseRecommend;
use Chance\GitToolkit\GitInformation;
use Chance\GitToolkit\Service\ReleaseRecommender;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ReleaseRecommendTest extends TestCase
{
    private $gitInformation;
    private $recommender;

    public function testExecute(): void
    {
        $this->gitInformation->method('getGitTags')->willReturn(['v1.0.0']);
        $this->gitInformation->method('getCurrentCommit')->willReturn('abcd');
        $this->gitInformation->method('getCommitRange')->willReturnMap([
            ['v1.0.0', 'abcd', true, ["feat: new feature\n\nSome body text", "fix: bug fix\n\nFixed it"]],
        ]);

        $command = new ReleaseRecommend($this->gitInformation, $this->recommender);
        $wrapper = new Command('toolkit:release:recommend');
        $wrapper->setCode($command);
        $command->configure($wrapper);

        $tester = new CommandTester($wrapper);
        $tester->execute([]);

        $output = $tester->getDisplay();
        $this->assertStringContainsString('Recommended release: minor', $output);
        $this->assertStringContainsString('Reason: 1 feature commits found and no breaking changes detected.', $output);
        $this->assertStringContainsString('Highest-impact commit type found: feat', $output);
    }

    public function testExecuteNoCommits(): void
    {
        $this->gitInformation->method('getGitTags')->willReturn(['v1.0.0']);
        $this->gitInformation->method('getCurrentCommit')->willReturn('abcd');
        $this->gitInformation->method('getCommitRange')->willReturn([]);

        $command = new ReleaseRecommend($this->gitInformation, $this->recommender);
        $wrapper = new Command('toolkit:release:recommend');
        $wrapper->setCode($command);
        $command->configure($wrapper);

        $tester = new CommandTester($wrapper);
        $tester->execute([]);

        $output = $tester->getDisplay();
        $this->assertStringContainsString('Recommended release: none', $output);
    }

    protected function setUp(): void
    {
        $this->gitInformation = $this->createMock(GitInformation::class);
        $this->recommender = new ReleaseRecommender();
    }
}
