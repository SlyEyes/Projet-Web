<?php

namespace Linkedout\App\controllers;

use Linkedout\App\models\CompanyModel;
use Linkedout\App\models\InternshipModel;

class SearchController extends BaseController
{
    public function render(): string
    {
        $companyModel = new CompanyModel($this->database);
        $internshipModel = new InternshipModel($this->database);

        $method = $_SERVER['REQUEST_METHOD'];
        $search = $_GET['q'];

        if ($method == 'GET' && !empty($search))
        {
            $companies = $companyModel->getCompaniesBySearch($search);

            return $this->blade->render('pages.search', [
                'companies' => $companies,
            ]);
        } else {
            return $this->blade->render('pages.search', [
                'error' => 'Veuillez entrer un nom de société ou de stage valide',
            ]);
        }
    }
}