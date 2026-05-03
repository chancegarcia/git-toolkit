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
use Symfony\Component\Console\Exception\RuntimeException;

#[AsCommand(
    name: 'toolkit:changelog',
    description: 'generate changelog from git commit history',
    help: 'This command allows you to generate a changelog from your git commit history'
)]
class ChangeLog
{
    public function __construct(
        private ChangeLogService $changeLogService
    )
    {
    }

    public function getChangeLogService(): ChangeLogService
    {
        return $this->changeLogService;
    }

    public function setChangeLogService(ChangeLogService $changeLogService): void
    {
        $this->changeLogService = $changeLogService;
    }

    public function __invoke(
        InputInterface $input,
        OutputInterface $output,
    ): int
    {
        $header = $input->getArgument('header');
        $newTag = $input->getOption('new-tag');
        $previousTag = $input->getOption('previous-tag');
        $outputDir = $input->getOption('output-dir');
        $filename = $input->getOption('filename');

        if (is_string($previousTag) && $newTag === null) {
            $output->writeln('<error>error: --previous-tag requires --new-tag because the new tag is used as the changelog heading.</error>');
            return Command::FAILURE;
        }

        try {
            if (is_string($header)) {
                $this->changeLogService->setMainHeaderName($header);
            }

            if (is_string($outputDir)) {
                $this->changeLogService->setChangeLogFilePath($outputDir);
            }

            if (is_string($filename)) {
                $this->changeLogService->setChangeLogFileName($filename);
            }

            $file = $this->changeLogService->getSplFileObject();
            $this->changeLogService->writeChangeLog($file, is_string($newTag) ? $newTag : null, is_string($previousTag) ? $previousTag : null);
            $output->writeln(sprintf("success: file '%s' has been created", $this->changeLogService->getFullPath()));

            return Command::SUCCESS;
        } catch (GitException|RuntimeException|\RuntimeException $e) {
            $this->renderError($output, $e);

            return Command::FAILURE;
        } catch (\Throwable $e) {
            $this->renderError($output, $e, 'An unexpected error occurred.');

            return Command::FAILURE;
        }
    }

    private function renderError(OutputInterface $output, \Throwable $e, ?string $customMessage = null): void
    {
        $message = $customMessage ?? sprintf('error: file "%s" was not written or maybe partially written.', $this->changeLogService->getFullPath());
        $output->writeln(sprintf('<error>%s</error>', $message));
        $output->writeln(sprintf('<error>error message: %s (line: %s)</error>', $e->getMessage(), $e->getLine()));
    }

    public function configure(Command $command): void
    {
        $command
            ->addArgument(
                'header',
                InputArgument::OPTIONAL,
                'main file header in output; default: ' . ChangeLogService::DEFAULT_MAIN_HEADER_NAME
            )
            ->addOption('new-tag', null, InputOption::VALUE_REQUIRED, 'label the current `HEAD` as NEW-TAG on output')
            ->addOption('previous-tag', null, InputOption::VALUE_REQUIRED, 'explicitly compare the upcoming release against this previous tag')
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
}
