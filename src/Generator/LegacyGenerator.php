<?php

namespace Chance\GitToolkit\Generator;

use Chance\GitToolkit\Collector\CollectorInterface;
use Chance\GitToolkit\Renderer\RendererInterface;
use SplFileObject;

class LegacyGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly CollectorInterface $collector,
        private readonly RendererInterface $renderer,
        private readonly string $mainHeader
    ) {
    }

    public function generate(
        SplFileObject $file,
        ?string $newTag = null,
        ?string $previousTag = null,
        bool $fullHistory = true
    ): void
    {
        $data = $this->collector->collect($newTag, $previousTag, $fullHistory);
        $processedData = $this->processData($data);

        if ($this->renderer instanceof \Chance\GitToolkit\Renderer\LegacyRenderer || $this->renderer instanceof \Chance\GitToolkit\Renderer\ConventionalMarkdownRenderer) {
            $content = $this->renderer->render($processedData, $this->mainHeader);
        } else {
            // Attempt to wrap in ChangeLogData for modern renderers
            $releases = [];
            foreach ($processedData as $tag => $commits) {
                $sections = [new \Chance\GitToolkit\Data\Section('Commits', (array)$commits)];
                $releases[] = new \Chance\GitToolkit\Data\Release($tag, $sections);
            }
            $changeLogData = new \Chance\GitToolkit\Data\ChangeLogData($this->mainHeader, $releases);
            $content = $this->renderer->render($changeLogData);
        }

        $file->fwrite($content);
    }

    public function processData(array $rawData): array
    {
        return $rawData;
    }
}
