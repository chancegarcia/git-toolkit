<?php

namespace Chance\ReleaseScribe\Service;

use Chance\ReleaseScribe\Collector\GitCollector;
use Chance\ReleaseScribe\Generator\ConventionalCommitGenerator;
use Chance\ReleaseScribe\Generator\LegacyGenerator;
use Chance\ReleaseScribe\Generator\GeneratorInterface;
use Chance\ReleaseScribe\GitInformation;
use Chance\ReleaseScribe\Renderer\ConventionalMarkdownRenderer;
use Chance\ReleaseScribe\Renderer\LegacyRenderer;

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
                $collector,
                new ConventionalMarkdownRenderer(),
                $mainHeader
            );
        }

        return new LegacyGenerator(
            $collector,
            new LegacyRenderer(),
            $mainHeader
        );
    }
}
