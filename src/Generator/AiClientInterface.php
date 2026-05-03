<?php

namespace Chance\GitToolkit\Generator;

interface AiClientInterface
{
    /**
     * @param string $prompt
     * @return string
     */
    public function generateChangelog(string $prompt): string;
}
