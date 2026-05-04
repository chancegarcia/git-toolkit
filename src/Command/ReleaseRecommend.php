<?php

namespace Chance\GitToolkit\Command;

use Chance\GitToolkit\Collector\GitCollector;
use Chance\GitToolkit\GitInformation;
use Chance\GitToolkit\Service\ConventionalCommitParser;
use Chance\GitToolkit\Service\ReleaseRecommender;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'toolkit:release:recommend', description: 'recommend a SemVer release level based on commits', help: 'This command analyzes the commits since the last tag and recommends whether to release a major, minor, or patch version.')]
class ReleaseRecommend
{
    public function __construct(
        private readonly GitInformation $gitInformation,
        private readonly ReleaseRecommender $recommender,
        private readonly ConventionalCommitParser $parser = new ConventionalCommitParser()
    ) {
    }

    public function __invoke(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $newTag = $input->getOption('new-tag') ?? 'upcoming';
        $previousTag = $input->getOption('previous-tag');

        $collector = new GitCollector($this->gitInformation);
        // We only want "what's new" for recommendation
        $rawData = $collector->collect($newTag, $previousTag, false);

        $allCommits = [];
        foreach ($rawData as $tagCommits) {
            foreach ($tagCommits as $commitMsg) {
                $parsed = $this->parser->parse($commitMsg);
                if ($parsed !== null) {
                    $allCommits[] = $parsed;
                }
            }
        }

        if (empty($allCommits)) {
            $output->writeln('<info>No conventional commits found in the specified range.</info>');
            $output->writeln('Recommended release: none');
            $output->writeln('Reason: No release-impacting commits found.');

            return Command::SUCCESS;
        }

        $recommendation = $this->recommender->recommend($allCommits);

        $output->writeln(sprintf('<info>Recommended release: %s</info>', $recommendation->getType()));
        $output->writeln(sprintf('Reason: %s', $recommendation->getReason()));
        $output->writeln(sprintf('Highest-impact commit type found: %s', $recommendation->getHighestImpactType()));
        $output->writeln(
            sprintf('Breaking changes detected: %s', $recommendation->breakingChangesDetected() ? 'yes' : 'no')
        );

        return Command::SUCCESS;
    }

    public function configure(Command $command): void
    {
        $command->addOption('new-tag', null, InputOption::VALUE_REQUIRED, 'temporary label for current HEAD')
                ->addOption('previous-tag', null, InputOption::VALUE_REQUIRED, 'compare against this tag')
        ;
    }
}
