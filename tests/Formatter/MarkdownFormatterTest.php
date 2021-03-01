<?php
/**
 * @package
 * @subpackage
 * @author      Chance Garcia <chance@garcia.codes>
 * @copyright   (C)Copyright 2013-2021 Chance Garcia, chancegarcia.com
 *
 *    The MIT License (MIT)
 *
 * Copyright (c) 2013-2021 Chance Garcia
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */

namespace Chance\GitToolkit\Test\Formatter;

use Chance\GitToolkit\Formatter\MarkdownFormatter;
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
