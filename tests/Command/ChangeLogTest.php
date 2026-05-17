<?php

namespace Chance\ReleaseScribe\Test\Command;

use Chance\ReleaseScribe\Command\ChangeLog;
use Chance\ReleaseScribe\Service\ChangeLogService;
use CzProject\GitPhp\GitException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use SplFileObject;
use Symfony\Component\Console\Command\Command;

class ChangeLogTest extends TestCase
{
    private MockObject $changeLogServiceMock;
    private MockObject $splFileObjectMock;

    protected function setUp(): void
    {
        $this->changeLogServiceMock = $this->createMock(ChangeLogService::class);
        $this->splFileObjectMock = $this->getMockBuilder(SplFileObject::class)
            ->setConstructorArgs(['php://memory', 'wb+'])
            ->getMock();
    }

    private function getCommandTester(ChangeLog $command): CommandTester
    {
        $wrapper = new Command('whats-new');
        $wrapper->setCode($command);
        $command->configure($wrapper);
        return new CommandTester($wrapper);
    }

    public function testExecute(): void
    {
        $this->changeLogServiceMock->expects(self::once())->method('changeLogFileExists')->willReturn(true)
        ;
        $this->changeLogServiceMock->expects(self::once())->method('setMainHeaderName')->with(
            ChangeLogService::DEFAULT_WHATS_NEW_HEADER_NAME
        )
        ;
        $this->changeLogServiceMock->expects(self::never())
            ->method('setChangeLogFilePath');
        $this->changeLogServiceMock->expects(self::never())
            ->method('setChangeLogFileName');
        $this->changeLogServiceMock->expects(self::once())
                             ->method('getSplFileObject')
            ->willReturn($this->splFileObjectMock);
        $this->changeLogServiceMock->expects(self::once())
                             ->method('writeChangeLog')
            ->with($this->isInstanceOf(SplFileObject::class), null, null);

        $changeLogCommand = new ChangeLog($this->changeLogServiceMock); // @phpstan-ignore-line

        self::assertSame($this->changeLogServiceMock, $changeLogCommand->getChangeLogService());

        $commandTester = $this->getCommandTester($changeLogCommand);
        $commandTester->execute([]);
    }

    public function testExecuteSuccessMessage(): void
    {
        $this->changeLogServiceMock->expects(self::once())->method('changeLogFileExists')->willReturn(true)
        ;
        $this->changeLogServiceMock->expects(self::once())
                             ->method('getSplFileObject')
            ->willReturn($this->splFileObjectMock);

        $this->changeLogServiceMock->expects(self::once())
            ->method('getFullPath')
            ->willReturn('some/path');

        $changeLogCommand = new ChangeLog($this->changeLogServiceMock); // @phpstan-ignore-line

        $commandTester = $this->getCommandTester($changeLogCommand);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        self::assertStringContainsString("success: file 'some/path' has been created", $output);
    }

    public function testExecuteWithHeaderArgument(): void
    {
        $this->changeLogServiceMock->expects(self::once())->method('changeLogFileExists')->willReturn(true)
        ;
        $this->changeLogServiceMock->expects(self::once())
            ->method('setMainHeaderName')
            ->with('some project name');
        $this->changeLogServiceMock->expects(self::once())
                             ->method('getSplFileObject')
            ->willReturn($this->splFileObjectMock);

        $changeLogCommand = new ChangeLog($this->changeLogServiceMock); // @phpstan-ignore-line

        $commandTester = $this->getCommandTester($changeLogCommand);
        $commandTester->execute([
            'header' => 'some project name',
        ]);
    }

    public function testExecuteWithNewTagOption(): void
    {
        $this->changeLogServiceMock->expects(self::once())->method('changeLogFileExists')->willReturn(true)
        ;
        $this->changeLogServiceMock->expects(self::once())
                             ->method('getSplFileObject')
            ->willReturn($this->splFileObjectMock);
        $this->changeLogServiceMock->expects(self::once())
                             ->method('writeChangeLog')
            ->with($this->isInstanceOf(SplFileObject::class), 'v1.0.0', null);

        $changeLogCommand = new ChangeLog($this->changeLogServiceMock); // @phpstan-ignore-line

        $commandTester = $this->getCommandTester($changeLogCommand);
        $commandTester->execute([
            '--new-tag' => 'v1.0.0',
        ]);
    }

