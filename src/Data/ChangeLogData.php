<?php

namespace Chance\ReleaseScribe\Data;

class ChangeLogData
{
    /**
     * @param string $mainHeader Main title of the changelog
     * @param array<Release> $releases
     * @param array<string, mixed> $options Additional renderer-specific options
     */
    public function __construct(
        private readonly string $mainHeader,
        private readonly array $releases,
        private readonly array $options = []
    ) {
    }

    public function getMainHeader(): string
    {
        return $this->mainHeader;
    }

    /**
     * @return array<Release>
     */
    public function getReleases(): array
    {
        return $this->releases;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
