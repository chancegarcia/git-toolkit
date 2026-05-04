<?php

namespace Chance\GitToolkit\Service;

use Chance\GitToolkit\Data\ConventionalCommit;
use Chance\GitToolkit\Data\ReleaseRecommendation;

class ReleaseRecommender
{
    public const string MAJOR = 'major';
    public const string MINOR = 'minor';
    public const string PATCH = 'patch';
    public const string NONE = 'none';

    private array $impactMapping = [
        'feat' => self::MINOR,
        'fix' => self::PATCH,
        'perf' => self::PATCH,
        'security' => self::PATCH,
        'deprecated' => self::PATCH,
        'docs' => self::NONE,
        'refactor' => self::NONE,
        'test' => self::NONE,
        'build' => self::NONE,
        'ci' => self::NONE,
        'chore' => self::NONE,
        'style' => self::NONE,
    ];

    /**
     * @param array<string, string>|null $impactMapping
     */
    public function __construct(?array $impactMapping = null)
    {
        if ($impactMapping !== null) {
            $this->impactMapping = array_merge($this->impactMapping, $impactMapping);
        }
    }

    /**
     * @param array<ConventionalCommit> $commits
     */
    public function recommend(array $commits): ReleaseRecommendation
    {
        $highestImpact = self::NONE;
        $highestImpactType = 'none';
        $breakingChanges = false;
        $counts = [
            self::MAJOR => 0,
            self::MINOR => 0,
            self::PATCH => 0,
            self::NONE => 0,
        ];

        foreach ($commits as $commit) {
            if ($commit->isBreakingChange()) {
                $breakingChanges = true;
                $counts[self::MAJOR]++;
                $highestImpact = self::MAJOR;
                $highestImpactType = $commit->getType();
            }

            $impact = $this->impactMapping[$commit->getType()] ?? self::NONE;
            $counts[$impact]++;

            if ($highestImpact === self::MAJOR) {
                continue;
            }

            if ($impact === self::MINOR) {
                $highestImpact = self::MINOR;
                $highestImpactType = $commit->getType();
            } elseif ($impact === self::PATCH && $highestImpact !== self::MINOR) {
                $highestImpact = self::PATCH;
                $highestImpactType = $commit->getType();
            } elseif ($impact === self::NONE && $highestImpact === self::NONE) {
                $highestImpactType = $commit->getType();
            }
        }

        if ($breakingChanges) {
            $reason = sprintf('%d breaking changes detected.', $counts[self::MAJOR]);

            return new ReleaseRecommendation(self::MAJOR, $reason, $highestImpactType, true);
        }

        if ($counts[self::MINOR] > 0) {
            $reason = sprintf('%d feature commits found and no breaking changes detected.', $counts[self::MINOR]);

            return new ReleaseRecommendation(self::MINOR, $reason, $highestImpactType, false);
        }

        if ($counts[self::PATCH] > 0) {
            $reason = sprintf(
                '%d patch-impact commits found and no features or breaking changes detected.',
                $counts[self::PATCH]
            );

            return new ReleaseRecommendation(self::PATCH, $reason, $highestImpactType, false);
        }

        return new ReleaseRecommendation(self::NONE, 'No release-impacting commits found.', $highestImpactType, false);
    }
}
