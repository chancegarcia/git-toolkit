<?php

namespace Chance\GitToolkit\Renderer;

use Chance\GitToolkit\Data\ConventionalCommit;

interface RendererInterface
{
    /**
     * @param array<string, array<string>>|array<string, array<string, array<ConventionalCommit>>> $data
     * @param string $mainHeader
     *
     * @return string
     */
    public function render(array $data, string $mainHeader): string;
}
