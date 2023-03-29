<?php

namespace Linkedout\App\controllers\dashboard;

use Exception;
use Linkedout\App\entities;
use Linkedout\App\enums\DashboardCollectionEnum;
use Linkedout\App\enums\DashboardLayoutEnum;
use Linkedout\App\enums\RoleEnum;
use Linkedout\App\models;

class PersonDashboardController extends BaseDashboardController
{
    /**
     * Redirects to the dashboard if a tutor is trying to view or edit tutors or administrators
     * @return void
     */
    private function redirectIfNotAuthorized(): void
    {
        if ($this->person->role != RoleEnum::ADMINISTRATOR
            && ($this->collection == DashboardCollectionEnum::TUTORS
                || $this->collection == DashboardCollectionEnum::ADMINISTRATORS)) {
            header('Location: /dashboard');
            exit;
        }
    }

    function handleGet(?string $error = null): string
    {
        $this->redirectIfNotAuthorized();

        $campusModel = new models\CampusModel($this->database);
        $promotionModel = new models\PromotionModel($this->database);
        $personModel = new models\PersonModel($this->database);
        $applianceModel = new models\ApplianceModel($this->database);

        if ($this->layout == DashboardLayoutEnum::EDIT) {
            $data = $personModel->getPersonById($this->elementId);

            if ($data == null)
                return $this->blade->render('pages.error', [
                    'person' => $this->person,
                    'message' => 'La personne demandée n\'existe pas.'
                ]);

            if ($data->role != RoleEnum::fromValue(substr($this->collection->value, 0, -1))) {
                header("Location: /dashboard/{$data->role->value}s/$this->elementId");
                exit;
            }
        }

        switch ($this->collection) {
            case DashboardCollectionEnum::STUDENTS:
                if ($this->layout == DashboardLayoutEnum::LIST)
                    $data = $personModel->getAllPersons(roles: [RoleEnum::STUDENT]);

                if ($this->layout == DashboardLayoutEnum::EDIT) {
                    $personPromotion = $promotionModel->getPromotionForStudentId($this->elementId);
                    $promotions = $promotionModel->getPromotionForCampusId($personPromotion->campusId);
                    $appliances = $applianceModel->getAppliancesByPersonId($this->elementId);
                }

                if ($this->layout == DashboardLayoutEnum::CREATE || $this->layout == DashboardLayoutEnum::EDIT)
                    $campuses = $campusModel->getAllCampuses();
                break;

            case DashboardCollectionEnum::TUTORS:
                if ($this->layout == DashboardLayoutEnum::LIST)
                    $data = $personModel->getAllPersons(roles: [RoleEnum::TUTOR]);

                if ($this->layout == DashboardLayoutEnum::EDIT) {
                    $personCampus = $campusModel->getCampusForPersonId($this->elementId);
                    if ($personCampus != null) {
                        $promotions = $promotionModel->getAvailablePromotionsForTutor($personCampus->id, $this->elementId);
                        $personPromotion = $promotionModel->getPromotionsForTutorId($this->elementId);
                        $personPromotion = array_map(fn($promotion) => $promotion->id, $personPromotion);
                    }
                }

                if ($this->layout == DashboardLayoutEnum::CREATE || $this->layout == DashboardLayoutEnum::EDIT) {
                    $campuses = $campusModel->getAllCampuses();
                }
                break;

            case DashboardCollectionEnum::ADMINISTRATORS:
                if ($this->layout == DashboardLayoutEnum::LIST)
                    $data = $personModel->getAllPersons(roles: [RoleEnum::ADMINISTRATOR]);
                break;
        }

        $pageTitle = $this->getPageTitle(
            !empty($data) && $data instanceof entities\PersonEntity ? $data->firstName . ' ' . $data->lastName : null
        );

        return $this->blade->render('pages.dashboard', [
            'person' => $this->person,
            'pageTitle' => $pageTitle,
            'collection' => $this->collection->value,
            'layout' => $this->layout->value,
            'destination' => $this->elementId ?? null,
            'data' => $data ?? null,
            'error' => $error ?? null,
            'campuses' => $campuses ?? null,
            'personCampus' => $personCampus ?? null,
            'personPromotion' => $personPromotion ?? null,
            'promotions' => $promotions ?? null,
            'appliances' => $appliances ?? null,
        ]);
    }


