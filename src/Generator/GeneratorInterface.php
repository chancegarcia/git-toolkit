<?php

namespace Chance\ReleaseScribe\Generator;

use SplFileObject;

interface GeneratorInterface
{
    /**
     * @param SplFileObject $file The file to write the changelog to
     * @param string|null $newTag Optional new tag name for unreleased commits
     * @param string|null $previousTag Optional previous tag for comparison
     * @param bool $fullHistory Whether to collect full history or just the newest/requested range
     */
    public function generate(
        SplFileObject $file,
        ?string $newTag = null,
        ?string $previousTag = null,
        bool $fullHistory = true
    ): void;

    /**
     * @param array $rawData
     *
     * @return array
     */
    public function processData(array $rawData): array;
}
