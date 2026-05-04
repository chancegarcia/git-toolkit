<?php

namespace Chance\GitToolkit\Renderer;

use Chance\GitToolkit\Data\ChangeLogData;
use Chance\GitToolkit\Formatter\MarkdownFormatter;

class LegacyRenderer implements RendererInterface
{
    public function render(ChangeLogData|array $data, ?string $mainHeader = null): string
    {
        if ($data instanceof ChangeLogData) {
            $mainHeader = $data->getMainHeader();
            $releases = $data->getReleases();
        } else {
            $releases = [];
            foreach ($data as $tag => $commits) {
                $releases[] = ['tag' => $tag, 'commits' => $commits];
            }
        }

        $content = sprintf("# %s\n\n", $mainHeader);

        foreach ($releases as $release) {
            if (is_array($release)) {
                $tag = $release['tag'];
                $commits = $release['commits'];
            } else {
                $tag = $release->getTag();
                // LegacyRenderer doesn't support sections, so we flatten them or just handle legacy data
                // Actually, if it's ChangeLogData, we should probably handle it or just keep it simple.
                // LegacyRenderer is for simple non-conventional commits.
                $commits = [];
                foreach ($release->getSections() as $section) {
                    foreach ($section->getItems() as $item) {
                        $commits[] = $item;
                    }
                }
            }

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