    function handlePost(): ?string
    {
        $this->redirectIfNotAuthorized();

        $personModel = new models\PersonModel($this->database);
        $promotionModel = new models\PromotionModel($this->database);

        try {
            if ($this->layout == DashboardLayoutEnum::EDIT)
                $newPerson = $personModel->getPersonById($this->elementId);
            else
                $newPerson = new entities\PersonEntity();

            if ($this->layout == DashboardLayoutEnum::EDIT)
                $newPerson->id = $this->elementId;
            $newPerson->firstName = $_POST['firstname'];
            $newPerson->lastName = $_POST['lastname'];
            $newPerson->email = $_POST['email'];
            if (!empty($_POST['password']))
                $newPerson->password = $_POST['password'];
            $newPerson->role = RoleEnum::fromValue(substr($this->collection->value, 0, -1));

            $this->database->beginTransaction();

            if ($this->layout == DashboardLayoutEnum::CREATE) {
                $newPerson->id = $personModel->createPerson($newPerson);
                if ($this->collection == DashboardCollectionEnum::STUDENTS)
                    $promotionModel->setPromotionForStudentId($newPerson->id, (int)$_POST['promotion']);
                elseif ($this->collection == DashboardCollectionEnum::TUTORS)
                    foreach ($_POST['promotions'] as $promotionId)
                        $promotionModel->addPromotionForTutorId($newPerson->id, (int)$promotionId);
            } else {
                $personModel->updatePerson($newPerson);
                if ($this->collection == DashboardCollectionEnum::STUDENTS) {
                    $personPromotion = $promotionModel->getPromotionForStudentId($newPerson->id);
                    if ($personPromotion->id != (int)$_POST['promotion']) {
                        $promotionModel->removePromotionForStudentId($newPerson->id, $personPromotion->id);
                        $promotionModel->setPromotionForStudentId($newPerson->id, (int)$_POST['promotion']);
                    }
                } elseif ($this->collection == DashboardCollectionEnum::TUTORS) {
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

        return null;
    }

    protected function handleDelete(): ?string
    {
        $promotionModel = new models\PromotionModel($this->database);
        $personModel = new models\PersonModel($this->database);
        $applianceModel = new models\ApplianceModel($this->database);

        if ($this->collection != DashboardCollectionEnum::STUDENTS && $this->collection != DashboardCollectionEnum::TUTORS)
            return 'Seuls les étudiants et les tuteurs peuvent être supprimés';


        if ($this->collection == DashboardCollectionEnum::STUDENTS) {
            $invalidAppliances = $applianceModel->getAppliancesByPersonId($this->elementId);
            if (!empty($invalidAppliances) && count($invalidAppliances) > 0)
                return 'Impossible de supprimer un étudiant qui a postulé à des stages';

            $validAppliances = $applianceModel->getAppliancesByPersonId($this->elementId, true);
            if (!empty($validAppliances) && count($validAppliances) > 0)
                return 'Impossible de supprimer un étudiant qui a été accepté sur un stage';

            $this->database->beginTransaction();

            try {
                $personPromotion = $promotionModel->getPromotionForStudentId($this->elementId);
                if (!empty($personPromotion))
                    $promotionModel->removePromotionForStudentId($this->elementId, $personPromotion->id);

                $wishlist = $applianceModel->getWishlistByPersonId($this->elementId);
                if (!empty($wishlist))
                    foreach ($wishlist as $wish)
                        $applianceModel->deleteAppliance($wish->personId, $wish->internshipId);

                $personModel->deletePerson($this->elementId);

                $this->database->commit();
            } catch (Exception $e) {
                $this->database->rollBack();
                return 'Erreur lors de la suppression de l\'étudiant : ' . $e->getMessage();
            }
        } else {
            $this->database->beginTransaction();

            try {
                $promotionModel->removePromotionsForTutorId($this->elementId);
                $personModel->deletePerson($this->elementId);
                $this->database->commit();
            } catch (Exception $e) {
                $this->database->rollBack();
                return 'Erreur lors de la suppression du tuteur : ' . $e->getMessage();
            }
        }

        return null;
    }
}
