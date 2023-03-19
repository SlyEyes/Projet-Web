<?php

namespace Linkedout\App\controllers;

use Linkedout\App\enums\RoleEnum;
use Linkedout\App\models;
use Linkedout\App\services;

/**
 * The dashboard controller is responsible for rendering all the pages related to the dashboard
 * @package Linkedout\App\controllers
 */
class DashboardController extends BaseController
{
    protected ?string $collection = null;
    protected ?string $destination = null;

    /**
     * The setter for the route parameters
     * @param string|null $collection The collection to show. If null, the user will be redirected to the default collection
     * @param string|null $destination The destination: either an ID or `new`
     * @return void
     */
    public function setRouteParams(?string $collection, ?string $destination): void
    {
        $this->collection = $collection;
        $this->destination = $destination;
    }

    public function render(): string
    {
        $DEFAULT_COLLECTION = 'students';
        $AUTHORIZED_COLLECTIONS = [
            'students',
            'tutors',
            'administrators',
            'internships',
            'enterprises',
        ];

        $personModel = new models\PersonModel($this->database);
        $person = $personModel->getPersonFromJwt();

        $authorizationService = new services\AuthorizationService($person);
        $authorizationService->redirectIfNotAuthorized('/', RoleEnum::ADMINISTRATOR, RoleEnum::TUTOR);

        if ($this->collection === null || !in_array($this->collection, $AUTHORIZED_COLLECTIONS)) {
            header("Location: /dashboard/{$DEFAULT_COLLECTION}");
            exit;
        }

        switch ($this->collection) {
            case 'students':
                $data = $personModel->getAllPersons(roles: [RoleEnum::STUDENT]);
                break;
            case 'tutors':
                $data = $personModel->getAllPersons(roles: [RoleEnum::TUTOR]);
                break;
            case 'administrators':
                $data = $personModel->getAllPersons(roles: [RoleEnum::ADMINISTRATOR]);
                break;
            case 'internships':
                $internshipModel = new models\InternshipModel($this->database);
                $data = $internshipModel->getAllInternships();
                break;
            case 'enterprises':
                $enterpriseModel = new models\CompanyModel($this->database);
                $data = $enterpriseModel->getAllEnterprises();
                break;
        }

        return $this->blade->render('pages.dashboard', [
            'person' => $person,
            'pageTitle' => $this->getPageTitle(),
            'collection' => $this->collection,
            'data' => $data,
            'destination' => $this->destination,
        ]);
    }

    private function getPageTitle(): string
    {
        $translations = [
            'students' => [
                'list' => 'Liste des étudiants',
                'new' => 'Nouvel étudiant',
            ],
            'tutors' => [
                'list' => 'Liste des tuteurs',
                'new' => 'Nouveau tuteur',
            ],
            'administrators' => [
                'list' => 'Liste des administrateurs',
                'new' => 'Nouvel administrateur',
            ],
            'internships' => [
                'list' => 'Liste des stages',
                'new' => 'Nouveau stage',
            ],
            'enterprises' => [
                'list' => 'Liste des entreprises',
                'new' => 'Nouvelle entreprise',
            ],
        ];

        if ($this->destination === 'new') {
            return $translations[$this->collection]['new'];
        } else {
            return $translations[$this->collection]['list'];
        }
    }
}
