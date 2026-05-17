<?php

namespace Chance\ReleaseScribe\Renderer;

use Chance\ReleaseScribe\Data\ChangeLogData;
use Chance\ReleaseScribe\Data\ConventionalCommit;

interface RendererInterface
{
    /**
     * @param ChangeLogData|array<string, array<string>>|array<string, array<string, array<ConventionalCommit>>> $data
     * @param string|null $mainHeader Ignored if ChangeLogData is provided
     */
    public function render(ChangeLogData|array $data, ?string $mainHeader = null): string;
}
