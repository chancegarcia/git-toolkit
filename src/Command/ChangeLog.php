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

use Chance\Version\GitInformation;
use Chance\Version\Service\ChangeLogService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ChangeLog extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'version:changelog';

    /**
     * @var ChangeLogService
     */
    private $changeLogService;

    /**
     * @return ChangeLogService
     */
    public function getChangeLogService(): ChangeLogService
    {
        return $this->changeLogService;
    }

    /**
     * @param ChangeLogService $changeLogService
     */
    public function setChangeLogService(ChangeLogService $changeLogService): void
    {
        $this->changeLogService = $changeLogService;
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('generate changelog from git commit history')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to generate a changelog from your git commit history')
            ->addArgument('header', InputArgument::OPTIONAL, 'main file header in output; default: ' . ChangeLogService::DEFAULT_MAIN_HEADER_NAME)
            ->addOption('new-tag', null, InputOption::VALUE_REQUIRED, 'label the current `HEAD` as NEW-TAG on output')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // todo gather data via questions (project name/main title)
        // $testTags = array_slice(getGitTags(), 0, 5);

        $mainHeaderName = $input->getArgument('header');
        $this->changeLogService->setMainHeaderName($mainHeaderName);


        $newTag = $input->getOption('new-tag');
        $this->changeLogService->writeChangeLog($newTag);


        // return this if there was no problem running the command
        return 0;

        // or return this if some error happened during the execution
        // return 1;
    }

    /**
     * @param \SplFileObject $file file resource
     * @param string $tagName
     * @param string $commits
     */
    private function writeTag(\SplFileObject $file, string $tagName, string $commits)
    {
        $file->fwrite(sprintf("## %s\n", $tagName));
        $file->fwrite(sprintf("%s\n", $commits));
    }

    /**
     * @param \SplFileObject $file file resource
     * @param string $newTag
     */
    private function writeNewTag(\SplFileObject $file, string $newTag)
    {
        $latestCommits = GitInformation::escapeCommitsForMarkdown($this->gitInformation->getNewCommits());
        $latestCommitsString = implode("\n", $latestCommits);
        $this->writeTag($file, $newTag, $latestCommitsString);
    }
}