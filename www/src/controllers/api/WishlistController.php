<?php

namespace Linkedout\App\controllers\api;

use Linkedout\App\entities\ApplianceEntity;
use Linkedout\App\enums\RoleEnum;
use Linkedout\App\models;

class WishlistController extends ApiController
{
    protected int $internshipId;


    public function setRouteParams(int $internshipId)
    {
        $this->internshipId = $internshipId;
    }

    protected function fetch(): array
    {
        $personModel = new models\PersonModel($this->database);
        $person = $personModel->getPersonFromJwt();

        if ($person === null)
            throw new \Exception('User must be logged in');

        if ($person->role != RoleEnum::STUDENT)
            throw new \Exception('User must be a student');

        $applianceModel = new models\ApplianceModel($this->database);

        $existingAppliance = $applianceModel->getApplianceById($person->id, $this->internshipId);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Throws an error if the appliance already exists
            if (!empty($existingAppliance))
                throw new \Exception('Vous avez déjà ajouté ce stage à votre wishlist');

            // Test if the internship exists
            $internshipModel = new models\InternshipModel($this->database);
            $internship = $internshipModel->getInternshipById($this->internshipId);
            if (empty($internship))
                throw new \Exception('Le stage n\'existe pas');

            $newAppliance = new ApplianceEntity();
            $newAppliance->personId = $person->id;
            $newAppliance->internshipId = $this->internshipId;
            $newAppliance->wishDate = new \DateTime();

            $applianceModel->createAppliance($newAppliance);
        } else {
            if (empty($existingAppliance))
                throw new \Exception('Le stage n\'est pas dans la wishlist');

            if ($existingAppliance->personId != $person->id)
                throw new \Exception('Vous ne pouvez pas supprimer un stage qui ne vous appartient pas');

            if ($existingAppliance->applianceDate != null)
                throw new \Exception('Vous ne pouvez pas supprimer un stage où vous avez postulé');

            if ($existingAppliance->responseDate != null)
                throw new \Exception('Vous ne pouvez pas supprimer un stage où vous avez reçu une réponse');

            $applianceModel->deleteAppliance($person->id, $this->internshipId);
        }

        return [
            'success' => true,
        ];
    }
}
