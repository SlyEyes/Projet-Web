<?php

namespace Linkedout\App\controllers\api;

use Linkedout\App\enums\RoleEnum;
use Linkedout\App\models;

class OfflineController extends ApiController
{

    /**
     * @inheritDoc
     */
    protected function fetch(): array
    {
        $personModel = new models\PersonModel($this->database);
        $person = $personModel->getPersonFromJwt();

        if ($person === null)
            throw new \Exception('Vous devez être connecté pour effectuer cette action');

        if ($person->role != RoleEnum::STUDENT)
            throw new \Exception('Vous n\'avez pas les droits pour effectuer cette action');

        $applianceModel = new models\ApplianceModel($this->database);
        $wishlist = $applianceModel->getWishlistByPersonId($person->id);
        $appliances = $applianceModel->getAppliancesByPersonId($person->id);
        $internships = $applianceModel->getAppliancesByPersonId($person->id, true);

        return [
            'person' => [
                'email' => $person->email,
                'firstName' => $person->firstName,
                'lastName' => $person->lastName,
                'role' => $person->role,
            ],
            'wishlist' => $wishlist,
            'appliances' => $appliances,
            'internships' => $internships,
        ];
    }
}
