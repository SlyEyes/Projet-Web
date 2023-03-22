<?php

namespace Linkedout\App\controllers;

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

        return $this->blade->render('pages.profile', [
            'person' => $person,
        ]);
    }
}
