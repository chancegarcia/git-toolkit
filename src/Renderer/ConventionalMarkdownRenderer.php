<?php

namespace Chance\GitToolkit\Renderer;

use Chance\GitToolkit\Data\ChangeLogData;
use Chance\GitToolkit\Data\ConventionalCommit;
use Chance\GitToolkit\Data\Release;
use Chance\GitToolkit\Formatter\MarkdownFormatter;

class ConventionalMarkdownRenderer implements RendererInterface
{
    public function render(ChangeLogData|array $data, ?string $mainHeader = null): string
    {
        if ($data instanceof ChangeLogData) {
            $mainHeader = $data->getMainHeader();
            $releases = $data->getReleases();
        } else {
            $releases = $this->mapLegacyData($data);
        }

        $content = sprintf("# %s\n\n", $mainHeader);

        foreach ($releases as $release) {
            $content .= sprintf("## %s\n\n", $release->getTag());

            if ($release->getAiSummary()) {
                $content .= $release->getAiSummary() . "\n\n";
            }

            foreach ($release->getSections() as $section) {
                $content .= sprintf("### %s\n\n", $section->getLabel());

                $descriptions = array_map(function ($item) {
                    if (is_string($item)) {
                        return $item;
                    }
                    /** @var ConventionalCommit $item */
                    $description = $item->getDescription();
                    if ($item->getScope()) {
                        $description = sprintf('**%s:** %s', $item->getScope(), $description);
                    }

                    return $description;
                }, $section->getItems());

                $escapedCommits = MarkdownFormatter::escapeCommitsForMarkdown($descriptions);
                foreach ($escapedCommits as $commit) {
                    $content .= sprintf("- %s\n", $commit);
                }
                $content .= "\n";
            }
        }

        return $content;
    }

    /**
     * @param array $data
     *
     * @return array<Release>
     */
    private function mapLegacyData(array $data): array
    {
        $releases = [];
        foreach ($data as $tag => $groups) {
            $sections = [];
            foreach ($groups as $label => $commits) {
                $sections[] = new \Chance\GitToolkit\Data\Section($label, (array)$commits);
            }
            $releases[] = new Release($tag, $sections);
        }

        return $releases;
    }
}
