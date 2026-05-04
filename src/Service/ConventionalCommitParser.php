<?php

namespace Chance\GitToolkit\Service;

use Chance\GitToolkit\Data\ConventionalCommit;

class ConventionalCommitParser
{
    private const string PATTERN = '/^(?P<type>[a-zA-Z]+)(?:\((?P<scope>[^)]+)\))?(?P<breaking>!)?: (?P<description>.+)/';

    public function parse(string $message): ?ConventionalCommit
    {
        $lines = explode("\n", trim($message));
        if ($message === '') {
            return null;
        }

        $header = $lines[0];
        if (!preg_match(self::PATTERN, $header, $matches)) {
            return null;
        }

        $type = $matches['type'];
        $scope = !empty($matches['scope']) ? $matches['scope'] : null;
        $isBreakingChange = !empty($matches['breaking']);
        $description = $matches['description'];

        $body = null;
        $footer = null;

        if (count($lines) > 1) {
            $remainingLines = array_slice($lines, 1);
            // Conventional Commits requires a blank line between header and body
            // But we should be a bit flexible if needed.
            // For now, let's just look for BREAKING CHANGE in the whole remaining text.

            $remainingText = implode("\n", $remainingLines);

            if (preg_match('/^BREAKING[ -]CHANGE: (?P<breakingDescription>.+)/m', $remainingText, $breakingMatches)) {
                $isBreakingChange = true;
            }

            // Simple split: first block after header is body, last block might be footer if it looks like one
            // This is a simplified version.
            $body = trim($remainingText);
        }

        return new ConventionalCommit(
            $type, $scope, $description, $body, $footer, $isBreakingChange, $message
        );
    }
}
