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

namespace Chance\GitToolkit\Command;

use Chance\GitToolkit\Service\ChangeLogService;
use CzProject\GitPhp\GitException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'toolkit:changelog',
    description: 'generate changelog from git commit history'
)]
class ChangeLog extends Command
{
    private ChangeLogService $changeLogService;

    public function getChangeLogService(): ChangeLogService
    {
        return $this->changeLogService;
    }

    public function setChangeLogService(ChangeLogService $changeLogService): void
    {
        $this->changeLogService = $changeLogService;
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to generate a changelog from your git commit history')
            ->addArgument(
                'header',
                InputArgument::OPTIONAL,
                'main file header in output; default: ' . ChangeLogService::DEFAULT_MAIN_HEADER_NAME
            )
            ->addOption('new-tag', null, InputOption::VALUE_REQUIRED, 'label the current `HEAD` as NEW-TAG on output')
            ->addOption(
                'output-dir',
                null,
                InputOption::VALUE_REQUIRED,
                'Write changelog to this directory. default is the value set in the change log service'
            )
            ->addOption(
                'filename',
                null,
                InputOption::VALUE_REQUIRED,
                'Write changelog to this filename. default is the value set in the change log service'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $mainHeaderName = $input->getArgument('header');
        if (is_string($mainHeaderName)) {
            $this->changeLogService->setMainHeaderName($mainHeaderName);
        }

        $newTag = $input->getOption('new-tag');
        $filePath = $input->getOption('output-dir');
        if (is_string($filePath)) {
            $this->changeLogService->setChangeLogFilePath($filePath);
        }

        $fileName = $input->getOption('filename');
        if (is_string($fileName)) {
            $this->changeLogService->setChangeLogFileName($fileName);
        }

        try {
            $file = $this->changeLogService->getSplFileObject();
            $this->changeLogService->writeChangeLog($file, is_string($newTag) ? $newTag : null);
            $output->writeln(sprintf("success: file '%s' has been created", $this->changeLogService->getFullPath()));

            return Command::SUCCESS;
        } catch (GitException $e) {
            $output->writeln(
                sprintf(
                    'error: file "%s" was not written or maybe partially written.',
                    $this->changeLogService->getFullPath()
                )
            );
            $output->writeln(sprintf('error message: %s (line: %s)', $e->getMessage(), $e->getLine()));

            return Command::FAILURE;
        }
    }
}
