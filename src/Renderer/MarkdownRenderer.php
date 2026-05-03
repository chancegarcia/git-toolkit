<?php

namespace Chance\GitToolkit\Renderer;

use Chance\GitToolkit\Formatter\MarkdownFormatter;

class MarkdownRenderer implements RendererInterface
{
    public function render(array $data, string $mainHeader): string
    {
        $content = sprintf("# %s\n\n", $mainHeader);

        foreach ($data as $tag => $commits) {
            $content .= sprintf("## %s\n\n", $tag);
            $escapedCommits = MarkdownFormatter::escapeCommitsForMarkdown($commits);
            foreach ($escapedCommits as $commit) {
                $content .= sprintf("- %s\n", $commit);
            }
            $content .= "\n";
        }

        return $content;
    }
}
