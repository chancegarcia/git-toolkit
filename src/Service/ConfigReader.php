<?php

namespace Chance\ReleaseScribe\Service;

class ConfigReader
{
    /**
     * @param array<string, mixed> $configMap
     */
    public function __construct(
        private readonly array $configMap = []
    ) {
    }

    public function getBool(string $key, bool $default = false): bool
    {
        $value = $this->get($key, $default);
        if (is_bool($value)) {
            return $value;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        // 1. Check provided config map
        if (array_key_exists($key, $this->configMap)) {
            return $this->configMap[$key];
        }

        // 2. Check environment variables
        $envValue = getenv($key);
        if ($envValue !== false) {
            return $envValue;
        }

        // 3. Fallback to $_ENV for cases where getenv might not see them (though Dotenv should populate both)
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        return $default;
    }
}
