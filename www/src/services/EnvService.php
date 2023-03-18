<?php

namespace Linkedout\App\services;

use Exception;
use josegonzalez\Dotenv\Loader;

/**
 * Service for the environment variables. It can load the environment variables from the .env file and checks that the
 * required variables are set.
 * @package Linkedout\App\services
 */
class EnvService
{
    /**
     * Load the environment variables from the .env file, located in the root of the project. If the file is not found,
     * nothing happens.
     */
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

    /**
     * Check that the required environment variables are set. If not, an exception is thrown.
     * @throws Exception
     */
    public static function checkEnv(): void
    {
        if (!getenv('MYSQL_HOST') || !getenv('MYSQL_DATABASE') || !getenv('MYSQL_USER') || !getenv('MYSQL_PASSWORD')) {
            throw new Exception('Missing database configuration variables');
        }

        if (!getenv('JWT_SECRET')) {
            throw new Exception('Missing JWT secret variable');
        }

        if (!getenv('APP_ENV')) {
            $_ENV['APP_ENV'] = 'production';
        }
    }
}
