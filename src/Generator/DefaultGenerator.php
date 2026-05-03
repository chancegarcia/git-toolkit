<?php

namespace Chance\GitToolkit\Generator;

use Chance\GitToolkit\Collector\CollectorInterface;
use Chance\GitToolkit\Renderer\RendererInterface;
use SplFileObject;

class DefaultGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly CollectorInterface $collector,
        private readonly RendererInterface $renderer,
        private readonly string $mainHeader
    )
    {
    }

    public function generate(SplFileObject $file, ?string $newTag = null, ?string $previousTag = null): void
    {
        $data = $this->collector->collect($newTag, $previousTag);
        $content = $this->renderer->render($data, $this->mainHeader);
        $file->fwrite($content);
    }
}
