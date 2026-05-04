<?php

namespace Chance\GitToolkit\Service;

use Chance\GitToolkit\Collector\GitCollector;
use Chance\GitToolkit\Generator\ConventionalCommitGenerator;
use Chance\GitToolkit\Generator\LegacyGenerator;
use Chance\GitToolkit\Generator\GeneratorInterface;
use Chance\GitToolkit\GitInformation;
use Chance\GitToolkit\Renderer\ConventionalMarkdownRenderer;
use Chance\GitToolkit\Renderer\LegacyRenderer;

class GeneratorFactory
{
    public function __construct(
        private readonly ConfigReader $configReader
    ) {
    }

    public function createGenerator(GitInformation $gitInformation, string $mainHeader): GeneratorInterface
    {
        $useConventional = $this->configReader->getBool('CHANGELOG_USE_CONVENTIONAL_COMMITS', true);
        $collector = new GitCollector($gitInformation);

        if ($useConventional) {
            return new ConventionalCommitGenerator(
                $collector, new ConventionalMarkdownRenderer(), $mainHeader
            );
        }

        return new LegacyGenerator(
            $collector, new LegacyRenderer(), $mainHeader
        );
    }
}
