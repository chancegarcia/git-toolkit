<?php

namespace Chance\GitToolkit\Tests\Service;

use Chance\GitToolkit\Data\ConventionalCommit;
use Chance\GitToolkit\Service\ReleaseRecommender;
use PHPUnit\Framework\TestCase;

class ReleaseRecommenderTest extends TestCase
{
    private ReleaseRecommender $recommender;

    public function testRecommendMajorForBreakingChange(): void
    {
        $commits = [
            new ConventionalCommit('fix', null, 'fix bug', null, null, true),
            new ConventionalCommit('feat', null, 'new feature'),
        ];

        $recommendation = $this->recommender->recommend($commits);

        $this->assertEquals(ReleaseRecommender::MAJOR, $recommendation->getType());
        $this->assertTrue($recommendation->breakingChangesDetected());
        $this->assertEquals('fix', $recommendation->getHighestImpactType());
    }

    public function testRecommendMinorForFeature(): void
    {
        $commits = [
            new ConventionalCommit('feat', null, 'new feature'),
            new ConventionalCommit('fix', null, 'fix bug'),
        ];

        $recommendation = $this->recommender->recommend($commits);

        $this->assertEquals(ReleaseRecommender::MINOR, $recommendation->getType());
        $this->assertFalse($recommendation->breakingChangesDetected());
        $this->assertEquals('feat', $recommendation->getHighestImpactType());
    }

    public function testRecommendPatchForFixOnly(): void
    {
        $commits = [
            new ConventionalCommit('fix', null, 'fix bug'),
            new ConventionalCommit('docs', null, 'update docs'),
        ];

        $recommendation = $this->recommender->recommend($commits);

        $this->assertEquals(ReleaseRecommender::PATCH, $recommendation->getType());
        $this->assertFalse($recommendation->breakingChangesDetected());
        $this->assertEquals('fix', $recommendation->getHighestImpactType());
    }

    public function testRecommendNoneForChoreOnly(): void
    {
        $commits = [
            new ConventionalCommit('chore', null, 'update dependencies'),
            new ConventionalCommit('docs', null, 'update docs'),
        ];

        $recommendation = $this->recommender->recommend($commits);

        $this->assertEquals(ReleaseRecommender::NONE, $recommendation->getType());
        $this->assertFalse($recommendation->breakingChangesDetected());
    }

    public function testConfigurableImpactMapping(): void
    {
        $recommender = new ReleaseRecommender([
            'chore' => ReleaseRecommender::PATCH,
        ]);

        $commits = [
            new ConventionalCommit('chore', null, 'update dependencies'),
        ];

        $recommendation = $recommender->recommend($commits);

        $this->assertEquals(ReleaseRecommender::PATCH, $recommendation->getType());
    }

    public function testEmptyCommits(): void
    {
        $recommendation = $this->recommender->recommend([]);

        $this->assertEquals(ReleaseRecommender::NONE, $recommendation->getType());
    }

    protected function setUp(): void
    {
        $this->recommender = new ReleaseRecommender();
    }
}
