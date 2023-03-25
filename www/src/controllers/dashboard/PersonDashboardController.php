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
    function handleGet(?string $error = null): string
    {
        $campusModel = new models\CampusModel($this->database);
        $promotionModel = new models\PromotionModel($this->database);
        $personModel = new models\PersonModel($this->database);

        switch ($this->collection) {
            case DashboardCollectionEnum::STUDENTS:
                if ($this->layout == DashboardLayoutEnum::LIST)
                    $data = $personModel->getAllPersons(roles: [RoleEnum::STUDENT]);

                if ($this->layout == DashboardLayoutEnum::EDIT) {
                    $data = $personModel->getPersonById($this->elementId);
                    $personPromotion = $promotionModel->getPromotionForStudentId($this->elementId);
                    $promotions = $promotionModel->getPromotionForCampusId($personPromotion->campusId);
                }

                if ($this->layout == DashboardLayoutEnum::CREATE || $this->layout == DashboardLayoutEnum::EDIT)
                    $campuses = $campusModel->getAllCampuses();
                break;

            case DashboardCollectionEnum::TUTORS:
                if ($this->layout == DashboardLayoutEnum::LIST)
                    $data = $personModel->getAllPersons(roles: [RoleEnum::TUTOR]);

                if ($this->layout == DashboardLayoutEnum::EDIT) {
                    $data = $personModel->getPersonById($this->elementId);
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

                if ($this->layout == DashboardLayoutEnum::EDIT)
                    $data = $personModel->getPersonById($this->elementId);
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
        ]);
    }


    function handlePost(): ?string
    {
        $personModel = new models\PersonModel($this->database);
        $promotionModel = new models\PromotionModel($this->database);

        try {
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
}
