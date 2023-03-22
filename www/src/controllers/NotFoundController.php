<?php

namespace Linkedout\App\controllers;

use Linkedout\App\models\PersonModel;

class NotFoundController extends BaseController
{
    public function render(): string
    {
        $personModel = new PersonModel($this->database);
        $person = $personModel->getPersonFromJwt();

        http_response_code(404);

        return $this->blade->render('pages.error', [
            'person' => $person,
            'title' => '404 - LinkedOut',
            'errorTitle' => '404 Not Found 🥲',
            'message' => 'Impossible de trouver la page recherchée.',
        ]);
    }
}
