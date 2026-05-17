<?php

namespace Chance\ReleaseScribe\Test\Service;

use Chance\ReleaseScribe\Generator\GeneratorInterface;
use Chance\ReleaseScribe\GitInformation;
use Chance\ReleaseScribe\Service\ChangeLogService;
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
        $generator = $this->createMock(GeneratorInterface::class);
        $generator->expects(self::once())
            ->method('generate')->with(self::isInstanceOf(SplFileObject::class), 'v1.0.0', 'v0.9.0', false)
        ;

        $this->service->setGenerator($generator);
        $this->service->writeChangeLog(new SplFileObject('php://memory', 'wb+'), 'v1.0.0', 'v0.9.0');
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

    public function testMainHeaderAffectsOutput(): void
    {
        $this->service->setMainHeaderName('Custom Name');
        $this->gitInformationMock->method('getGitTags')->willReturn([]);

        $tempFile = new SplFileObject('php://memory', 'wb+');
        $this->service->writeChangeLog($tempFile);
        $tempFile->fseek(0);
        $content = $tempFile->fread(1024);

        self::assertStringContainsString('# Custom Name', $content);
    }

    public function testMainHeaderChangeAfterGeneratorCreatedAffectsOutput(): void
    {
        // call getGenerator to "freeze" it if it's bugged
        $this->service->getGenerator();

        $this->service->setMainHeaderName('Custom Name');
        $this->gitInformationMock->method('getGitTags')->willReturn([]);

        $tempFile = new SplFileObject('php://memory', 'wb+');
        $this->service->writeChangeLog($tempFile);
        $tempFile->fseek(0);
        $content = $tempFile->fread(1024);

        self::assertStringContainsString('# Custom Name', $content);
    }

    public function testMainHeaderNullResetsOutputToDefault(): void
    {
        $this->service->setMainHeaderName('Custom Name');
        $this->service->setMainHeaderName(null);
        $this->gitInformationMock->method('getGitTags')->willReturn([]);

        $tempFile = new SplFileObject('php://memory', 'wb+');
        $this->service->writeChangeLog($tempFile);
        $tempFile->fseek(0);
        $content = $tempFile->fread(1024);

        self::assertStringContainsString('# ' . ChangeLogService::DEFAULT_MAIN_HEADER_NAME, $content);
    }
}
