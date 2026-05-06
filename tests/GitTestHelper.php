<?php

namespace Chance\ReleaseScribe\Test;

use CzProject\GitPhp\Git;
use CzProject\GitPhp\GitRepository;
use Symfony\Component\Filesystem\Filesystem;

class GitTestHelper
{
    private string $repoPath;
    private Filesystem $fs;

    public function __construct(string $repoPath)
    {
        $this->repoPath = $repoPath;
        $this->fs = new Filesystem();
    }

    public function initRepo(): GitRepository
    {
        if ($this->fs->exists($this->repoPath)) {
            $this->fs->remove($this->repoPath);
        }
        $this->fs->mkdir($this->repoPath);

        $this->runGit(['init']);
        $this->runGit(['config', 'user.email', 'test@example.com']);
        $this->runGit(['config', 'user.name', 'Test User']);
        $this->runGit(['config', 'commit.gpgsign', 'false']);

        return new GitRepository($this->repoPath);
    }

    private function runGit(array $args): void
    {
        $command = 'git ';
        foreach ($args as $arg) {
            $command .= escapeshellarg($arg) . ' ';
        }

        $process = proc_open($command, [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ], $pipes, $this->repoPath);

        if (is_resource($process)) {
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);
        }
    }

    public function createFile(string $filename, string $content = ''): void
    {
        $this->fs->dumpFile($this->repoPath . '/' . $filename, $content);
    }

    public function commit(GitRepository $repo, string $message): void
    {
        $this->runGit(['add', '.']);
        $this->runGit(['commit', '-m', $message]);
    }

    public function tag(GitRepository $repo, string $tagName): void
    {
        $this->runGit(['tag', $tagName]);
    }

    public function cleanUp(): void
    {
        if ($this->fs->exists($this->repoPath)) {
            $this->fs->remove($this->repoPath);
        }
    }

    public function getRepoPath(): string
    {
        return $this->repoPath;
    }
}
