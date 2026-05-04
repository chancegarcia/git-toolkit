<?php

namespace Chance\GitToolkit\Renderer;

use Chance\GitToolkit\Data\ChangeLogData;
use Chance\GitToolkit\Data\ConventionalCommit;

interface RendererInterface
{
    /**
     * @param ChangeLogData|array<string, array<string>>|array<string, array<string, array<ConventionalCommit>>> $data
     * @param string|null $mainHeader Ignored if ChangeLogData is provided
     *
     * @return string
     */
    public function render(ChangeLogData|array $data, ?string $mainHeader = null): string;
}
