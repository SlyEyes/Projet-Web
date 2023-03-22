<?php

namespace Linkedout\App\controllers;

use Linkedout\App\models;

class SearchController extends BaseController
{
    public function render(): string
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($_GET['q']) && isset($_GET['target'])) {
            $search = $_GET['q'];
            $target = $_GET['target'];
        } else {
            $search = null;
            $target = null;
        }

        $companyModel = new models\CompanyModel($this->database);
        $internshipModel = new models\InternshipModel($this->database);

        $personModel = new models\PersonModel($this->database);
        $person = $personModel->getPersonFromJwt();

        if ($person === null) {
            header("Location: /login?r=/search?q=$search&target=internships");
            exit;
        }

        if ($method == 'GET' && !empty($search)) {
            if ($target == 'internships')
                $results = $internshipModel->getInternshipsBySearch($search);
            else if ($target == 'companies')
                $results = $companyModel->getCompaniesBySearch($search);
            else
                $results = null;

            return $this->blade->render('pages.search', [
                'results' => $results,
                'search' => $search,
                'target' => $target,
                'person' => $person,
            ]);
        } else {
            return $this->blade->render('pages.search', [
                'error' => 'Veuillez entrer un nom de société ou de stage valide',
                'person' => $person,
            ]);
        }
    }
}