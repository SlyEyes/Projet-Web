<?php

namespace Linkedout\App\services;

use Exception;
use josegonzalez\Dotenv\Loader;

class EnvService
{
    // Load the environment variables
    public static function loadEnv(): void
    {
        $envFiles = [
            realpath(__DIR__ . '/../../.env'),
            realpath(__DIR__ . '/../../../.env'),
        ];

        foreach ($envFiles as $envFile) {
            if (!file_exists($envFile))
                continue;

            $dotenv = new Loader($envFile);
            $dotenv->parse();
            $dotenv->toEnv();
        }
    }

    // Check the required environment variables are set
    public static function checkEnv(): void
    {
        if (!getenv('MYSQL_HOST') || !getenv('MYSQL_DATABASE') || !getenv('MYSQL_USER') || !getenv('MYSQL_PASSWORD')) {
            throw new Exception('Missing database configuration variables');
        }

        if (!getenv('JWT_SECRET')) {
            throw new Exception('Missing JWT secret variable');
        }
    }
}
