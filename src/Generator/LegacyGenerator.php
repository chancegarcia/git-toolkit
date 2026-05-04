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
        $content = $this->renderer->render($processedData, $this->mainHeader);
        $file->fwrite($content);
    }

    public function processData(array $rawData): array
    {
        return $rawData;
    }
}
