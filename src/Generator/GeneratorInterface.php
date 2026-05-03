<?php

namespace Chance\GitToolkit\Generator;

use SplFileObject;

interface GeneratorInterface
{
    /**
     * @param SplFileObject $file The file to write the changelog to
     * @param string|null $newTag Optional new tag name for unreleased commits
     */
    public function generate(SplFileObject $file, ?string $newTag = null): void;
}
