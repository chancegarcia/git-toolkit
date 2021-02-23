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

namespace Chance\Version;

use Cz\Git\GitException;
use Cz\Git\GitRepository;

class GitInformation
{
    public const MARKDOWN_RESERVED_CHARACTERS = [
        '*',
        // '_',
        // '{',
        // '}',
        '[',
        ']',
        '(',
        ')',
        '#',
        '+',
        // '-',
        // '.',
        '!',
    ];

    /**
     * @var GitRepository
     */
    private $gitRepo;

    /**
     * GitInformation constructor.
     *
     * @param GitRepository $gitRepo
     */
    public function __construct(GitRepository $gitRepo)
    {
        $this->gitRepo = $gitRepo;
    }

    /**
     * @return GitRepository
     */
    public function getGitRepo(): GitRepository
    {
        return $this->gitRepo;
    }

    /**
     * @param GitRepository $gitRepo
     */
    public function setGitRepo(GitRepository $gitRepo): void
    {
        $this->gitRepo = $gitRepo;
    }

    /**
     * @return array|string[] list of current tags
     */
    public function getGitTags(): array
    {
        return $this->gitRepo->execute(['tag', '-l', '--sort=-v:refname']);
    }

    /**
     * @return string id of first commit
     */
    public function getFirstCommit() : string
    {
        $commits = $this->gitRepo->execute(['rev-list', '--max-parents=0', 'HEAD']);

        return trim(array_pop($commits));
    }

    /**
     * @return string|null id of current commit
     *
     * @throws GitException
     */
    public function getCurrentCommit() : string
    {
        // executing `git rev-parse HEAD` would also work
        return $this->gitRepo->getLastCommitId();
    }

    /**
     * @param string $previous
     * @param string $current
     * @param bool $noMerges add `--no-merges` option to log call
     *
     * @return array
     */
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

        return $this->gitRepo->execute($commandArray);
    }

    /**
     * @param array|string[] $commits
     *
     * @return array|string[]
     * @todo: this probably belongs in a different class
     */
    public static function escapeCommitsForMarkdown(array $commits): array
    {
        foreach ($commits as $i => $commitMsg) {
            // remove empty list items
            if (preg_match('/^-\s*$/', $commitMsg)) {
                unset($commits[$i]);
            }
        }

        $stringCommits = implode("\n", $commits);

        foreach (self::MARKDOWN_RESERVED_CHARACTERS as $reservedChar) {
            $stringCommits = str_replace($reservedChar, sprintf('\%s', $reservedChar), $stringCommits);
        }

        return explode("\n", $stringCommits);
    }

    /**
     * @return array
     */
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

    /**
     * @return string|null
     */
    public function getLatestReleaseTag() : ?string
    {
        $tags = $this->getGitTags();

        if (count($tags) > 0) {
            return array_shift($tags);
        }

        return null;
    }

}