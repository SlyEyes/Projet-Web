<?php

namespace Linkedout\App\controllers;

use Linkedout\App\enums\RoleEnum;
use Linkedout\App\models;

class ProfileController extends BaseController
{
    public function render(): string
    {
        $personModel = new models\PersonModel($this->database);
        $person = $personModel->getPersonFromJwt();

        if ($person === null) {
            header("Location: /login?r=/profile");
            exit;
        }

        if ($person->role == RoleEnum::STUDENT) {
            $applianceModel = new models\ApplianceModel($this->database);
            $wishlist = $applianceModel->getWishlistByPersonId($person->id);
            $appliances = $applianceModel->getAppliancesByPersonId($person->id);

            $promotionModel = new models\PromotionModel($this->database);
            $promotion = $promotionModel->getPromotionForPersonId($person->id);
        }

        return $this->blade->render('pages.profile', [
            'person' => $person,
            'wishlist' => $wishlist ?? null,
            'appliances' => $appliances ?? null,
            'promotion' => $promotion ?? null,
        ]);
    }
}
