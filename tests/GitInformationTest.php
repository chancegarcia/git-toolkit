<?php

namespace Chance\ReleaseScribe\Test;

use Chance\ReleaseScribe\GitInformation;
use Chance\ReleaseScribe\Service\GitRepositoryFactory;
use CzProject\GitPhp\GitRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GitInformationTest extends TestCase
{
    private MockObject $gitRepoMock;
    private MockObject $factoryMock;
    private GitInformation $gitInformation;

    protected function setUp(): void
    {
        $this->gitRepoMock = $this->createMock(GitRepository::class);
        $this->factoryMock = $this->createMock(GitRepositoryFactory::class);
        $this->factoryMock->method('create')->willReturn($this->gitRepoMock);
        $this->gitInformation = new GitInformation($this->factoryMock);
    }

    public function testGetGitTags(): void
    {
        $this->gitRepoMock->expects(self::once())
            ->method('execute')
            ->with(['tag', '-l', '--sort=-v:refname'])
            ->willReturn(['v1.0.0', 'v0.9.0']);

        $tags = $this->gitInformation->getGitTags();
        self::assertSame(['v1.0.0', 'v0.9.0'], $tags);
    }

    public function testGetCurrentCommit(): void
    {
        $this->gitRepoMock->expects(self::once())
            ->method('getLastCommitId')
            ->willReturn('abc1234');

        self::assertSame('abc1234', $this->gitInformation->getCurrentCommit());
    }

    public function testGetFirstCommit(): void
    {
        $this->gitRepoMock->expects(self::once())
            ->method('execute')
            ->with(['rev-list', '--max-parents=0', 'HEAD'])
            ->willReturn(['root-hash']);

        self::assertSame('root-hash', $this->gitInformation->getFirstCommit());
    }

    public function testGetCommitRange(): void
    {
        $this->gitRepoMock->expects(self::once())
            ->method('execute')
            ->with(['log', '--format=%B', 'v1.0.0..v1.1.0'])
            ->willReturn(['hash1 commit message 1', 'hash2 commit message 2']);

        $commits = $this->gitInformation->getCommitRange('v1.0.0', 'v1.1.0');
        self::assertSame(['hash1 commit message 1', 'hash2 commit message 2'], $commits);
    }

    public function testGetGitRepo(): void
    {
        self::assertSame($this->gitRepoMock, $this->gitInformation->getGitRepo());
    }
}
