<?php

namespace Chance\ReleaseScribe\Collector;

use Chance\ReleaseScribe\GitInformation;

class GitCollector implements CollectorInterface
{
    public function __construct(
        private readonly GitInformation $gitInformation
    ) {
    }

    /**
     * @param bool $fullHistory Whether to collect full history or just the newest/requested range
     *
     * @return array<string, array<string>> Map of tag to list of commit messages
     */
    public function collect(?string $newTag = null, ?string $previousTag = null, bool $fullHistory = true): array
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

            if (!$fullHistory) {
                return $data;
            }
        }

        if (!$fullHistory && empty($data) && !empty($tags)) {
            // If no new tag but we want only "what's new", return the latest tag's commits
            $tag = $tags[0];
            if (isset($tags[1])) {
                $commits = $this->gitInformation->getCommitRange($tags[1], $tag, true);
            } else {
                $commits = $this->gitInformation->getCommitsForTag($tag, true);
            }
            $data[$tag] = $commits;

            return $data;
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
