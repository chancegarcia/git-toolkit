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
     * @return array|string[] list of current tags
     */
    public function getGitTags() : array
    {
        $tagString = shell_exec('git tag -l --sort=-v:refname');

        return explode("\n", trim($tagString));
    }

    /**
     * @return string id of first commit
     */
    public function getFirstCommit() : string
    {
        $shellOutput = shell_exec('git rev-list --max-parents=0 HEAD');

        $commits = explode("\n", $shellOutput);

        if (count($commits) > 1) {
            return trim(array_pop($commits));
        }

        return trim($shellOutput);
    }

    /**
     * @return string id of current commit
     */
    public function getCurrentCommit() : string
    {
        return trim(shell_exec('git rev-parse HEAD'));
    }

    /**
     * @param string $previous
     * @param string $current
     *
     * @return string
     */
    public function getCommits(string $previous, string $current) : string
    {
        $commits = $this->getCommitBodies($previous, $current, true);

        if (!is_string($commits)) {
            $commits = $this->getCommitBodies($previous, $current);
        }

        return is_string($commits) ? $commits : sprintf("- no changes from version %s to %s\n", $previous, $current);

    }

    /**
     * @param string $previous
     * @param string $current
     * @param bool $noMerges add `--no-merges` option to log call
     *
     * @return string
     */
    public function getCommitBodies(string $previous, string $current, bool $noMerges = false)
    {
        $mergeOption = ($noMerges) ? ' --no-merges' : '';
        $cmd = sprintf('git log --format="%%B"%s %s..%s', $mergeOption, $previous, $current);
        $commits = shell_exec($cmd);

        return is_string($commits) ? $commits : sprintf("- no changes from version %s to %s\n", $previous, $current);
    }

    /**
     * @param string $commits
     *
     * @return string
     */
    public function escapeCommitsForMarkdown(string $commits): string
    {
        foreach (self::MARKDOWN_RESERVED_CHARACTERS as $reservedChar) {
            $commits = str_replace($reservedChar, sprintf('\%s', $reservedChar), $commits);
        }

        $arr = explode("\n", $commits);

        foreach ($arr as $i => $commitMsg) {
            // remove empty list items
            if (preg_match('/^-\s*$/', $commitMsg)) {
                unset($arr[$i]);
            }
        }

        return implode("\n", $arr);
    }

    /**
     * @return string
     */
    public function getNewCommits() : string
    {
        return $this->getCommitBodies($this->getLatestReleaseTag(), $this->getCurrentCommit(), true);
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