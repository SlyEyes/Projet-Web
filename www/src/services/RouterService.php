<?php

namespace Linkedout\App\services;

use Jenssegers\Blade\Blade;
use Klein\Klein;
use Linkedout\App\controllers;
use PDO;

class RouterService
{
    protected Klein $klein;
    protected Blade $blade;
    protected PDO $database;

    function __construct(TemplateService $templateService, DatabaseService $databaseService)
    {
        $this->blade = $templateService->getBlade();
        $this->database = $databaseService->getDatabase();
        $this->klein = new Klein();
    }

    public function getKlein(): Klein
    {
        return $this->klein;
    }

    public function addRoutes(): void
    {
        $this->klein->respond('GET', '/', function () {
            $controller = new controllers\IndexController($this->blade, $this->database);
            return $controller->render();
        });

        $this->klein->respond(array('GET', 'POST'), '/login', function () {
            $controller = new controllers\LoginController($this->blade, $this->database);
            return $controller->render();
        });
    }

    public function addErrorHandling(): void
    {
        $this->klein->onHttpError(function ($code, $router) {
            switch ($code) {
                case 404:
                    $notFoundController = new controllers\NotFoundController($this->blade, $this->database);
                    $router->response()->body($notFoundController->render());
                    break;
                default:
                    $router->response()->body('An error has occurred');
            }
        });
    }

    public function dispatch(): void
    {
        $this->klein->dispatch();
    }
}
