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

        $campusModel = new models\CampusModel($this->database);
        $promotionModel = new models\PromotionModel($this->database);
        $studentYearModel = new models\StudentYearModel($this->database);

        switch ($this->collection) {
            case 'students':
                if (empty($this->destination))
                    $data = $personModel->getAllPersons(roles: [RoleEnum::STUDENT]);
                elseif ($validCollectionID)
                    $data = $personModel->getPersonById($this->destination);
                if ($validCollectionID || $this->destination == 'new')
                    $campuses = $campusModel->getAllCampuses();
                if ($validCollectionID) {
                    $personPromotion = $promotionModel->getPromotionForStudentId($this->destination);
                    $promotions = $promotionModel->getPromotionForCampusId($personPromotion->campusId);
                }
                break;
            case 'tutors':
                if (empty($this->destination))
                    $data = $personModel->getAllPersons(roles: [RoleEnum::TUTOR]);
                elseif ($validCollectionID) {
                    $data = $personModel->getPersonById($this->destination);
                    $personCampus = $campusModel->getCampusForPersonId($this->destination);
                    if ($personCampus != null) {
                        $promotions = $promotionModel->getAvailablePromotionsForTutor($personCampus->id, $this->destination);
                        $personPromotion = $promotionModel->getPromotionsForTutorId($this->destination);
                        $personPromotion = array_map(fn($promotion) => $promotion->id, $personPromotion);
                    }
                }
                if ($validCollectionID || $this->destination == 'new') {
                    $campuses = $campusModel->getAllCampuses();
                }
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
                    $studentYears = $studentYearModel->getStudentYears();
                }

                if ($validCollectionID) {
                    $internshipStudentYears = $studentYearModel->getStudentYearsForInternship($this->destination);
                    if (!empty($internshipStudentYears))
                        $internshipStudentYearsIds = array_map(fn($studentYear) => $studentYear->id, $internshipStudentYears);
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
            'campuses' => $campuses ?? null,
            'personCampus' => $personCampus ?? null,
            'personPromotion' => $personPromotion ?? null,
            'promotions' => $promotions ?? null,
            'studentYears' => $studentYears ?? null,
            'internshipStudentYearsIds' => $internshipStudentYearsIds ?? null,
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
            $promotionModel = new models\PromotionModel($this->database);

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

                    $this->database->beginTransaction();

                    if ($this->destination == 'new') {
                        $newPerson->id = $personModel->createPerson($newPerson);
                        if ($this->collection == 'students')
                            $promotionModel->setPromotionForStudentId($newPerson->id, (int)$_POST['promotion']);
                        elseif ($this->collection == 'tutors')
                            foreach ($_POST['promotions'] as $promotionId)
                                $promotionModel->addPromotionForTutorId($newPerson->id, (int)$promotionId);
                    } else {
                        $personModel->updatePerson($newPerson);
                        if ($this->collection == 'students') {
                            $personPromotion = $promotionModel->getPromotionForStudentId($newPerson->id);
                            if ($personPromotion->id != (int)$_POST['promotion']) {
                                $promotionModel->removePromotionForStudentId($newPerson->id, $personPromotion->id);
                                $promotionModel->setPromotionForStudentId($newPerson->id, (int)$_POST['promotion']);
                            }
                        } elseif ($this->collection == 'tutors') {
                            $promotionModel->removePromotionsForTutorId($newPerson->id);
                            foreach ($_POST['promotions'] as $promotionId)
                                $promotionModel->addPromotionForTutorId($newPerson->id, (int)$promotionId);
                        }
                    }

                    $this->database->commit();
                } catch (Exception $e) {
                    $this->database->rollBack();
                    return 'Erreur lors de la création de l\'utilisateur : ' . $e->getMessage();
                }
            break;
            case 'internships':
                $internshipModel = new models\InternshipModel($this->database);
                $studentYearModel = new models\StudentYearModel($this->database);

                try {
                    $newInternship = new entities\InternshipEntity();

                    if ($this->destination != 'new')
                        $newInternship->id = (int)$this->destination;
                    $newInternship->title = $_POST['title'];
                    $newInternship->description = $_POST['description'];
                    $newInternship->skills = $_POST['skills'];
                    $newInternship->salary = (int)$_POST['salary'];
                    $newInternship->beginDate = $_POST['begin-date'];
                    $newInternship->endDate = $_POST['end-date'];
                    $newInternship->offerDate = date('Y-m-d');
                    $newInternship->numberPlaces = (int)$_POST['places'];
                    $newInternship->masked = !empty($_POST['masked']);
                    $newInternship->city = new entities\CityEntity();
                    $newInternship->city->id = (int)$_POST['cityId'];
                    $newInternship->companyId = $_POST['companyId'];

                    $this->database->beginTransaction();

                    if ($this->destination == 'new') {
                        $newInternship->id = $internshipModel->createInternship($newInternship);
                    } else {
                        $internshipModel->updateInternship($newInternship);
                        $studentYearModel->removeStudentYearsForInternship($newInternship->id);
                    }

                    for ($i = 0; $i < count($_POST['student-years'] ?? []); $i++)
                        $studentYearModel->addStudentYearForInternship($newInternship->id, (int)$_POST['student-years'][$i]);

                    $this->database->commit();
                } catch (Exception $e) {
                    $this->database->rollBack();
                    return 'Erreur lors de la création du stage : ' . $e->getMessage();
                }
                break;
            case 'companies':
                $companyModel = new models\CompanyModel($this->database);

                try {
                    $newCompany = new entities\CompanyEntity();

                    if ($this->destination != 'new')
                        $newCompany->id = (int)$this->destination;
                    $newCompany->name = $_POST['name'];
                    $newCompany->logo = $_POST['logo'];
                    $newCompany->sector = $_POST['sector'];
                    $newCompany->website = $_POST['website'];
                    $newCompany->email = $_POST['email'];
                    $newCompany->masked = !empty($_POST['masked']);

                    if ($this->destination == 'new')
                        $companyModel->createCompany($newCompany);
                    else
                        $companyModel->updateCompany($newCompany);
                } catch (Exception $e) {
                    return 'Erreur lors de la création de l\'entreprise : ' . $e->getMessage();
                }
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
