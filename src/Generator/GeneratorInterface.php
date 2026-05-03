<?php

namespace Chance\GitToolkit\Generator;

use SplFileObject;

interface GeneratorInterface
{
    /**
     * @param SplFileObject $file The file to write the changelog to
     * @param string|null $newTag Optional new tag name for unreleased commits
     * @param string|null $previousTag Optional previous tag for comparison
     */
    public function generate(SplFileObject $file, ?string $newTag = null, ?string $previousTag = null): void;
}
