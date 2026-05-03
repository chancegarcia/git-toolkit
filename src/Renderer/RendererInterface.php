<?php

namespace Chance\GitToolkit\Renderer;

interface RendererInterface
{
    /**
     * @param array<string, array<string>> $data Map of tag to list of commit messages
     * @param string $mainHeader
     * @return string The rendered changelog content
     */
    public function render(array $data, string $mainHeader): string;
}
