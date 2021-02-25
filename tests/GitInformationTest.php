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

namespace Chance\GitToolkit\Test;

use Chance\GitToolkit\GitInformation;
use Cz\Git\GitRepository;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use PHPUnit\TestFixture\Mockable;

class GitInformationTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockBuilder|GitRepository
     */
    private $repoMockBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->repoMockBuilder = $this->getMockBuilder(GitRepository::class)->disableOriginalConstructor();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->repoMockBuilder = null;
    }

    public function testGetGitTagsReturnsArray()
    {
        $tags = ['4.1.3', '4.1.2', '4.1.1', '4.1.0',];
        $repoMock = $this->repoMockBuilder->getMock();
        // @formatter:off
        $repoMock->method('execute')
                 ->willReturn($tags)
        ;
        // @formatter:on

        $gitInfo = new GitInformation($repoMock);

        self::assertIsArray($gitInfo->getGitTags());
    }

    public function testGetGitTagsNoTags()
    {
        $expectedTags = [];
        $repoMock = $this->repoMockBuilder->getMock();
        // @formatter:off
        $repoMock->method('execute')
                 ->willReturn($expectedTags)
        ;
        // @formatter:on

        $gitInfo = new GitInformation($repoMock);

        $tags = $gitInfo->getGitTags();

        self::assertCount(0, $tags);
    }

    public function testGetGitTags()
    {
        $expectedTags = ['4.1.3', '4.1.2', '4.1.1', '4.1.0',];
        $repoMock = $this->repoMockBuilder->getMock();
        // @formatter:off
        $repoMock->method('execute')
                 ->willReturn($expectedTags)
        ;
        // @formatter:on

        $gitInfo = new GitInformation($repoMock);

        self::assertEquals($expectedTags, $gitInfo->getGitTags());
    }

    public function testGetFirstCommitSingleRoot()
    {
        $repoMock = $this->repoMockBuilder->getMock();
        $commitId = 'fa6b87bdd2d3a3b1abcc79a50401be3f6b71e713';
        // @formatter:off
        $repoMock->method('execute')
                 ->willReturn([$commitId])
        ;
        // @formatter:on

        $gitInfo = new GitInformation($repoMock);

        self::assertEquals($commitId, $gitInfo->getFirstCommit());
    }

    public function testGetFirstCommitMultpleRoot()
    {
        $commits = [
            '30b96aea5194bac56b26a2ac2952db9b6f0bfbe7',
            '1663cb9e6520e7a4a89443741230bb8809a500c6',
        ];

        $repoMock = $this->repoMockBuilder->getMock();
        // @formatter:off
        $repoMock->method('execute')
                 ->willReturn($commits)
        ;
        // @formatter:on

        $gitInfo = new GitInformation($repoMock);

        self::assertEquals(array_pop($commits), $gitInfo->getFirstCommit());
    }

    public function testGetCommitsWithMerges()
    {
        $repoMock = $this->repoMockBuilder->getMock();
        // @formatter:off
        $repoMock->expects(self::once())
                 ->method('execute')
                 ->with(self::callback(function ($cmdArray) {
                        return !in_array('--no-merges', $cmdArray);
                    }))
                 ->willReturn([])
        ;
        // @formatter:on

        $gitInfo = new GitInformation($repoMock);
        try {
            $gitInfo->getCommits('foo', 'bar');
        } catch (ExpectationFailedException $e) {
            self::fail('"--no-merges" option unexpectedly called for this method');
        }
    }

    public function testGetCommitsNoMerges()
    {
        $repoMock = $this->repoMockBuilder->getMock();
        // @formatter:off
        $repoMock->expects(self::once())
                 ->method('execute')
                 ->with(self::callback(function ($cmdArray) {
                        return in_array('--no-merges', $cmdArray);
                    }))
                 ->willReturn([])
        ;
        // @formatter:on

        $gitInfo = new GitInformation($repoMock);
        try {
            $gitInfo->getCommits('foo', 'bar', true);
        } catch (ExpectationFailedException $e) {
            self::fail('"--no-merges" was not called for this method');
        }
    }

    public function testEscapeCommitsForMarkdown()
    {
        $commitText = 'the following are reserved characters that must be escaped:' . "\n" . '* [ ] ( ) # + !' . "\n" . 'Next we test an empty list item' . "\n" . '-' . "\n" . '- not empty second line';

        $expectedText = 'the following are reserved characters that must be escaped:' . "\n" . '\\* \\[ \\] \( \\) \\# \\+ \\!' . "\n" . 'Next we test an empty list item' . "\n" . '- not empty second line';

        $expectedArray = explode("\n", $expectedText);
        $commitsArray = explode("\n", $commitText);
        self::assertEquals($expectedArray, GitInformation::escapeCommitsForMarkdown($commitsArray));
    }

    /**
     * @depends testGetGitTags
     */
    public function testGetLatestReleaseTag()
    {
        $tags = ['4.1.3', '4.1.2', '4.1.1', '4.1.0',];
        $repoMock = $this->repoMockBuilder->getMock();
        // @formatter:off
        $repoMock->method('execute')
                 ->willReturn($tags)
        ;
        // @formatter:on

        $gitInfo = new GitInformation($repoMock);

        self::assertEquals(array_shift($tags), $gitInfo->getLatestReleaseTag());
    }

    /**
     * @depends testGetGitTagsNoTags
     */
    public function testGetLatestReleaseTagNoTags()
    {
        $tags = [];
        $repoMock = $this->repoMockBuilder->getMock();
        // @formatter:off
        $repoMock->method('execute')
                 ->willReturn($tags)
        ;
        // @formatter:on

        $gitInfo = new GitInformation($repoMock);

        self::assertNull($gitInfo->getLatestReleaseTag());
    }

    /**
     * @depends testGetFirstCommitSingleRoot
     * @depends testGetFirstCommitMultpleRoot
     * @depends testGetLatestReleaseTag
     * @depends testGetLatestReleaseTagNoTags
     * @depends testGetCommitsNoMerges
     * @depends testGetCommitsWithMerges
     */
    public function testNewCommitsNoTagsCallsFirstCommit()
    {
        $repoMock = $this->getMockBuilder(GitRepository::class)->disableOriginalConstructor()->getMock();

        // mock for previous tag value and for getCommits value
        // @formatter:off
        $repoMock->method('getLastCommitId')
                 ->willReturn('bar')
        ;

        $infoMock = $this->getMockBuilder(GitInformation::class)->setConstructorArgs([$repoMock])->onlyMethods(['getLatestReleaseTag', 'getFirstCommit', 'getGitTags', 'getCurrentCommit', 'getCommits'])->getMock();
        $infoMock->expects(self::atLeastOnce())
                 ->method('getFirstCommit')
                 ->willReturn('baz')
        ;

        $infoMock->expects(self::once())
                 ->method('getLatestReleaseTag')
                 ->willReturn(null)
        ;

        $infoMock->method('getGitTags')
                 ->willReturn([])
        ;
        // @formatter:on

        $infoMock->getNewCommits();
    }

    /**
     * depends testNewCommitsNoTagsCallsFirstCommit
     */
    public function testNewCommits()
    {
        $commits = ['- msg 1', '- msg 2'];

        $repoMock = $this->repoMockBuilder->getMock();

        // @formatter:off
        // mock for previous tag value and for getCommits value
        $repoMock->method('getLastCommitId')
                 ->willReturn('bar')
        ;

        $infoMock = $this->getMockBuilder(GitInformation::class)->setConstructorArgs([$repoMock])->onlyMethods(['getLatestReleaseTag', 'getFirstCommit', 'getGitTags', 'getCurrentCommit', 'getCommits'])->getMock();
        $infoMock->expects(self::never())
                 ->method('getFirstCommit')
        ;

        $infoMock->expects(self::once())
                 ->method('getLatestReleaseTag')
                 ->willReturn('foo1')
        ;

        $infoMock->method('getGitTags')
                 ->willReturn(['bar1', 'bar2'])
        ;

        $infoMock->expects(self::atLeastOnce())
                 ->method('getCommits')
                 ->willReturn($commits)
        ;
        // @formatter:on

        $newCommits = $infoMock->getNewCommits();

        self::assertSame($commits, $newCommits);
    }

    public function testGitRepo()
    {
        $repoMock = $this->repoMockBuilder->getMock();

        $info = new GitInformation($repoMock);

        self::assertEquals($repoMock, $info->getGitRepo());
    }

    // test get current commit
    // mock expect repo getLastCommitId is call
    public function testCurrentCommit()
    {
        $repoMock = $this->repoMockBuilder->getMock();

        // @formatter:off
        $repoMock->expects(self::once())
                 ->method('getLastCommitId')
                 ->willReturn('foo')
        ;
        // @formatter:on

        $info = new GitInformation($repoMock);
        $info->getCurrentCommit();
    }
}