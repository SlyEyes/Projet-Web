<?php

namespace Linkedout\App\controllers;

use Linkedout\App\models;

class LegalnoticeController extends BaseController
{
    public function render(): string
    {
        $personModel = new models\PersonModel($this->database);
        $person = $personModel->getPersonFromJwt();

        return $this->blade->make('pages.legalnotice', [
            'person' => $person
        ]);
    }
}
