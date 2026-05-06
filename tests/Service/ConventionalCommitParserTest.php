<?php

namespace Chance\ReleaseScribe\Tests\Service;

use Chance\ReleaseScribe\Service\ConventionalCommitParser;
use PHPUnit\Framework\TestCase;

class ConventionalCommitParserTest extends TestCase
{
    private ConventionalCommitParser $parser;

    public static function provideCommitMessages(): array
    {
        return [
            'simple feat' => ['feat: add new command', 'feat', null, false, 'add new command'],
            'feat with scope' => ['fix(parser): handle missing scope', 'fix', 'parser', false, 'handle missing scope'],
            'breaking feat with !' => [
                'feat!: remove deprecated option',
                'feat',
                null,
                true,
                'remove deprecated option',
            ],
            'breaking feat with scope and !' => [
                'refactor(core)!: simplify collector',
                'refactor',
                'core',
                true,
                'simplify collector',
            ],
            'breaking change in footer' => [
                "feat: add new command\n\nBREAKING CHANGE: this is breaking",
                'feat',
                null,
                true,
                "add new command\n\nBREAKING CHANGE: this is breaking",
            ],
            'breaking change in footer with hyphen' => [
                "feat: add new command\n\nBREAKING-CHANGE: this is breaking",
                'feat',
                null,
                true,
                "add new command\n\nBREAKING-CHANGE: this is breaking",
            ],
            'non-conventional' => ['just a regular commit', null, null, false, ''],
            'invalid format' => ['feat : missing colon', null, null, false, ''],
            'multi-line commit' => [
                "docs: add comprehensive project roadmap\n\n- Introduced `docs/roadmap.md` outlining the planned future direction for the project, including rebranding, phases, and AI integration.\n- Updated cross-references in `readme.md` and `modernization-and-ai-changelog-plan.md` to link to the new roadmap.",
                'docs',
                null,
                false,
                "add comprehensive project roadmap\n\n- Introduced `docs/roadmap.md` outlining the planned future direction for the project, including rebranding, phases, and AI integration.\n- Updated cross-references in `readme.md` and `modernization-and-ai-changelog-plan.md` to link to the new roadmap.",
            ],
            'multi-line with breaking change' => [
                "feat(api)!: breaking change across lines\n\nThis is a multi-line body.\n\nBREAKING CHANGE: This is a major change.",
                'feat',
                'api',
                true,
                "breaking change across lines\n\nThis is a multi-line body.\n\nBREAKING CHANGE: This is a major change.",
            ],
        ];
    }

    /**
     * @dataProvider provideCommitMessages
     */
    public function testParse(
        string $message,
        ?string $expectedType,
        ?string $expectedScope,
        bool $expectedBreaking,
        string $expectedDescription
    ): void {
        $commit = $this->parser->parse($message);

        if ($expectedType === null) {
            $this->assertNull($commit);

            return;
        }

        $this->assertNotNull($commit);
        $this->assertEquals($expectedType, $commit->getType());
        $this->assertEquals($expectedScope, $commit->getScope());
        $this->assertEquals($expectedBreaking, $commit->isBreakingChange());
        $this->assertEquals($expectedDescription, $commit->getDescription());
    }

    protected function setUp(): void
    {
        $this->parser = new ConventionalCommitParser();
    }
}