    public function testExecuteWithPreviousTagOption(): void
    {
        $this->changeLogServiceMock->expects(self::once())->method('changeLogFileExists')->willReturn(true)
        ;
        $this->changeLogServiceMock->expects(self::once())
            ->method('getSplFileObject')
            ->willReturn($this->splFileObjectMock);
        $this->changeLogServiceMock->expects(self::once())
            ->method('writeChangeLog')
            ->with($this->isInstanceOf(SplFileObject::class), 'v2.0.0', 'v1.9.0');

        $changeLogCommand = new ChangeLog($this->changeLogServiceMock); // @phpstan-ignore-line

        $commandTester = $this->getCommandTester($changeLogCommand);
        $commandTester->execute([
            '--new-tag' => 'v2.0.0',
            '--previous-tag' => 'v1.9.0',
        ]);
    }

    public function testExecuteWithPreviousTagOptionWithoutNewTagFails(): void
    {
        $this->changeLogServiceMock->expects(self::never())
            ->method('writeChangeLog');

        $changeLogCommand = new ChangeLog($this->changeLogServiceMock); // @phpstan-ignore-line

        $commandTester = $this->getCommandTester($changeLogCommand);
        $commandTester->execute([
            '--previous-tag' => 'v1.9.0',
        ]);

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('error: --previous-tag requires --new-tag', $output);
        self::assertSame(1, $commandTester->getStatusCode());
    }

    public function testExecuteWithOutputDirOption(): void
    {
        $this->changeLogServiceMock->expects(self::once())->method('changeLogFileExists')->willReturn(true)
        ;
        $this->changeLogServiceMock->expects(self::once())
                             ->method('setChangeLogFilePath')
            ->with('some/path');
        $this->changeLogServiceMock->expects(self::once())
            ->method('getSplFileObject')
            ->willReturn($this->splFileObjectMock);

        $changeLogCommand = new ChangeLog($this->changeLogServiceMock); // @phpstan-ignore-line

        $commandTester = $this->getCommandTester($changeLogCommand);
        $commandTester->execute([
            '--output-dir' => 'some/path',
        ]);
    }

    public function testExecuteWithFileNameOption(): void
    {
        $this->changeLogServiceMock->expects(self::once())->method('changeLogFileExists')->willReturn(true)
        ;
        $this->changeLogServiceMock->expects(self::once())
                             ->method('setChangeLogFileName')
            ->with('some-file.md');
        $this->changeLogServiceMock->expects(self::once())
            ->method('getSplFileObject')
            ->willReturn($this->splFileObjectMock);

        $changeLogCommand = new ChangeLog($this->changeLogServiceMock); // @phpstan-ignore-line

        $commandTester = $this->getCommandTester($changeLogCommand);
        $commandTester->execute([
            '--filename' => 'some-file.md',
        ]);
    }

    public function testExecuteWithGitException(): void
    {
        $this->changeLogServiceMock->expects(self::once())->method('changeLogFileExists')->willReturn(true)
        ;
        $this->changeLogServiceMock->expects(self::once())
                             ->method('getSplFileObject')
            ->willThrowException(new GitException('some git error'));

        $changeLogCommand = new ChangeLog($this->changeLogServiceMock); // @phpstan-ignore-line

        $commandTester = $this->getCommandTester($changeLogCommand);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        self::assertStringContainsString(
            'Git error: The configured repository path may not be a valid Git repository or was not found.',
            $output
        );
        self::assertStringContainsString('Details: some git error', $output);
    }

