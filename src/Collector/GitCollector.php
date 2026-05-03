<?php

namespace Chance\GitToolkit\Collector;

use Chance\GitToolkit\GitInformation;

class GitCollector implements CollectorInterface
{
    public function __construct(
        private readonly GitInformation $gitInformation
    ) {
    }

    /**
     * @param string|null $newTag
     * @param string|null $previousTag
     * @return array<string, array<string>> Map of tag to list of commit messages
     */
    public function collect(?string $newTag = null, ?string $previousTag = null): array
    {
        /** @var array<string, array<string>> $data */
        $data = [];
        $tags = $this->gitInformation->getGitTags();

        if ($newTag !== null) {
            $currentCommit = $this->gitInformation->getCurrentCommit();
            if ($previousTag !== null) {
                $commits = $this->gitInformation->getCommitRange($previousTag, $currentCommit, true);
            } elseif (empty($tags)) {
                $firstCommit = $this->gitInformation->getFirstCommit();
                $commits = $this->gitInformation->getCommitRange($firstCommit, $currentCommit, true);
            } else {
                $commits = $this->gitInformation->getCommitRange($tags[0], $currentCommit, true);
            }
            $data[$newTag] = $commits;
        }

        foreach ($tags as $i => $tag) {
            if (isset($tags[$i + 1])) {
                $commits = $this->gitInformation->getCommitRange($tags[$i + 1], $tag, true);
            } else {
                $commits = $this->gitInformation->getCommitsForTag($tag, true);
            }
            $data[$tag] = $commits;
        }

        return $data;
    }
}
