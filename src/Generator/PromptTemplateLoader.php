<?php

namespace Chance\ReleaseScribe\Generator;

class PromptTemplateLoader
{
    public function __construct(
        private readonly string $templatePath
    ) {
    }

    /**
     * @param array<string, array<string>> $data
     */
    public function load(array $data): string
    {
        // For now, just a placeholder that converts data to string
        // In the future, this would load a template from $this->templatePath and inject $data
        return "Template Path: " . $this->templatePath . "\n" . json_encode($data, JSON_PRETTY_PRINT);
    }
}
