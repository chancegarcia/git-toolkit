<?php

namespace Chance\ReleaseScribe\Collector;

interface CollectorInterface
{
    /**
     * @param bool $fullHistory Whether to collect full history or just the newest/requested range
     *
     * @return array<string, array<string>> Map of tag to list of commit messages
     */
    public function collect(?string $newTag = null, ?string $previousTag = null, bool $fullHistory = true): array;
}
