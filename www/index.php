<?php

// Include the autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load the environment variables
if (file_exists(__DIR__ . '../.env')) {
    $dotenv = new josegonzalez\Dotenv\Loader(__DIR__ . '../.env');
    $dotenv->parse();
    $dotenv->toEnv();
}

// Check the required environment variables are set
if (!getenv('MYSQL_HOST') || !getenv('MYSQL_DATABASE') || !getenv('MYSQL_USER') || !getenv('MYSQL_PASSWORD')) {
    throw new Exception('Missing configuration variables');
}
