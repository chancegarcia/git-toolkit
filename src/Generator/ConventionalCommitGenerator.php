<?php

namespace Chance\GitToolkit\Generator;

use Chance\GitToolkit\Collector\CollectorInterface;
use Chance\GitToolkit\Data\ConventionalCommit;
use Chance\GitToolkit\Renderer\RendererInterface;
use Chance\GitToolkit\Service\ConventionalCommitParser;
use SplFileObject;

class ConventionalCommitGenerator implements GeneratorInterface
{
    private array $typeLabels = [
        'feat' => 'Features',
        'fix' => 'Bug Fixes',
        'perf' => 'Performance Improvements',
        'refactor' => 'Refactoring',
        'docs' => 'Documentation',
        'security' => 'Security',
        'deprecated' => 'Deprecations',
    ];

    private array $includedTypes = [
        'feat',
        'fix',
        'perf',
        'refactor',
        'docs',
        'security',
        'deprecated',
    ];

    public function __construct(
        private readonly CollectorInterface $collector,
        private readonly RendererInterface $renderer,
        private readonly string $mainHeader,
        private readonly ConventionalCommitParser $parser = new ConventionalCommitParser()
    ) {
    }

    public function generate(
        SplFileObject $file,
        ?string $newTag = null,
        ?string $previousTag = null,
        bool $fullHistory = true
    ): void
    {
        $rawData = $this->collector->collect($newTag, $previousTag, $fullHistory);
        $processedData = $this->processData($rawData);

        $content = $this->renderer->render($processedData, $this->mainHeader);
        $file->fwrite($content);
    }

    /**
     * @param array<string, array<string>> $rawData
     *
     * @return array<string, array<string, array<ConventionalCommit|string>>>
     */
    public function processData(array $rawData): array
    {
        $processedData = [];

        foreach ($rawData as $tag => $commits) {
            $groups = [
                'Breaking Changes' => [],
            ];
            foreach ($this->typeLabels as $label) {
                $groups[$label] = [];
            }

            foreach ($commits as $commitMsg) {
                $parsed = $this->parser->parse($commitMsg);
                if ($parsed === null) {
                    $includeNonConventional = filter_var(
                        $_ENV['CHANGELOG_INCLUDE_NON_CONVENTIONAL'] ?? true,
                        FILTER_VALIDATE_BOOLEAN
                    );
                    if ($includeNonConventional) {
                        if (!isset($groups['Other'])) {
                            $groups['Other'] = [];
                        }
                        $groups['Other'][] = $commitMsg;
                        trigger_error('Non-conventional commit detected: ' . $commitMsg, E_USER_WARNING);
                    }
                    continue;
                }

                if ($parsed->isBreakingChange()) {
                    $groups['Breaking Changes'][] = $parsed;
                }

                $type = $parsed->getType();
                if (in_array($type, $this->includedTypes) && isset($this->typeLabels[$type])) {
                    $label = $this->typeLabels[$type];
                    $groups[$label][] = $parsed;
                }
            }

            // Remove empty groups
            $groups = array_filter($groups, fn($group) => !empty($group));

            if (!empty($groups)) {
                $processedData[$tag] = $groups;
            }
        }

        return $processedData;
    }
}
