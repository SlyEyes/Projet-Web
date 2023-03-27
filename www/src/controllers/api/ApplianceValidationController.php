<?php

namespace Linkedout\App\controllers\api;

use Linkedout\App\enums\RoleEnum;
use Linkedout\App\models;

class ApplianceValidationController extends ApiController
{
    protected function fetch(): array
    {
        $personModel = new models\PersonModel($this->database);
        $person = $personModel->getPersonFromJwt();

        if ($person === null)
            throw new \Exception('Vous devez être connecté pour effectuer cette action');

        if ($person->role != RoleEnum::TUTOR && $person->role != RoleEnum::ADMINISTRATOR)
            throw new \Exception('Vous n\'avez pas les droits pour effectuer cette action');

        if (empty($_POST['student']) || empty($_POST['internship']))
            throw new \Exception('Les paramètres `student` et `internship` sont obligatoires');

        $studentId = $_POST['student'];
        $internshipId = $_POST['internship'];

        $applianceModel = new models\ApplianceModel($this->database);
        $appliance = $applianceModel->getApplianceById($studentId, $internshipId);

        if ($appliance == null)
            throw new \Exception('Application introuvable');

        if (!$appliance->applianceDate)
            throw new \Exception('L\'étudiant n\'a pas postulé à cette offre');

        if ($appliance->validation)
            throw new \Exception('Cette application a déjà été validée');

        $appliance->validation = true;
        $applianceModel->updateAppliance($appliance);

        return [
            'success' => true
        ];
    }
}