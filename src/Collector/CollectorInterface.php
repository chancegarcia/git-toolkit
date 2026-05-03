<?php

namespace Chance\GitToolkit\Collector;

interface CollectorInterface
{
    /**
     * @param string|null $newTag
     * @param string|null $previousTag
     * @return array<string, array<string>> Map of tag to list of commit messages
     */
    public function collect(?string $newTag = null, ?string $previousTag = null): array;
}
