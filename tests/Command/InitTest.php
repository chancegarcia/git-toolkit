<?php

namespace Chance\GitToolkit\Test\Command;

use Chance\GitToolkit\Command\Init;
use Chance\GitToolkit\GitInformation;
use Chance\GitToolkit\Service\ChangeLogService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class InitTest extends TestCase
{
    private MockObject $changeLogServiceMock;
    private MockObject $gitInformationMock;
    private MockObject $splFileObjectMock;

    public function testExecuteWithTags(): void
    {
        $this->gitInformationMock->expects(self::once())->method('getGitTags')->willReturn(['v1.0.0'])
        ;

        $this->changeLogServiceMock->expects(self::once())->method('getSplFileObject')->willReturn(
            $this->splFileObjectMock
        )
        ;

        $this->changeLogServiceMock->expects(self::once())->method('writeChangeLog')->with(
            $this->splFileObjectMock,
            null
        )
        ;

        $this->changeLogServiceMock->expects(self::once())->method('getFullPath')->willReturn('changelog.md')
        ;

        $command = new Init($this->changeLogServiceMock); // @phpstan-ignore-line
        $tester = $this->getCommandTester($command);
        $tester->execute([]);

        self::assertStringContainsString("success: file 'changelog.md' has been initialized", $tester->getDisplay());
        self::assertSame(0, $tester->getStatusCode());
    }

    private function getCommandTester(Init $command): CommandTester
    {
        $wrapper = new Command('toolkit:init');
        $wrapper->setCode($command);
        $command->configure($wrapper);

        return new CommandTester($wrapper);
    }

    public function testExecuteWithoutTagsDefaultVersion(): void
    {
        $this->gitInformationMock->expects(self::once())->method('getGitTags')->willReturn([])
        ;

        $this->changeLogServiceMock->expects(self::once())->method('getSplFileObject')->willReturn(
            $this->splFileObjectMock
        )
        ;

        $this->changeLogServiceMock->expects(self::once())->method('writeChangeLog')->with(
            $this->splFileObjectMock,
            'v1.0.0'
        )
        ;

        $command = new Init($this->changeLogServiceMock); // @phpstan-ignore-line
        $tester = $this->getCommandTester($command);
        $tester->execute([]);

        self::assertSame(0, $tester->getStatusCode());
    }

    public function testExecuteWithoutTagsCustomVersion(): void
    {
        $this->gitInformationMock->expects(self::once())->method('getGitTags')->willReturn([])
        ;

        $this->changeLogServiceMock->expects(self::once())->method('getSplFileObject')->willReturn(
            $this->splFileObjectMock
        )
        ;

        $this->changeLogServiceMock->expects(self::once())->method('writeChangeLog')->with(
            $this->splFileObjectMock,
            'v0.1.0'
        )
        ;

        $command = new Init($this->changeLogServiceMock); // @phpstan-ignore-line
        $tester = $this->getCommandTester($command);
        $tester->execute(['--initial-version' => 'v0.1.0']);

        self::assertSame(0, $tester->getStatusCode());
    }

    protected function setUp(): void
    {
        $this->changeLogServiceMock = $this->createMock(ChangeLogService::class);
        $this->gitInformationMock = $this->createMock(GitInformation::class);
        $this->splFileObjectMock = $this->getMockBuilder(\SplFileObject::class)->setConstructorArgs(
            ['php://memory', 'wb+']
        )->getMock()
        ;

        $this->changeLogServiceMock->method('getGitInformation')->willReturn($this->gitInformationMock)
        ;
    }
}
