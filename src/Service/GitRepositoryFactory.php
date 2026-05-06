<?php

namespace Chance\ReleaseScribe\Service;

use CzProject\GitPhp\GitRepository;

class GitRepositoryFactory
{
    public function __construct(
        private readonly string $projectRoot
    ) {
    }

    public function create(): GitRepository
    {
        return new GitRepository($this->projectRoot);
    }
}
