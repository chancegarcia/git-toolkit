<?php

namespace Chance\ReleaseScribe\Data;

class Release
{
    /**
     * @param string $tag Current tag or version name (e.g. "v1.0.0" or "Unreleased")
     * @param array<Section> $sections
     * @param string|null $previousTag Previous tag for comparison links
     * @param string|null $date ISO date string or formatted date
     * @param string|null $aiSummary Optional AI-generated summary for this release
     */
    public function __construct(
        private readonly string $tag,
        private readonly array $sections,
        private readonly ?string $previousTag = null,
        private readonly ?string $date = null,
        private readonly ?string $aiSummary = null
    ) {
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @return array<Section>
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    public function getPreviousTag(): ?string
    {
        return $this->previousTag;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function getAiSummary(): ?string
    {
        return $this->aiSummary;
    }
}
