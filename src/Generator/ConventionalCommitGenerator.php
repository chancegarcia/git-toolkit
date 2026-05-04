<?php

namespace Chance\GitToolkit\Generator;

use Chance\GitToolkit\Collector\CollectorInterface;
use Chance\GitToolkit\Data\ChangeLogData;
use Chance\GitToolkit\Data\ConventionalCommit;
use Chance\GitToolkit\Data\Release;
use Chance\GitToolkit\Data\Section;
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
        $releases = $this->processData($rawData);

        $data = new ChangeLogData($this->mainHeader, $releases);

        $content = $this->renderer->render($data);
        $file->fwrite($content);
    }

    /**
     * @param array<string, array<string>> $rawData
     *
     * @return array<Release>
     */
    public function processData(array $rawData): array
    {
        $releases = [];

        foreach ($rawData as $tag => $commits) {
            $groups = [
                'Breaking Changes' => [
                    'label' => 'Breaking Changes',
                    'type' => null,
                    'items' => [],
                ],
            ];
            foreach ($this->typeLabels as $type => $label) {
                $groups[$type] = [
                    'label' => $label,
                    'type' => $type,
                    'items' => [],
                ];
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
                            $groups['Other'] = [
                                'label' => 'Other',
                                'type' => null,
                                'items' => [],
                            ];
                        }
                        $groups['Other']['items'][] = $commitMsg;
                        trigger_error('Non-conventional commit detected: ' . $commitMsg, E_USER_WARNING);
                    }
                    continue;
                }

                if ($parsed->isBreakingChange()) {
                    $groups['Breaking Changes']['items'][] = $parsed;
                }

                $type = $parsed->getType();
                if (in_array($type, $this->includedTypes) && isset($groups[$type])) {
                    $groups[$type]['items'][] = $parsed;
                }
            }

            $sections = [];
            foreach ($groups as $group) {
                if (!empty($group['items'])) {
                    $sections[] = new Section($group['label'], $group['items'], $group['type']);
                }
            }

            if (!empty($sections)) {
                $releases[] = new Release($tag, $sections);
            }
        }

        return $releases;
    }
}
