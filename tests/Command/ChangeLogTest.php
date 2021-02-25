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

namespace Chance\GitToolkit\Test\Command;

use Chance\GitToolkit\Command\ChangeLog;
use Chance\GitToolkit\GitInformation;
use Chance\GitToolkit\Service\ChangeLogService;
use Composer\Console\Application;
use Cz\Git\GitException;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ChangeLogTest extends TestCase
{
    /**
     * @var MockBuilder|ChangeLogService
     */
    private $serviceMockBuilder;
    /**
     * @var \SplFileObject|MockBuilder
     */
    private $splFileObjectMockBuilder;

    public function testExecute()
    {
        $fileMock = $this->splFileObjectMockBuilder->getMock();

        // @formatter:off
        /**
         * @var ChangeLogService|MockObject $changeLogServiceMock
         */
        $changeLogServiceMock = $this->serviceMockBuilder->getMock();

        $changeLogServiceMock->expects(self::once())
                             ->method('setMainHeaderName')
        ;
        $changeLogServiceMock->expects(self::once())
                             ->method('getSplFileObject')
                             ->willReturn($fileMock)
        ;
        $changeLogServiceMock->expects(self::once())
                             ->method('writeChangeLog')
                             ->with(
                                 self::isInstanceOf(\SplFileObject::class),
                                 self::isNull()
                             )
        ;

        // @formatter:on

        $changeLogCommand = new ChangeLog();
        $changeLogCommand->setChangeLogService($changeLogServiceMock);

        self::assertSame($changeLogServiceMock, $changeLogCommand->getChangeLogService());

        $commandTester = new CommandTester($changeLogCommand);
        $commandTester->execute([]);
    }

    /**
     * @depends testExecute
     */
    public function testExecuteSuccessMessage()
    {
        $fileMock = $this->splFileObjectMockBuilder->getMock();

        // @formatter:off
        /**
         * @var ChangeLogService|MockObject $changeLogServiceMock
         */
        $changeLogServiceMock = $this->serviceMockBuilder->getMock();

        $changeLogServiceMock->expects(self::once())
                             ->method('getSplFileObject')
                             ->willReturn($fileMock)
        ;

        // @formatter:on

        $changeLogCommand = new ChangeLog();
        $changeLogCommand->setChangeLogService($changeLogServiceMock);

        $commandTester = new CommandTester($changeLogCommand);
        $commandTester->execute([]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        self::assertEquals(sprintf("success: file '%s' has been created\n", $changeLogServiceMock->getFullPath()), $output);
    }

    /**
     * @depends testExecute
     */
    public function testExecuteWithHeaderArgument()
    {
        $headerValue = 'Toolkit Test';

        // @formatter:off
        $fileMock = $this->splFileObjectMockBuilder->getMock();
        $changeLogServiceMock = $this->serviceMockBuilder->getMock();

        $changeLogServiceMock->expects(self::once())
                             ->method('getSplFileObject')
                             ->willReturn($fileMock)
        ;

        $changeLogServiceMock->expects(self::once())
                             ->method('setMainHeaderName')
                             ->with(self::equalTo($headerValue))
        ;

        $changeLogCommand = new ChangeLog();
        $changeLogCommand->setChangeLogService($changeLogServiceMock);

        $commandTester = new CommandTester($changeLogCommand);
        $commandTester->execute([
            'header' => $headerValue,
        ]);

        // @formatter:on
    }

    /**
     * @depends testExecute
     */
    public function testExecuteWithNewTagOption()
    {
        $tagName = '0.3.1';

        // @formatter:off
        $fileMock = $this->splFileObjectMockBuilder->getMock();
        $changeLogServiceMock = $this->serviceMockBuilder->getMock();

        $changeLogServiceMock->expects(self::once())
                             ->method('getSplFileObject')
                             ->willReturn($fileMock)
        ;

        $changeLogServiceMock->expects(self::once())
                             ->method('writeChangeLog')
                             ->with(
                                 self::isInstanceOf(\SplFileObject::class),
                                 self::equalTo($tagName)
                             )
        ;


        $changeLogCommand = new ChangeLog();
        $changeLogCommand->setChangeLogService($changeLogServiceMock);

        $commandTester = new CommandTester($changeLogCommand);
        $commandTester->execute([
            '--new-tag' => $tagName,
        ]);

        // @formatter:on
    }

    /**
     * @depends testExecute
     */
    public function testExecuteWithGitException()
    {
        $fileMock = $this->splFileObjectMockBuilder->getMock();

        // @formatter:off
        /**
         * @var ChangeLogService|MockObject $changeLogServiceMock
         */
        $changeLogServiceMock = $this->serviceMockBuilder->getMock();

        $changeLogServiceMock->expects(self::once())
                             ->method('getSplFileObject')
                             ->willReturn($fileMock)
        ;
        $changeLogServiceMock->expects(self::once())
                             ->method('writeChangeLog')
                             ->willThrowException(new GitException('some git error happened'))
        ;

        // @formatter:on

        $changeLogCommand = new ChangeLog();
        $changeLogCommand->setChangeLogService($changeLogServiceMock);

        self::assertSame($changeLogServiceMock, $changeLogCommand->getChangeLogService());

        $commandTester = new CommandTester($changeLogCommand);
        $commandTester->execute([]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        self::assertStringContainsString('error:', $output);
        self::assertStringContainsString('error message:', $output);
    }

    protected function setUp(): void
    {
        parent::setUp();

        // @formatter:on
        $this->splFileObjectMockBuilder = $this->getMockBuilder(\SplFileObject::class)
                                               ->setConstructorArgs(['php://memory'])
        ;

        $this->serviceMockBuilder = $this->getMockBuilder(ChangeLogService::class)->disableOriginalConstructor();

        // @formatter:off
    }

    protected function tearDown() : void
    {
        $this->serviceMockBuilder = null;
        $this->splFileObjectMockBuilder = null;
    }
}
