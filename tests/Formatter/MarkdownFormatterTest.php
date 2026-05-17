<?php

namespace Chance\ReleaseScribe\Test\Formatter;

use Chance\ReleaseScribe\Formatter\MarkdownFormatter;
use PHPUnit\Framework\TestCase;

class MarkdownFormatterTest extends TestCase
{
    public function testEscapeCommitsForMarkdown()
    {
        // @formatter:off
        $commitText = 'the following are reserved characters that must be escaped:'
            . "\n" . '* [ ] ( ) # + !'
            . "\n"
            . 'Next we test an empty list item'
            . "\n"
            . '-'
            . "\n"
            . '- not empty second line'
        ;

        $expectedText = 'the following are reserved characters that must be escaped:'
            . "\n"
            . '\\* \\[ \\] \( \\) \\# \\+ \\!'
            . "\n"
            . 'Next we test an empty list item'
            . "\n"
            . '- not empty second line'
        ;
        // @formatter:on

        $expectedArray = explode("\n", $expectedText);
        $commitsArray = explode("\n", $commitText);
        self::assertEquals($expectedArray, MarkdownFormatter::escapeCommitsForMarkdown($commitsArray));
    }
}
