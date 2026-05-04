<?php

namespace Chance\GitToolkit\Data;

class ConventionalCommit
{
    public function __construct(
        private readonly string $type,
        private readonly ?string $scope,
        private readonly string $description,
        private readonly ?string $body = null,
        private readonly ?string $footer = null,
        private readonly bool $isBreakingChange = false,
        private readonly string $rawMessage = ''
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }

    public function getDescription(): string
    {
        if ($this->body) {
            return $this->description . "\n\n" . $this->body;
        }

        return $this->description;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getFooter(): ?string
    {
        return $this->footer;
    }

    public function isBreakingChange(): bool
    {
        return $this->isBreakingChange;
    }

    public function getRawMessage(): string
    {
        return $this->rawMessage;
    }
}
