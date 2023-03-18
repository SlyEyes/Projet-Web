<?php

// Include the autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Import the classes
use Linkedout\App\services;

// Set error reporting
error_reporting(E_ALL ^ E_DEPRECATED);

// Environment variables
$envService = new services\EnvService();
$envService->loadEnv();
$envService->checkEnv();

// Development mode cache headers
if (getenv('APP_ENV') === 'development') {
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
}

// Template engine
$bladeService = new services\TemplateService();
$bladeService->addDirectives();

// Database
$databaseService = new services\DatabaseService();

// Configure the router
$routerService = new services\RouterService($bladeService, $databaseService);
$routerService->addRoutes();
$routerService->addErrorHandling();
$routerService->dispatch();
