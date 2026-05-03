<?php

namespace Chance\GitToolkit\Test\Command;

use Chance\GitToolkit\Command\ChangeLog;
use Chance\GitToolkit\Service\ChangeLogService;
use CzProject\GitPhp\GitException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ChangeLogTest extends TestCase
{
    private MockObject $changeLogServiceMock;
    private MockObject $splFileObjectMock;

    protected function setUp(): void
    {
        $this->changeLogServiceMock = $this->createMock(ChangeLogService::class);
        $this->splFileObjectMock = $this->createMock(\SplFileObject::class);
    }

    public function testExecute(): void
    {
        $this->changeLogServiceMock->expects(self::never())
            ->method('setMainHeaderName');
        $this->changeLogServiceMock->expects(self::never())
            ->method('setChangeLogFilePath');
        $this->changeLogServiceMock->expects(self::never())
            ->method('setChangeLogFileName');
        $this->changeLogServiceMock->expects(self::once())
                             ->method('getSplFileObject')
            ->willReturn($this->splFileObjectMock);
        $this->changeLogServiceMock->expects(self::once())
                             ->method('writeChangeLog')
            ->with($this->isInstanceOf(\SplFileObject::class), null);

        $changeLogCommand = new ChangeLog();
        $changeLogCommand->setChangeLogService($this->changeLogServiceMock);

        self::assertSame($this->changeLogServiceMock, $changeLogCommand->getChangeLogService());

        $commandTester = new CommandTester($changeLogCommand);
        $commandTester->execute([]);
    }

    public function testExecuteSuccessMessage(): void
    {
        $this->changeLogServiceMock->expects(self::once())
                             ->method('getSplFileObject')
            ->willReturn($this->splFileObjectMock);

        $this->changeLogServiceMock->expects(self::once())
            ->method('getFullPath')
            ->willReturn('some/path');

        $changeLogCommand = new ChangeLog();
        $changeLogCommand->setChangeLogService($this->changeLogServiceMock);

        $commandTester = new CommandTester($changeLogCommand);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        self::assertStringContainsString("success: file 'some/path' has been created", $output);
    }

    public function testExecuteWithHeaderArgument(): void
    {
        $this->changeLogServiceMock->expects(self::once())
            ->method('setMainHeaderName')
            ->with('some project name');
        $this->changeLogServiceMock->expects(self::once())
                             ->method('getSplFileObject')
            ->willReturn($this->splFileObjectMock);

        $changeLogCommand = new ChangeLog();
        $changeLogCommand->setChangeLogService($this->changeLogServiceMock);

        $commandTester = new CommandTester($changeLogCommand);
        $commandTester->execute([
            'header' => 'some project name',
        ]);
    }

    public function testExecuteWithNewTagOption(): void
    {
        $this->changeLogServiceMock->expects(self::once())
                             ->method('getSplFileObject')
            ->willReturn($this->splFileObjectMock);
        $this->changeLogServiceMock->expects(self::once())
                             ->method('writeChangeLog')
            ->with($this->isInstanceOf(\SplFileObject::class), 'v1.0.0');

        $changeLogCommand = new ChangeLog();
        $changeLogCommand->setChangeLogService($this->changeLogServiceMock);

        $commandTester = new CommandTester($changeLogCommand);
        $commandTester->execute([
            '--new-tag' => 'v1.0.0',
        ]);
    }

    public function testExecuteWithOutputDirOption(): void
    {
        $this->changeLogServiceMock->expects(self::once())
                             ->method('setChangeLogFilePath')
            ->with('some/path');
        $this->changeLogServiceMock->expects(self::once())
            ->method('getSplFileObject')
            ->willReturn($this->splFileObjectMock);

        $changeLogCommand = new ChangeLog();
        $changeLogCommand->setChangeLogService($this->changeLogServiceMock);

        $commandTester = new CommandTester($changeLogCommand);
        $commandTester->execute([
            '--output-dir' => 'some/path',
        ]);
    }

    public function testExecuteWithFileNameOption(): void
    {
        $this->changeLogServiceMock->expects(self::once())
                             ->method('setChangeLogFileName')
            ->with('some-file.md');
        $this->changeLogServiceMock->expects(self::once())
            ->method('getSplFileObject')
            ->willReturn($this->splFileObjectMock);

        $changeLogCommand = new ChangeLog();
        $changeLogCommand->setChangeLogService($this->changeLogServiceMock);

        $commandTester = new CommandTester($changeLogCommand);
        $commandTester->execute([
            '--filename' => 'some-file.md',
        ]);
    }

    public function testExecuteWithGitException(): void
    {
        $this->changeLogServiceMock->expects(self::once())
                             ->method('getSplFileObject')
            ->willThrowException(new GitException('some git error'));

        $this->changeLogServiceMock->expects(self::once())
            ->method('getFullPath')
            ->willReturn('some/path');

        $changeLogCommand = new ChangeLog();
        $changeLogCommand->setChangeLogService($this->changeLogServiceMock);

        $commandTester = new CommandTester($changeLogCommand);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('error: file "some/path" was not written or maybe partially written.', $output);
        self::assertStringContainsString('error message: some git error', $output);
    }
}
