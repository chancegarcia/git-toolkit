<?php

namespace Chance\ReleaseScribe;

use Symfony\Component\Dotenv\Dotenv;

class Environment
{
    public static function load(string $projectDir): void
    {
        $dotenv = new Dotenv();

        // Load .env, .env.local, and .env.$APP_ENV, .env.$APP_ENV.local
        // Symfony Dotenv::loadEnv() does exactly what is requested:
        // .env
        // .env.local (unless $APP_ENV is test)
        // .env.$APP_ENV
        // .env.$APP_ENV.local

        $envFile = $projectDir . '/.env';
        if (file_exists($envFile)) {
            $dotenv->loadEnv($envFile);
        }
    }
}
