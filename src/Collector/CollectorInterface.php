<?php

namespace Chance\GitToolkit\Collector;

interface CollectorInterface
{
    /**
     * @param string|null $newTag
     * @return array<string, array<string>> Map of tag to list of commit messages
     */
    public function collect(?string $newTag = null): array;
}
