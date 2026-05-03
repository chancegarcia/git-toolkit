<?php

/**
 * @package
 * @subpackage
 * @author      Chance Garcia <chance@garcia.codes>
 * @copyright   (C)Copyright 2013-2026 Chance Garcia, chancegarcia.com
 *
 *    The MIT License (MIT)
 *
 * Copyright (c) 2013-2026 Chance Garcia
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

namespace Chance\GitToolkit;

use CzProject\GitPhp\GitException;
use CzProject\GitPhp\GitRepository;

class GitInformation
{
    public function __construct(
        private readonly GitRepository $gitRepo
    ) {
    }

    public function getGitRepo(): GitRepository
    {
        return $this->gitRepo;
    }

    public function getGitTags(): array
    {
        /** @var array<string> $result */
        $result = $this->gitRepo->execute(['tag', '-l', '--sort=-v:refname']);

        return $result;
    }

    public function getFirstCommit(): string
    {
        /** @var array<string> $commits */
        $commits = $this->gitRepo->execute(['rev-list', '--max-parents=0', 'HEAD']);

        return trim((string)array_pop($commits));
    }

    /**
     * @return string id of current commit
     *
     * @throws GitException
     */
    public function getCurrentCommit(): string
    {
        // executing `git rev-parse HEAD` would also work
        return $this->gitRepo->getLastCommitId();
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
        $result = $this->gitRepo->execute($commandArray);

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
        $result = $this->gitRepo->execute($commandArray);

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
