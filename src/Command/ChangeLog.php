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

namespace Chance\Version\Command;

use Chance\Version\GitLogUtil;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChangeLog extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'version:changelog';

    /**
     * @var GitLogUtil
     */
    private $gitLogUtil;

    private $changeLogFileName;

    private $changeLogFilePath;

    private $mainHeaderName;

    /**
     * @return GitLogUtil
     */
    public function getGitLogUtil(): GitLogUtil
    {
        return $this->gitLogUtil;
    }

    /**
     * @param GitLogUtil $gitLogUtil
     */
    public function setGitLogUtil(GitLogUtil $gitLogUtil): void
    {
        $this->gitLogUtil = $gitLogUtil;
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('generate changelog from git commit history')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to generate a changelog from your git commit history')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // $testTags = array_slice(getGitTags(), 0, 5);
        $tags = $this->gitLogUtil->getGitTags();
        $file = fopen('changelog.md', 'wb+');

        fwrite($file, "# Core Bundle\n\n");

        for ($i = 0, $iMax = count($tags); $i < $iMax; $i++) {
            if ($i + 1 === $iMax) {
                [$current] = array_slice($tags, $i, 1);
                $previous = $this->gitLogUtil->getFirstCommit();
            } else {
                [$current, $previous] = array_slice($tags, $i, 2);
            }

            $commits = $this->gitLogUtil->escapeCommits($this->gitLogUtil->getCommits($previous, $current));

            $tagName = $current;
            if ("" === $tagName) {
                $currentCommit = $this->gitLogUtil->getCurrentCommit();
                $tagName = sprintf('empty tag \(latest commit: %s\)', $currentCommit);
            }

            fwrite($file, sprintf("## %s\n", $tagName));
            fwrite($file, sprintf("%s\n", $commits));
        }

        fclose($file);

        // return this if there was no problem running the command
        return 0;

        // or return this if some error happened during the execution
        // return 1;
    }
}