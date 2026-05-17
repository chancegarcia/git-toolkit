<?php

namespace Chance\ReleaseScribe;

use Chance\ReleaseScribe\Service\GitRepositoryFactory;
use CzProject\GitPhp\GitException;
use CzProject\GitPhp\GitRepository;

class GitInformation
{
    private ?GitRepository $gitRepo = null;

    public function __construct(
        private readonly GitRepositoryFactory $factory
    ) {
    }

    public function getGitRepo(): GitRepository
    {
        if ($this->gitRepo === null) {
            $this->gitRepo = $this->factory->create();
        }

        return $this->gitRepo;
    }

    public function getGitTags(): array
    {
        /** @var array<string> $result */
        $result = $this->getGitRepo()->execute(['tag', '-l', '--sort=-v:refname']);

        return $result;
    }

    public function getFirstCommit(): string
    {
        /** @var array<string> $commits */
        $commits = $this->getGitRepo()->execute(['rev-list', '--max-parents=0', 'HEAD']);

        return trim((string)array_pop($commits));
    }

    /**
     * @throws GitException
     * @return string id of current commit
     */
    public function getCurrentCommit(): string
    {
        // executing `git rev-parse HEAD` would also work
        return $this->getGitRepo()->getLastCommitId();
    }

    public function getCommits(string $previous, string $current, bool $noMerges = false): array
    {
        $range = sprintf('%s..%s', $previous, $current);
        $commandArray = [
            'log',
            '--format=%B',
            $range,
        ];

        if ($noMerges) {
            $commandArray[] = '--no-merges';
        }

        /** @var array<string> $result */
        $result = $this->getGitRepo()->execute($commandArray);

        return $result;
    }

    public function getCommitRange(string $previous, string $current, bool $noMerges = false): array
    {
        return $this->getCommits($previous, $current, $noMerges);
    }

    public function getCommitsForTag(string $tag, bool $noMerges = false): array
    {
        $commandArray = [
            'log',
            '--format=%B',
            $tag,
        ];

        if ($noMerges) {
            $commandArray[] = '--no-merges';
        }

        /** @var array<string> $result */
        $result = $this->getGitRepo()->execute($commandArray);

        return $result;
    }

    public function getNewCommits(): array
    {
        $latestReleaseTag = $this->getLatestReleaseTag();
        if (null === $latestReleaseTag) {
            $previous = $this->getFirstCommit();
        } else {
            $previous = $latestReleaseTag;
        }

        return $this->getCommits($previous, $this->getCurrentCommit(), true);
    }

    public function getLatestReleaseTag(): ?string
    {
        $tags = $this->getGitTags();

        if (count($tags) > 0) {
            return array_shift($tags);
        }

        return null;
    }
}
