<?php

namespace Chance\ReleaseScribe\Formatter;

class MarkdownFormatter
{
    public const array MARKDOWN_RESERVED_CHARACTERS = [
        '*',
        // '_',
        // '{',
        // '}',
        '[',
        ']',
        '(',
        ')',
        '#',
        '+',
        // '-',
        // '.',
        '!',
    ];

    public static function escapeCommitsForMarkdown(array $commits): array
    {
        foreach ($commits as $i => $commitMsg) {
            // remove empty list items
            if (preg_match('/^-\s*$/', (string)$commitMsg)) {
                unset($commits[$i]);
            }
        }

        $stringCommits = implode("\n", $commits);

        foreach (self::MARKDOWN_RESERVED_CHARACTERS as $reservedChar) {
            $stringCommits = str_replace($reservedChar, sprintf('\%s', $reservedChar), $stringCommits);
        }

        /** @var array<string> $result */
        $result = explode("\n", $stringCommits);

        return $result;
    }
}
