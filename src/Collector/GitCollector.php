<?php

namespace Chance\GitToolkit\Collector;

use Chance\GitToolkit\GitInformation;

class GitCollector implements CollectorInterface
{
    public function __construct(
        private readonly GitInformation $gitInformation
    )
    {
    }

    public function collect(?string $newTag = null): array
    {
        $data = [];
        $tags = $this->gitInformation->getGitTags();

        if ($newTag !== null) {
            $currentCommit = $this->gitInformation->getCurrentCommit();
            if (empty($tags)) {
                $firstCommit = $this->gitInformation->getFirstCommit();
                $commits = $this->gitInformation->getCommitRange($firstCommit, $currentCommit);
            } else {
                $commits = $this->gitInformation->getCommitRange($tags[0], $currentCommit);
            }
            $data[$newTag] = $commits;
        }

        foreach ($tags as $i => $tag) {
            if (isset($tags[$i + 1])) {
                $commits = $this->gitInformation->getCommitRange($tags[$i + 1], $tag);
            } else {
                $commits = $this->gitInformation->getCommitRange($this->gitInformation->getFirstCommit(), $tag);
            }
            $data[$tag] = $commits;
        }

        return $data;
    }
}
