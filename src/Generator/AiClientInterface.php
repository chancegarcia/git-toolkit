<?php

namespace Chance\ReleaseScribe\Generator;

interface AiClientInterface
{
    /**
     * @param string $prompt
     * @return string
     */
    public function generateChangelog(string $prompt): string;
}
