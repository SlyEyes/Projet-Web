<?php

namespace Linkedout\App\services;

use Jenssegers\Blade\Blade;
use Klein\Klein;
use Linkedout\App\controllers;
use PDO;

/**
 * The router service is responsible for routing the requests to the correct controller
 * @package Linkedout\App\services
 */
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

    /**
     * The getter for the Klein router instance
     * @return Klein
     */
    public function getKlein(): Klein
    {
        return $this->klein;
    }

    /**
     * Add all the declared routes to the router
     * @return void
     */
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

        $this->klein->respond('GET', '/company/[i:id]', function () {
            $controller = new controllers\CompanyController($this->blade, $this->database);
            return $controller->render();
        });

        $this->klein->respond(array('GET', 'POST'), '/dashboard/[a:collection]?/[a:destination]?', function ($request) {
            $controller = new controllers\DashboardController($this->blade, $this->database);
            $controller->setRouteParams($request->collection, $request->destination);
            return $controller->render();
        });
    }

    /**
     * Add the error handling to the router
     * @return void
     */
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

    /**
     * Handle the request to the router and dispatch it to the correct controller
     * @return void
     */
    public function dispatch(): void
    {
        $this->klein->dispatch();
    }
}
