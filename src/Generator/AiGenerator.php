<?php

namespace Chance\ReleaseScribe\Generator;

use SplFileObject;
use Chance\ReleaseScribe\Collector\CollectorInterface;

class AiGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly CollectorInterface $collector,
        private readonly AiClientInterface $aiClient,
        private readonly PromptTemplateLoader $promptLoader
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
        $prompt = $this->promptLoader->load($processedData);
        $content = $this->aiClient->generateChangelog($prompt);
        $file->fwrite($content);
    }

    public function processData(array $rawData): array
    {
        return $rawData;
    }
}
