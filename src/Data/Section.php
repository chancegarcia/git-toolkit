<?php

namespace Chance\ReleaseScribe\Data;

class Section
{
    /**
     * @param string $label
     * @param array<ConventionalCommit|string> $items
     * @param string|null $type The commit type this section represents (if any)
     */
    public function __construct(
        private readonly string $label,
        private readonly array $items,
        private readonly ?string $type = null
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return array<ConventionalCommit|string>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getType(): ?string
    {
        return $this->type;
    }
}
