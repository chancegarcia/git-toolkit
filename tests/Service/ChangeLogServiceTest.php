<?php

namespace Chance\GitToolkit\Test\Service;

use Chance\GitToolkit\Generator\GeneratorInterface;
use Chance\GitToolkit\GitInformation;
use Chance\GitToolkit\Service\ChangeLogService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SplFileObject;

class ChangeLogServiceTest extends TestCase
{
    private MockObject $gitInformationMock;
    private ChangeLogService $service;

    protected function setUp(): void
    {
        $this->gitInformationMock = $this->createMock(GitInformation::class);
        $this->service = new ChangeLogService($this->gitInformationMock);
    }

    public function testMainHeaderName(): void
    {
        self::assertSame(ChangeLogService::DEFAULT_MAIN_HEADER_NAME, $this->service->getMainHeaderName());
        $this->service->setMainHeaderName('Some Name');
        self::assertSame('Some Name', $this->service->getMainHeaderName());
    }

    public function testSetMainHeaderNullParameterPassed(): void
    {
        $this->service->setMainHeaderName(null);
        self::assertSame(ChangeLogService::DEFAULT_MAIN_HEADER_NAME, $this->service->getMainHeaderName());
    }

    public function testGenerator(): void
    {
        $generator = $this->createMock(GeneratorInterface::class);
        $this->service->setGenerator($generator);
        self::assertSame($generator, $this->service->getGenerator());
    }

    public function testWriteChangeLog(): void
    {
        $file = $this->createMock(SplFileObject::class);
        $generator = $this->createMock(GeneratorInterface::class);
        $generator->expects(self::once())
            ->method('generate')
            ->with($file, 'v1.0.0');

        $this->service->setGenerator($generator);
        $this->service->writeChangeLog($file, 'v1.0.0');
    }

    public function testFilePath(): void
    {
        self::assertSame(ChangeLogService::DEFAULT_FILE_PATH, $this->service->getChangeLogFilePath());
        $this->service->setChangeLogFilePath('some/path');
        self::assertSame('some/path/', $this->service->getChangeLogFilePath());

        $this->service->setChangeLogFilePath('some/path/');
        self::assertSame('some/path/', $this->service->getChangeLogFilePath());
    }

    public function testFileName(): void
    {
        self::assertSame(ChangeLogService::DEFAULT_FILE_NAME, $this->service->getChangeLogFileName());
        $this->service->setChangeLogFileName('file.md');
        self::assertSame('file.md', $this->service->getChangeLogFileName());
    }

    public function testFullPath(): void
    {
        $this->service->setChangeLogFilePath('path');
        $this->service->setChangeLogFileName('file.md');
        self::assertSame('path/file.md', $this->service->getFullPath());
    }

    public function testGitInformation(): void
    {
        self::assertSame($this->gitInformationMock, $this->service->getGitInformation());
    }
}
