<?php

namespace Linkedout\App\controllers;

use Linkedout\App\models;

class SearchController extends BaseController
{
    public function render(): string
    {
        $complete_url = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        $page = (int) ($_GET['page'] ?? 1);
        $limit = 4;
        $firstResult = ($page - 1) * $limit;

        $search = $_GET['q'] ?? null;
        $target = $_GET['target'] ?? 'internships';
        $f = str_split($_GET['f'] ?? '19');

        $companyModel = new models\CompanyModel($this->database);
        $internshipModel = new models\InternshipModel($this->database);

        $personModel = new models\PersonModel($this->database);
        $person = $personModel->getPersonFromJwt();

        if ($page < 1)
            $page = 1;

        $url = str_replace("&page=$page", '', $complete_url);

        if ($person === null) {
            header("Location: /login?r=/search?q=$search&target=internships");
            exit;
        }

        if ($method == 'GET' && !empty($search)) {
            if ($target == 'internships')
                $results = $internshipModel->getInternshipsBySearch($search, $limit, $firstResult, $f);
            else if ($target == 'companies')
                $results = $companyModel->getCompaniesBySearch($search, $limit, $firstResult);
            else
                $results = null;

            return $this->blade->render('pages.search', [
                'url' => $url,
                'page' => $page,
                'search' => $search,
                'target' => $target,
                'results' => $results,
                'person' => $person,
            ]);
        } else {
            return $this->blade->render('pages.search', [
                'url' => $url,
                'page' => $page,
                'results' => null,
                'person' => $person,
            ]);
        }
    }
}