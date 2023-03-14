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

        return $this->blade->render('pages.404', [
            'person' => $person,
        ]);
    }
}
