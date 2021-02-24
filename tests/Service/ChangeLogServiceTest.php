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

namespace Chance\Version\Test\Service;

use Chance\Version\GitInformation;
use Chance\Version\Service\ChangeLogService;
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

    public function setUp(): void
    {
        parent::setUp();

        // @formatter:off
        $this->splFileObjectMockBuilder = $this->getMockBuilder(\SplFileObject::class)->setConstructorArgs(['php://memory']);

        $this->gitInfoMockBuilder = $this->getMockBuilder(GitInformation::class)->disableOriginalConstructor();
        // @formatter:on
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->splFileObjectMockBuilder = null;
    }

    public function testMainHeaderName()
    {
        $infoMock = $this->gitInfoMockBuilder->getMock();
        $service = new ChangeLogService($infoMock);
        $service->setMainHeaderName('foo');

        self::assertSame('foo', $service->getMainHeaderName());
    }

    /**
     * @depends testMainHeaderName
     */
    public function testSetMainHeaderNullParamaterPassed()
    {
        $infoMock = $this->gitInfoMockBuilder->getMock();
        $service = new ChangeLogService($infoMock);
        $service->setMainHeaderName('foo');

        // this will make sure we do not have the default name. so we can test changing back to the default;
        self::assertSame('foo', $service->getMainHeaderName());
        // no need to test for `foo` value being set correctly because of test dependency for this method already confirming that it will set as expected

        $service->setMainHeaderName(null);

        try {
            self::assertSame(ChangeLogService::DEFAULT_MAIN_HEADER_NAME, $service->getMainHeaderName());
        } catch (ExpectationFailedException $e) {
            $msg = sprintf('main header name not set to "%s" when passing null explictly to %s::setMainHeaderName()', ChangeLogService::DEFAULT_MAIN_HEADER_NAME, ChangeLogService::class);
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

        $infoMock = $this->gitInfoMockBuilder->getMock();

        $service = new ChangeLogService($infoMock);

        try {
            $service->writeTag($fileMock, $tagName, $commits);
        } catch (ExpectationFailedException $e) {
            self::fail($e->getMessage());
        }

    }

    /**
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

    public function testWriteChangeLog()
    {
        // test/mock info::getGitTags called
        // test writeNewTag called
        // test writeNewTag not called
        // test/mock getSplFileObject
        // test getFirstCommit called
        // test/mock writeTag called
        // test current tag is blank?
    }
}
