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

namespace Chance\GitToolkit\Service;

use Chance\GitToolkit\GitInformation;

class ChangeLogService
{
    public const DEFAULT_MAIN_HEADER_NAME = 'Projecty McProjectFace';
    public const DEFAULT_FILE_NAME = 'changelog.md';
    public const DEFAULT_FILE_PATH = '';
    /**
     * @var GitInformation
     */
    private $gitInformation;

    /**
     * @var string
     */
    private $changeLogFileName = self::DEFAULT_FILE_NAME;

    /**
     * @var string
     */
    private $changeLogFilePath = self::DEFAULT_FILE_PATH;

    /**
     * @var string
     */
    private $mainHeaderName = self::DEFAULT_MAIN_HEADER_NAME;

    /**
     * ChangeLogService constructor.
     *
     * @param GitInformation $gitInformation
     */
    public function __construct(GitInformation $gitInformation)
    {
        $this->gitInformation = $gitInformation;
    }

    /**
     * @return GitInformation
     */
    public function getGitInformation(): GitInformation
    {
        return $this->gitInformation;
    }

    /**
     * @return string
     */
    public function getChangeLogFileName(): string
    {
        return $this->changeLogFileName;
    }

    /**
     * @param string|null $changeLogFileName
     */
    public function setChangeLogFileName(?string $changeLogFileName): void
    {
        if (is_string($changeLogFileName)) {
            $this->changeLogFileName = $changeLogFileName;
        } else {
            $this->changeLogFileName = self::DEFAULT_FILE_NAME;
        }
    }

    /**
     * @return string
     */
    public function getChangeLogFilePath(): string
    {
        return $this->changeLogFilePath;
    }

    /**
     * @param string|null $changeLogFilePath
     */
    public function setChangeLogFilePath(?string $changeLogFilePath): void
    {
        if (is_string($changeLogFilePath)) {
            $this->changeLogFilePath = $changeLogFilePath;
        } else {
            $this->changeLogFilePath = self::DEFAULT_FILE_PATH;
        }
    }

    /**
     * @return string
     */
    public function getMainHeaderName(): string
    {
        return $this->mainHeaderName;
    }

    /**
     * @param string|null $mainHeaderName
     */
    public function setMainHeaderName(?string $mainHeaderName): void
    {
        if (is_string($mainHeaderName)) {
            $this->mainHeaderName = $mainHeaderName;
        } else {
            $this->mainHeaderName = self::DEFAULT_MAIN_HEADER_NAME;
        }
    }

    /**
     * @param \SplFileObject $file
     * @param string|null $newTag
     *
     * @throws \Cz\Git\GitException
     */
    public function writeChangeLog(\SplFileObject $file, string $newTag = null)
    {
        $tags = $this->gitInformation->getGitTags();
        $tagCount = count($tags);

        $file->fwrite(sprintf("# %s\n\n", $this->mainHeaderName));

        $hasNewTag = false;
        if (is_string($newTag)) {
            $this->writeNewTag($file, $newTag);
            $hasNewTag = true;
        }

        if (!$hasNewTag && 0 === $tagCount) {
            // write current commit as a new tag
            $this->writeNewTag($file, $this->gitInformation->getCurrentCommit());
        }

        if ($tagCount > 0) {
            for ($i = 0; $i < $tagCount; $i++) {
                if ($i + 1 === $tagCount) {
                    [$current] = array_slice($tags, $i, 1);
                    $previous = $this->gitInformation->getFirstCommit();
                } else {
                    [$current, $previous] = array_slice($tags, $i, 2);
                }

                $commits = GitInformation::escapeCommitsForMarkdown(
                    $this->gitInformation->getCommits($previous, $current, true)
                );
                $commitString = implode("\n", $commits);

                if ('' !== $commitString) {
                    $tagName = $current;
                    if ("" === $tagName) {
                        $currentCommit = $this->gitInformation->getCurrentCommit();
                        $tagName = sprintf('empty tag \(latest commit: %s\)', $currentCommit);
                    }
                    $this->writeTag($file, $tagName, $commitString);
                }
            }
        }

        // close file (https://stackoverflow.com/questions/22449822/how-to-close-a-splfileobject-file-handler/22822981)
        $file = null;
    }

    public function getFullPath()
    {
        return $this->changeLogFilePath . $this->changeLogFileName;
    }

    public function getSplFileObject(): \SplFileObject
    {
        $fullPath = $this->getFullPath();

        return new \SplFileObject($fullPath, 'wb+');
    }

    /**
     * @param \SplFileObject $file file resource
     * @param string $newTag
     */
    public function writeNewTag(\SplFileObject $file, string $newTag)
    {
        $latestCommits = GitInformation::escapeCommitsForMarkdown($this->gitInformation->getNewCommits());
        $latestCommitsString = implode("\n", $latestCommits);
        $this->writeTag($file, $newTag, $latestCommitsString);
    }

    /**
     * @param \SplFileObject $file file resource
     * @param string $tagName
     * @param string $commits
     */
    public function writeTag(\SplFileObject $file, string $tagName, string $commits)
    {
        $file->fwrite(sprintf("## %s\n", $tagName));
        $file->fwrite(sprintf("%s\n", $commits));
    }
}
