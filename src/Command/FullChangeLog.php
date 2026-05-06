<?php

namespace Chance\ReleaseScribe\Command;

use Chance\ReleaseScribe\Service\ChangeLogService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'changelog',
    description: 'generate full changelog history',
    help: 'This command allows you to generate a full changelog history from your git commit history'
)]
class FullChangeLog
{
    public function __construct(
        private readonly ChangeLog $changeLogCommand
    ) {
    }

    public function __invoke(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        // Force full mode
        $input->setOption('mode', 'full');

        return ($this->changeLogCommand)($input, $output);
    }

    public function configure(Command $command): void
    {
        $this->changeLogCommand->configure($command);
    }
}
