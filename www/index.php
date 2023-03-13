<?php

// Include the autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Import the classes
use Jenssegers\Blade\Blade;
use Klein\Klein;
use Linkedout\App\controllers;

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
$blade = new Blade(__DIR__ . '/views', __DIR__ . '/cache');
$blade->directive('pagestyle', function ($expression) {
    return "<?php echo '<link rel=\"stylesheet\" href=\"/resources/pages/' . {$expression} . '.css\">'; ?>";
});

// Database connection
$database = new PDO('mysql:host=' . $_ENV['MYSQL_HOST'] . ';dbname=' . $_ENV['MYSQL_DATABASE'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD']);

// Configure the router
$klein = new Klein();

// Routes
$klein->respond('GET', '/', function () use ($blade, $database) {
    $controller = new controllers\IndexController($blade, $database);
    return $controller->render();
});

// Error handling
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
