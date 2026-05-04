<?php

namespace Chance\GitToolkit\Data;

class ReleaseRecommendation
{
    public function __construct(
        private readonly string $type,
        private readonly string $reason,
        private readonly string $highestImpactType,
        private readonly bool $breakingChangesDetected
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getHighestImpactType(): string
    {
        return $this->highestImpactType;
    }

    public function breakingChangesDetected(): bool
    {
        return $this->breakingChangesDetected;
    }
}