    public function testExecuteFailsWhenChangelogNotInitialized(): void
    {
        $this->changeLogServiceMock->expects(self::once())->method('changeLogFileExists')->willReturn(false)
        ;

        $changeLogCommand = new ChangeLog($this->changeLogServiceMock); // @phpstan-ignore-line

        $commandTester = $this->getCommandTester($changeLogCommand);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('No changelog has been initialized for this project.', $output);
        self::assertStringContainsString('Run "release-scribe init" to create one', $output);
        self::assertSame(1, $commandTester->getStatusCode());
    }

    public function testExecuteWithModeFull(): void
    {
        $this->changeLogServiceMock->expects(self::once())->method('changeLogFileExists')->willReturn(true)
        ;
        $this->changeLogServiceMock->expects(self::once())->method('setFullHistory')->with(true)
        ;
        $this->changeLogServiceMock->expects(self::never())->method('setMainHeaderName')
        ;
        $this->changeLogServiceMock->expects(self::once())->method('getSplFileObject')->willReturn(
            $this->splFileObjectMock
        )
        ;

        $changeLogCommand = new ChangeLog($this->changeLogServiceMock); // @phpstan-ignore-line
        $commandTester = $this->getCommandTester($changeLogCommand);
        $commandTester->execute(['--mode' => 'full']);
    }

    public function testExecuteWithModeWhatsNew(): void
    {
        $this->changeLogServiceMock->expects(self::once())->method('changeLogFileExists')->willReturn(true)
        ;
        $this->changeLogServiceMock->expects(self::once())->method('setFullHistory')->with(false)
        ;
        $this->changeLogServiceMock->expects(self::once())->method('setMainHeaderName')->with(
            ChangeLogService::DEFAULT_WHATS_NEW_HEADER_NAME
        )
        ;

        $changeLogCommand = new ChangeLog($this->changeLogServiceMock); // @phpstan-ignore-line
        $commandTester = $this->getCommandTester($changeLogCommand);
        $commandTester->execute(['--mode' => 'whats-new']);
    }

    public function testExecuteWithModeAliasCurrent(): void
    {
        $this->changeLogServiceMock->expects(self::once())->method('changeLogFileExists')->willReturn(true)
        ;
        $this->changeLogServiceMock->expects(self::once())->method('setFullHistory')->with(false)
        ;

        $changeLogCommand = new ChangeLog($this->changeLogServiceMock); // @phpstan-ignore-line
        $commandTester = $this->getCommandTester($changeLogCommand);
        $commandTester->execute(['--mode' => 'current']);
    }

    public function testExecuteWithModeAliasHistory(): void
    {
        $this->changeLogServiceMock->expects(self::once())->method('changeLogFileExists')->willReturn(true)
        ;
        $this->changeLogServiceMock->expects(self::once())->method('setFullHistory')->with(true)
        ;

        $changeLogCommand = new ChangeLog($this->changeLogServiceMock); // @phpstan-ignore-line
        $commandTester = $this->getCommandTester($changeLogCommand);
        $commandTester->execute(['--mode' => 'history']);
    }

    public function testExecuteWithInvalidModeFails(): void
    {
        $changeLogCommand = new ChangeLog($this->changeLogServiceMock); // @phpstan-ignore-line
        $commandTester = $this->getCommandTester($changeLogCommand);
        $commandTester->execute(['--mode' => 'invalid']);

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('error: invalid mode "invalid"', $output);
        self::assertSame(1, $commandTester->getStatusCode());
    }

    public function testExecuteCreatesFileWhenNotInitializedButCustomTargetProvided(): void
    {
        $this->changeLogServiceMock->expects(self::once())->method('changeLogFileExists')->willReturn(false)
        ;
        $this->changeLogServiceMock->expects(self::once())->method('getSplFileObject')->willReturn(
            $this->splFileObjectMock
        )
        ;

        $changeLogCommand = new ChangeLog($this->changeLogServiceMock); // @phpstan-ignore-line
        $commandTester = $this->getCommandTester($changeLogCommand);
        $commandTester->execute(['--filename' => 'custom.md']);
        self::assertSame(0, $commandTester->getStatusCode());
    }
}
