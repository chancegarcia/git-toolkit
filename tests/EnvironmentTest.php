<?php

namespace Chance\GitToolkit\Test;

use Chance\GitToolkit\Environment;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class EnvironmentTest extends TestCase
{
    private string $tempDir;
    private Filesystem $fs;

    public function testPrecedence(): void
    {
        // 1. .env
        $this->fs->dumpFile($this->tempDir . '/.env', "TEST_VAR=base\nAPP_ENV=dev");
        Environment::load($this->tempDir);
        $this->assertEquals('base', $_ENV['TEST_VAR']);

        // 2. .env.local (overrides .env)
        $this->fs->dumpFile($this->tempDir . '/.env.local', "TEST_VAR=local");
        Environment::load($this->tempDir);
        $this->assertEquals('local', $_ENV['TEST_VAR']);

        // 3. .env.dev (overrides .env.local)
        $this->fs->dumpFile($this->tempDir . '/.env.dev', "TEST_VAR=dev");
        Environment::load($this->tempDir);
        $this->assertEquals('dev', $_ENV['TEST_VAR']);

        // 4. .env.dev.local (overrides .env.dev)
        $this->fs->dumpFile($this->tempDir . '/.env.dev.local', "TEST_VAR=dev_local");
        Environment::load($this->tempDir);
        $this->assertEquals('dev_local', $_ENV['TEST_VAR']);
    }

    public function testTestEnvironmentDoesNotLoadLocal(): void
    {
        $this->fs->dumpFile($this->tempDir . '/.env', "TEST_VAR=base\nAPP_ENV=test");
        $this->fs->dumpFile($this->tempDir . '/.env.local', "TEST_VAR=local");
        $this->fs->dumpFile($this->tempDir . '/.env.test', "TEST_VAR=test");

        // Set APP_ENV to test before loading
        $_ENV['APP_ENV'] = 'test';

        Environment::load($this->tempDir);

        // In 'test' env, Symfony Dotenv does NOT load .env.local
        // So it should be 'test' from .env.test
        $this->assertEquals('test', $_ENV['TEST_VAR']);
    }

    protected function setUp(): void
    {
        $this->fs = new Filesystem();
        $this->tempDir = sys_get_temp_dir() . '/git-toolkit-env-test-' . uniqid('', true);
        $this->fs->mkdir($this->tempDir);
    }

    protected function tearDown(): void
    {
        $this->fs->remove($this->tempDir);

        // Clear environment variables after each test
        unset($_ENV['APP_ENV'], $_SERVER['APP_ENV'], $_ENV['TEST_VAR'], $_SERVER['TEST_VAR']);
        // Also clear any other vars we might have set
        foreach (['PROJECT_NAME', 'OUTPUT_FILENAME', 'OUTPUT_DIRECTORY', 'PROJECT_ROOT'] as $var) {
            unset($_ENV[$var], $_SERVER[$var]);
        }
    }
}
