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

#[AsCommand(name: 'toolkit:init', description: 'initialize changelog for the project', help: 'This command allows you to initialize a changelog. It will generate a complete history if tags exist, or an initial release if no tags exist.', aliases: ['toolkit:initialize'])]
class Init
{
    public function __construct(
        private ChangeLogService $changeLogService
    ) {
    }

    public function __invoke(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $header = $input->getArgument('header');
        $initialVersion = $input->getOption('initial-version');
        $outputDir = $input->getOption('output-dir');
        $filename = $input->getOption('filename');

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

            $gitInformation = $this->changeLogService->getGitInformation();
            $tags = $gitInformation->getGitTags();

            $file = $this->changeLogService->getSplFileObject();

            if (empty($tags)) {
                // Initial release mode
                $version = is_string($initialVersion) ? $initialVersion : 'v1.0.0';
                $this->changeLogService->writeChangeLog($file, $version);
            } else {
                // Complete history mode
                // GitCollector::collect already collects everything by default
                $this->changeLogService->writeChangeLog($file);
            }

            $output->writeln(
                sprintf("success: file '%s' has been initialized", $this->changeLogService->getFullPath())
            );

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
        if ($e instanceof GitException) {
            $output->writeln(
                '<error>Git error: The configured repository path may not be a valid Git repository or was not found.</error>'
            );
            $output->writeln(sprintf('<error>Details: %s</error>', $e->getMessage()));

            return;
        }

        $message = $customMessage ?? sprintf(
            'error: file "%s" was not written or maybe partially written.',
            $this->changeLogService->getFullPath()
        );
        $output->writeln(sprintf('<error>%s</error>', $message));
        $output->writeln(sprintf('<error>error message: %s (line: %s)</error>', $e->getMessage(), $e->getLine()));
    }

    public function configure(Command $command): void
    {
        $command->addArgument(
                'header',
                InputArgument::OPTIONAL,
                'main file header in output; default: ' . ChangeLogService::DEFAULT_MAIN_HEADER_NAME
            )->addOption(
                'initial-version',
                'iv',
                InputOption::VALUE_REQUIRED,
                'initial version to use if no tags exist',
                'v1.0.0'
            )->addOption(
                'output-dir',
                null,
                InputOption::VALUE_REQUIRED,
                'Write changelog to this directory. default is the value set in the change log service'
            )->addOption(
                'filename',
                null,
                InputOption::VALUE_REQUIRED,
                'Write changelog to this filename. default is the value set in the change log service'
            )
        ;
    }
}
