<?php

namespace Chance\ReleaseScribe\Generator;

use Chance\ReleaseScribe\Collector\CollectorInterface;
use Chance\ReleaseScribe\Renderer\RendererInterface;
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
    ): void {
        $data = $this->collector->collect($newTag, $previousTag, $fullHistory);
        $processedData = $this->processData($data);

        if ($this->renderer instanceof \Chance\ReleaseScribe\Renderer\LegacyRenderer || $this->renderer instanceof \Chance\ReleaseScribe\Renderer\ConventionalMarkdownRenderer) {
            $content = $this->renderer->render($processedData, $this->mainHeader);
        } else {
            // Attempt to wrap in ChangeLogData for modern renderers
            $releases = [];
            foreach ($processedData as $tag => $commits) {
                $sections = [new \Chance\ReleaseScribe\Data\Section('Commits', (array)$commits)];
                $releases[] = new \Chance\ReleaseScribe\Data\Release($tag, $sections);
            }
            $changeLogData = new \Chance\ReleaseScribe\Data\ChangeLogData($this->mainHeader, $releases);
            $content = $this->renderer->render($changeLogData);
        }

        $file->fwrite($content);
    }

    public function processData(array $rawData): array
    {
        return $rawData;
    }
}
