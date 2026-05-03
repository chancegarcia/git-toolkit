<?php

/**
 * @package
 * @subpackage
 * @author      Chance Garcia <chance@garcia.codes>
 * @copyright   (C)Copyright 2013-2026 Chance Garcia, chancegarcia.com
 *
 *    The MIT License (MIT)
 *
 * Copyright (c) 2013-2026 Chance Garcia
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */

namespace Chance\GitToolkit\Service;

use Chance\GitToolkit\Collector\GitCollector;
use Chance\GitToolkit\Formatter\MarkdownFormatter;
use Chance\GitToolkit\Generator\DefaultGenerator;
use Chance\GitToolkit\Generator\GeneratorInterface;
use Chance\GitToolkit\GitInformation;
use Chance\GitToolkit\Renderer\MarkdownRenderer;
use SplFileObject;

class ChangeLogService
{
    public const string DEFAULT_MAIN_HEADER_NAME = 'Projecty McProjectFace';
    public const string DEFAULT_FILE_NAME = 'changelog.md';
    public const string DEFAULT_FILE_PATH = '';

    private readonly GitInformation $gitInformation;
    private string $changeLogFileName = self::DEFAULT_FILE_NAME;
    private string $changeLogFilePath = self::DEFAULT_FILE_PATH;
    private string $mainHeaderName = self::DEFAULT_MAIN_HEADER_NAME;
    private ?GeneratorInterface $generator = null;

    public function __construct(
        GitInformation $gitInformation
    )
    {
        $this->gitInformation = $gitInformation;
    }

    public function getGenerator(): GeneratorInterface
    {
        if ($this->generator === null) {
            $this->generator = new DefaultGenerator(
                new GitCollector($this->gitInformation),
                new MarkdownRenderer(),
                $this->mainHeaderName
            );
        }

        return $this->generator;
    }

    public function setGenerator(GeneratorInterface $generator): void
    {
        $this->generator = $generator;
    }

    /**
     * @param GeneratorInterface|null $generator
     */
    public function injectGenerator(?GeneratorInterface $generator): void
    {
        $this->generator = $generator;
    }

    public function getGitInformation(): GitInformation
    {
        return $this->gitInformation;
    }

    public function getChangeLogFileName(): string
    {
        return $this->changeLogFileName;
    }

    public function setChangeLogFileName(?string $changeLogFileName): void
    {
        if (is_string($changeLogFileName)) {
            $this->changeLogFileName = $changeLogFileName;
        } else {
            $this->changeLogFileName = self::DEFAULT_FILE_NAME;
        }
    }

    public function getChangeLogFilePath(): string
    {
        return $this->changeLogFilePath;
    }

    public function setChangeLogFilePath(?string $changeLogFilePath): void
    {
        if (null === $changeLogFilePath) {
            $this->changeLogFilePath = self::DEFAULT_FILE_PATH;
            return;
        }

        if ('' === $changeLogFilePath) {
            $this->changeLogFilePath = $changeLogFilePath;
            return;
        }

        $this->changeLogFilePath = rtrim($changeLogFilePath, '/') . '/';
    }

    public function getMainHeaderName(): string
    {
        return $this->mainHeaderName;
    }

    public function setMainHeaderName(?string $mainHeaderName): void
    {
        if (is_string($mainHeaderName)) {
            $this->mainHeaderName = $mainHeaderName;
        } else {
            $this->mainHeaderName = self::DEFAULT_MAIN_HEADER_NAME;
        }

        // invalidate generator to ensure mainHeaderName propagation
        $this->generator = null;
    }

    public function writeChangeLog(\SplFileObject $file, ?string $newTag = null, ?string $previousTag = null): void
    {
        $this->getGenerator()->generate($file, $newTag, $previousTag);
    }

    public function getFullPath(): string
    {
        return $this->changeLogFilePath . $this->changeLogFileName;
    }

    public function getSplFileObject(): SplFileObject
    {
        $fullPath = $this->getFullPath();

        return new SplFileObject($fullPath, 'wb+');
    }
}
