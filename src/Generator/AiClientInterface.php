<?php

namespace Chance\ReleaseScribe\Generator;

interface AiClientInterface
{
    public function generateChangelog(string $prompt): string;
}
