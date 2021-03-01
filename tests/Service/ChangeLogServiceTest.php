<?php

/**
 * @package
 * @subpackage
 * @author      Chance Garcia <chance@garcia.codes>
 * @copyright   (C)Copyright 2013-2021 Chance Garcia, chancegarcia.com
 *
 *    The MIT License (MIT)
 *
 * Copyright (c) 2013-2021 Chance Garcia
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */

namespace Chance\GitToolkit\Test\Service;

use Chance\GitToolkit\GitInformation;
use Chance\GitToolkit\Service\ChangeLogService;
use Cz\Git\GitRepository;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;

class ChangeLogServiceTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockBuilder|\SplFileObject
     */
    private $splFileObjectMockBuilder;
    /**
     * @var \PHPUnit\Framework\MockObject\MockBuilder|GitInformation
     */
    private $gitInfoMockBuilder;

    /**
     * @var \PHPUnit\Framework\MockObject\MockBuilder|GitRepository
     */
    private $repoMockBuilder;

    public function setUp(): void
    {
        parent::setUp();

        // @formatter:off
        $this->splFileObjectMockBuilder = $this->getMockBuilder(\SplFileObject::class)
                                               ->setConstructorArgs(['php://memory'])
        ;

        $this->gitInfoMockBuilder = $this->getMockBuilder(GitInformation::class)->disableOriginalConstructor();

        $this->repoMockBuilder = $this->getMockBuilder(GitRepository::class)->disableOriginalConstructor();

        // @formatter:on
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->splFileObjectMockBuilder = null;
        $this->gitInfoMockBuilder = null;
        $this->repoMockBuilder = null;
    }

    public function testMainHeaderName()
    {
        $service = $this->getServiceWithInfoMock();
        $service->setMainHeaderName('foo');

        self::assertSame('foo', $service->getMainHeaderName());
    }

    /**
     * @depends testMainHeaderName
     */
    public function testSetMainHeaderNullParamaterPassed()
    {
        $service = $this->getServiceWithInfoMock();
        $service->setMainHeaderName('foo');

        // this will make sure we do not have the default name. so we can test changing back to the default;
        self::assertSame('foo', $service->getMainHeaderName());

        // no need to test for `foo` value being set correctly because of test dependency
        // for this method already confirming that it will set as expected
        $service->setMainHeaderName(null);

        try {
            self::assertSame(ChangeLogService::DEFAULT_MAIN_HEADER_NAME, $service->getMainHeaderName());
        } catch (ExpectationFailedException $e) {
            $msg = sprintf(
                'main header name not set to "%s" when passing null explictly to %s::setMainHeaderName()',
                ChangeLogService::DEFAULT_MAIN_HEADER_NAME,
                ChangeLogService::class
            );
            self::fail($msg);
        }
    }

    public function testWriteTag()
    {
        $tagName = 'taggy mctaggerson';
        $commits = 'commit message: committy mccommitterson';

        $fileMock = $this->splFileObjectMockBuilder->getMock();
        // @formatter:off
        $fileMock->expects(self::exactly(2))
                 ->method('fwrite')
                 ->withAnyParameters()
        ;

        $service = $this->getServiceWithInfoMock();

        try {
            $service->writeTag($fileMock, $tagName, $commits);
        } catch (ExpectationFailedException $e) {
            self::fail($e->getMessage());
        }
    }

    /**
     * @depends testWriteTag
     */
    public function testWriteNewTag()
    {
        $fileMock = $this->splFileObjectMockBuilder->getMock();
        // @formatter:off
        $fileMock->expects(self::exactly(2))
                 ->method('fwrite')
                 ->withAnyParameters()
        ;
        // @formatter:on

        $infoMock = $this->gitInfoMockBuilder->getMock();
        // @formatter:off
        $infoMock->expects(self::atLeastOnce())
                 ->method('getNewCommits')
                 ->withAnyParameters()
                 ->willReturn([
                    'foo',
                    'bar',
                    'baz',
                ])
        ;

        $service = new ChangeLogService($infoMock);

        $service->writeNewTag($fileMock, '0.1.0');
    }

    /**
     * @depends testWriteTag
     */
    public function testWriteChangeLogNoNewTagWithExistingHistory()
    {
        $tags = [
            '4.1.3',
            '4.1.2',
            '4.1.1',
            '4.1.0',
            "",
            ];

        $commits = [
            "perf: made this better",
            "refactor: also patch a thing ",
            "fix: some sort of patch\n\n- we have additional notes in the body here",
            "feature: initial commit",
        ];

        // @formatter:off
        $repoMock = $this->repoMockBuilder->getMock();
        $infoMock = $this->gitInfoMockBuilder->enableOriginalConstructor()->setConstructorArgs([$repoMock])->getMock();

        $infoMock->expects(self::atLeastOnce())
            ->method('getGitTags')
            ->willReturn($tags)
        ;

        $infoMock->expects(self::atLeastOnce())
            ->method('getFirstCommit')
            ->willReturn($tags[count($tags) - 1])
        ;

        $infoMock->expects(self::atLeastOnce())
            ->method('getCommits')
            ->willReturn($commits)
        ;

        $infoMock->expects(self::once())
            ->method('getCurrentCommit')
        ;

        // writes the main header once then 2 writes per tag (tag name and commit string)
        $expectedWrites = (count($tags) * 2) + 1;
        $splFileObjectMock = $this->splFileObjectMockBuilder->getMock();
        $splFileObjectMock->expects(self::exactly($expectedWrites))
            ->method('fwrite')
        ;

        // @formatter:on

        $service = new ChangeLogService($infoMock);
        $service->writeChangeLog($splFileObjectMock);

        // test current tag is blank?
    }

    /**
     * @depends testWriteNewTag
     */
    public function testWriteChangeLogNewTagWithExistingHistory()
    {
        $tags = [
            '4.1.3',
            '4.1.2',
            '4.1.1',
            '4.1.0',
        ];

        $commits = [
            "perf: made this better",
            "refactor: also patch a thing ",
            "fix: some sort of patch\n\n- we have additional notes in the body here",
            "feature: initial commit",
        ];

        $newCommits = [
            'fix: something after 4.1.3',
            'fix: another thing after 4.1.3',
            'fix: last thing after 4.1.3',
        ];

        // @formatter:off
        $repoMock = $this->repoMockBuilder->getMock();
        $infoMock = $this->gitInfoMockBuilder->enableOriginalConstructor()->setConstructorArgs([$repoMock])->getMock();

        $infoMock->expects(self::atLeastOnce())
            ->method('getGitTags')
            ->willReturn($tags)
        ;

        $infoMock->expects(self::atLeastOnce())
                 ->method('getFirstCommit')
                 ->willReturn($tags[count($tags) - 1])
        ;

        $infoMock->expects(self::atLeastOnce())
                 ->method('getCommits')
                 ->willReturn($commits)
        ;

        $infoMock->expects(self::atLeastOnce())
            ->method('getNewCommits')
        ;

        // writes the main header once then 2 writes per tag (tag name and commit string)
        // plus new commit tag name and it's header
        $expectedWrites = (count($tags) * 2) + 3;
        $splFileObjectMock = $this->splFileObjectMockBuilder->getMock();
        $splFileObjectMock->expects(self::exactly($expectedWrites))
                          ->method('fwrite')
        ;

        // @formatter:on
        $service = new ChangeLogService($infoMock);
        $service->writeChangeLog($splFileObjectMock, "4.1.4");

        // test current tag is blank?
    }

    /**
     * @depends testWriteTag
     */
    public function testWriteChangeLogNoNewTagWithNoHistory()
    {
        $commits = [
            "perf: made this better",
            "refactor: also patch a thing ",
            "fix: some sort of patch\n\n- we have additional notes in the body here",
            "feature: initial commit",
        ];

        // @formatter:off
        $repoMock = $this->repoMockBuilder->getMock();
        $infoMock = $this->gitInfoMockBuilder->enableOriginalConstructor()->setConstructorArgs([$repoMock])->getMock();

        $infoMock->expects(self::once())
            ->method('getCurrentCommit')
        ;

        $infoMock->expects(self::atLeastOnce())
                 ->method('getGitTags')
                 ->willReturn([])
        ;

        // writes the main header once then 2 writes for the new tag name and the commits
        $expectedWrites = 3;
        $splFileObjectMock = $this->splFileObjectMockBuilder->getMock();
        $splFileObjectMock->expects(self::exactly($expectedWrites))
                          ->method('fwrite')
        ;

        // @formatter:on

        $service = new ChangeLogService($infoMock);
        $service->writeChangeLog($splFileObjectMock);

        // test current tag is blank?
    }

    /**
     * @depends testWriteNewTag
     */
    public function testWriteChangeLogNewTagWithNoHistory()
    {
        $commits = [
            "perf: made this better",
            "refactor: also patch a thing ",
            "fix: some sort of patch\n\n- we have additional notes in the body here",
            "feature: initial commit",
        ];

        // @formatter:off
        $repoMock = $this->repoMockBuilder->getMock();
        $infoMock = $this->gitInfoMockBuilder->enableOriginalConstructor()->setConstructorArgs([$repoMock])->getMock();

        $infoMock->expects(self::atLeastOnce())
                 ->method('getGitTags')
                 ->willReturn($commits);

        $infoMock->expects(self::atLeastOnce())
                 ->method('getNewCommits')
                 ->willReturn($commits)
        ;

        // writes the main header once then 2 writes for the new tag name and the commits
        $expectedWrites = 3;
        $splFileObjectMock = $this->splFileObjectMockBuilder->getMock();
        $splFileObjectMock->expects(self::exactly($expectedWrites))
                          ->method('fwrite')
        ;

        // @formatter:on

        $service = new ChangeLogService($infoMock);
        $service->writeChangeLog($splFileObjectMock, '1.0.0');

        // test current tag is blank?
    }


    public function testFilePath()
    {
        $service = $this->getServiceWithInfoMock();

        self::assertSame(ChangeLogService::DEFAULT_FILE_PATH, $service->getChangeLogFilePath());

        $path = '/tmp/';
        $service->setChangeLogFilePath($path);
        self::assertSame($path, $service->getChangeLogFilePath());

        $noTrailingSlash = '/tmp';
        $service->setChangeLogFilePath($noTrailingSlash);
        self::assertSame($path, $service->getChangeLogFilePath());

        $service->setChangeLogFilePath('');
        self::assertSame('', $service->getChangeLogFilePath());
    }

    public function testFileName()
    {
        $service = $this->getServiceWithInfoMock();

        self::assertSame(ChangeLogService::DEFAULT_FILE_NAME, $service->getChangeLogFileName());

        $filename = 'somefile.md';
        $service->setChangeLogFileName($filename);

        self::assertSame($filename, $service->getChangeLogFileName());
    }

    /**
     * @depends testFilePath
     * @depends testFileName
     */
    public function testFullPath()
    {
        $service = $this->getServiceWithInfoMock();

        $defaultFullPath = ChangeLogService::DEFAULT_FILE_PATH . ChangeLogService::DEFAULT_FILE_NAME;

        self::assertSame($defaultFullPath, $service->getFullPath());
    }

    /**
     * @depends testFullPath
     */
    public function testGetSplFileObject()
    {
        $service = $this->getServiceWithInfoMock();
        $service->setChangeLogFilePath('php://');
        $service->setChangeLogFileName('memory');

        $file = $service->getSplFileObject();

        // no need to assert instance type because of return type declaration
        self::assertSame($service->getFullPath(), $file->getPathname());
    }

    public function testGitInformation()
    {
        $infoMock = $this->gitInfoMockBuilder->getMock();
        $service = new ChangeLogService($infoMock);

        self::assertSame($infoMock, $service->getGitInformation());
    }

    /**
     * @depends testFilePath
     */
    public function testSetFilePathNullPassed()
    {
        $service = $this->getServiceWithInfoMock();

        $service->setChangeLogFilePath('/tmp');

        $service->setChangeLogFilePath(null);

        self::assertSame(ChangeLogService::DEFAULT_FILE_PATH, $service->getChangeLogFilePath());
    }

    /**
     * @depends testFileName
     */
    public function testSetFileNameNullPassed()
    {
        $service = $this->getServiceWithInfoMock();

        $service->setChangeLogFileName('kittens.txt');

        $service->setChangeLogFileName(null);

        self::assertSame(ChangeLogService::DEFAULT_FILE_NAME, $service->getChangeLogFileName());
    }

    /**
     * @return ChangeLogService
     */
    private function getServiceWithInfoMock(): ChangeLogService
    {
        $infoMock = $this->gitInfoMockBuilder->getMock();
        $service = new ChangeLogService($infoMock);

        return $service;
    }
}
