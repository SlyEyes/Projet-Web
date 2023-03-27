<?php

namespace Linkedout\App\services;

use Exception;
use Jenssegers\Blade\Blade;
use Klein\Klein;
use Linkedout\App\controllers;
use Linkedout\App\controllers\api;
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

        $this->klein->respond(array('GET', 'POST'), '/password-change', function () {
            $controller = new controllers\PasswordChangeController($this->blade, $this->database);
            return $controller->render();
        });

        $this->klein->respond(array('GET', 'POST'), '/profile', function () {
            $controller = new controllers\ProfileController($this->blade, $this->database);
            return $controller->render();
        });

        $this->klein->respond('GET', '/company/[i:id]', function ($request) {
            $controller = new controllers\CompanyController($this->blade, $this->database);
            $controller->setRouteParams($request->id);
            return $controller->render();
        });

        $this->klein->respond('GET', '/internship/[i:id]', function ($request) {
            $controller = new controllers\InternshipController($this->blade, $this->database);
            $controller->setRouteParams($request->id);
            return $controller->render();
        });

        $this->klein->respond(array('GET', 'POST'), '/internship/[i:id]/apply', function ($request) {
            $controller = new controllers\ApplianceController($this->blade, $this->database);
            $controller->setRouteParams($request->id);
            return $controller->render();
        });

        // Redirect to /dashboard/students if no collection is specified
        $this->klein->respond(array('GET', 'POST'), '/dashboard', function ($request, $response) {
            $response->redirect('/dashboard/students');
        });

        $this->klein->respond(array('GET', 'POST'), '/dashboard/[students|tutors|administrators|internships|companies:collection]/[a:destination]?', function ($request) {
            $controller = match ($request->collection) {
                'students', 'tutors', 'administrators' => new controllers\dashboard\PersonDashboardController($this->blade, $this->database),
                'internships' => new controllers\dashboard\InternshipDashboardController($this->blade, $this->database),
                'companies' => new controllers\dashboard\CompanyDashboardController($this->blade, $this->database),
                default => throw new Exception("Invalid collection $request->collection"),
            };
            $controller->setRouteParams($request->collection, $request->destination);
            return $controller->render();
        });

        $this->klein->respond(array('GET'), '/search', function ($request) {
            $controller = new controllers\SearchController($this->blade, $this->database);
            return $controller->render();
        });


        $this->klein->respond('GET', '/about', function () {
            $controller = new controllers\AboutController($this->blade, $this->database);
            return $controller->render();
        });

        $this->klein->respond('GET', '/legal-notice', function () {
            $controller = new controllers\LegalnoticeController($this->blade, $this->database);
            return $controller->render();
        });

        $this->klein->respond('GET', '/api/city/[i:zipcode]', function ($request) {
            $controller = new api\CityController($this->database);
            $controller->setRouteParams($request->zipcode);
            return $controller->render();
        });

        $this->klein->respond('GET', '/api/promotion/[i:campusId]', function ($request) {
            $controller = new api\PromotionController($this->database);
            $controller->setRouteParams($request->campusId);
            return $controller->render();
        });

        $this->klein->respond(array('POST', 'DELETE'), '/api/wishlist/[i:id]', function ($request) {
            $controller = new api\WishlistController($this->database);
            $controller->setRouteParams($request->id);
            return $controller->render();
        });

        $this->klein->respond('POST', '/api/validate-appliance', function () {
            $controller = new api\ApplianceValidationController($this->database);
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
            $errorController = new controllers\ErrorController($this->blade, $this->database);

            switch ($code) {
                case 404:
                    $errorController->setRouteParams($code, '404 Not Found 🥲', 'Impossible de trouver la page recherchée.');
                    $router->response()->body($errorController->render());
                    break;
                default:
                    $errorController->setRouteParams($code);
                    $router->response()->body($errorController->render());
                    break;
            }
        });

        $this->klein->onError(function ($router, $error, $instance, $trace) {
            $errorController = new controllers\ErrorController($this->blade, $this->database);
            $errorController->setRouteParams(500, '500 Internal Server Error 🥲', $error, $trace);
            $router->response()->body($errorController->render());
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
