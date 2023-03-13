<?php

// Include the autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Import the classes
use Klein\Klein;

// Set error reporting
error_reporting(E_ALL ^ E_DEPRECATED);

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

// Template engine
$blade = new Jenssegers\Blade\Blade(__DIR__ . '/views', __DIR__ . '/cache');

// Configure the router
$klein = new Klein();

$klein->onHttpError(function ($code, $router) {
    switch ($code) {
        case 404:
            $router->response()->body('Page not found');
            break;
        default:
            $router->response()->body('An error has occurred');
    }
});

$klein->dispatch();
