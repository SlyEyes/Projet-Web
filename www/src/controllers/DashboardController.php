<?php

namespace Linkedout\App\controllers;

use Exception;
use Linkedout\App\entities;
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
            'companies',
        ];

        $personModel = new models\PersonModel($this->database);
        $person = $personModel->getPersonFromJwt();

        $authorizationService = new services\AuthorizationService($person);
        $authorizationService->redirectIfNotAuthorized('/', RoleEnum::ADMINISTRATOR, RoleEnum::TUTOR);

        if ($this->collection === null || !in_array($this->collection, $AUTHORIZED_COLLECTIONS)) {
            header("Location: /dashboard/{$DEFAULT_COLLECTION}");
            exit;
        }

        $validCollectionID = is_numeric($this->destination) && $this->destination > 0;
        if (!empty($this->destination) && $this->destination !== 'new' && !$validCollectionID) {
            header("Location: /dashboard/{$this->collection}");
            exit;
        }

        if (($validCollectionID || $this->destination == 'new') && $_SERVER['REQUEST_METHOD'] === 'POST')
            $error = $this->handlePost();

        switch ($this->collection) {
            case 'students':
                if (empty($this->destination))
                    $data = $personModel->getAllPersons(roles: [RoleEnum::STUDENT]);
                elseif ($validCollectionID)
                    $data = $personModel->getPersonById($this->destination);
                break;
            case 'tutors':
                if (empty($this->destination))
                    $data = $personModel->getAllPersons(roles: [RoleEnum::TUTOR]);
                elseif ($validCollectionID)
                    $data = $personModel->getPersonById($this->destination);
                break;
            case 'administrators':
                if (empty($this->destination))
                    $data = $personModel->getAllPersons(roles: [RoleEnum::ADMINISTRATOR]);
                elseif ($validCollectionID)
                    $data = $personModel->getPersonById($this->destination);
                break;
            case 'internships':
                $internshipModel = new models\InternshipModel($this->database);
                if (empty($this->destination))
                    $data = $internshipModel->getAllInternships();
                elseif ($validCollectionID)
                    $data = $internshipModel->getInternshipById($this->destination);

                if ($validCollectionID || $this->destination == 'new') {
                    $companyModel = new models\CompanyModel($this->database);
                    $companies = $companyModel->getAllCompanies();
                }
                break;
            case 'companies':
                $companyModel = new models\CompanyModel($this->database);
                if (empty($this->destination))
                    $data = $companyModel->getAllCompanies();
                elseif ($validCollectionID)
                    $data = $companyModel->getCompanyById($this->destination);
                break;
        }

        $pageTitle = $validCollectionID && !empty($data)
            ? $this->getDynamicPageTitle($data)
            : $this->getStaticPageTitle();

        return $this->blade->render('pages.dashboard', [
            'person' => $person,
            'pageTitle' => $pageTitle,
            'collection' => $this->collection,
            'data' => $data ?? null,
            'destination' => $this->destination,
            'error' => $error ?? null,
            'companies' => $companies ?? null,
        ]);
    }

    /**
     * Handles the modifications to the database when the user submits a form
     * @return string|null The error message if an error occurred, otherwise it redirects the user to the dashboard collection
     */
    private function handlePost(): ?string
    {
        switch ($this->collection) {
            case 'students':
            case 'tutors':
            case 'administrators':
                $personModel = new models\PersonModel($this->database);

                try {
                    $newPerson = new entities\PersonEntity();

                    if ($this->destination != 'new')
                        $newPerson->id = (int)$this->destination;
                    $newPerson->firstName = $_POST['firstname'];
                    $newPerson->lastName = $_POST['lastname'];
                    $newPerson->email = $_POST['email'];
                    if (!empty($_POST['password']))
                        $newPerson->password = $_POST['password'];
                    $newPerson->role = RoleEnum::fromValue(substr($this->collection, 0, -1));

                    if ($this->destination == 'new')
                        $personModel->createPerson($newPerson);
                    else
                        $personModel->updatePerson($newPerson);
                } catch (Exception $e) {
                    return 'Erreur lors de la création de l\'utilisateur : ' . $e->getMessage();
                }
                break;
            case 'internships':
                break;
            case 'companies':
                break;
        }

        header("Location: /dashboard/{$this->collection}");
        exit;
    }

    /**
     * Returns the page title depending on the collection and the data.
     * It generates a title for the edit page
     * @param mixed $data The data to use to generate the title
     * @return string The page title
     */
    private function getDynamicPageTitle(mixed $data): string
    {
        return match ($this->collection) {
            'students', 'tutors', 'administrators' => 'Modification de ' . $data->firstName . ' ' . $data->lastName,
            'internships' => 'Modification de ' . $data->title,
            'companies' => 'Modification de ' . $data->name,
            default => 'Erreur',
        };
    }

    /**
     * Returns the page title depending on the collection and the destination.
     * It generates a title for the list and the new pages
     * @return string The page title
     */
    private function getStaticPageTitle(): string
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
            'companies' => [
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
