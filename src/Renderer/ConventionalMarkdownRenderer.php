<?php

namespace Chance\GitToolkit\Renderer;

use Chance\GitToolkit\Data\ConventionalCommit;
use Chance\GitToolkit\Formatter\MarkdownFormatter;

class ConventionalMarkdownRenderer implements RendererInterface
{
    public function render(array $data, string $mainHeader): string
    {
        $content = sprintf("# %s\n\n", $mainHeader);

        foreach ($data as $tag => $groups) {
            $content .= sprintf("## %s\n\n", $tag);

            foreach ($groups as $label => $commits) {
                $content .= sprintf("### %s\n\n", $label);

                $descriptions = array_map(function ($commit) {
                    if (is_string($commit)) {
                        return $commit;
                    }
                    /** @var ConventionalCommit $commit */
                    $description = $commit->getDescription();
                    if ($commit->getScope()) {
                        $description = sprintf('**%s:** %s', $commit->getScope(), $description);
                    }

                    return $description;
                }, (array)$commits);

                $escapedCommits = MarkdownFormatter::escapeCommitsForMarkdown($descriptions);
                foreach ($escapedCommits as $commit) {
                    $content .= sprintf("- %s\n", $commit);
                }
                $content .= "\n";
            }
        }

        return $content;
    }
}
