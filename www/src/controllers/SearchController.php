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
        $search = $_POST['search'];

        if ($method == 'GET' && !empty($search))
        {
            $companies = $companyModel->getCompanyById($search);
            $companies = $companyModel->getCompanyByName($search);
            $internships = $internshipModel->getInternshipById($search);

            return $this->blade->render('pages.search', [
                'companies' => $companies,
                'internships' => $internships,
            ]);
        } else {
            return $this->blade->render('pages.search', [
                'error' => 'Veuillez entrer un nom de société ou de stage valide',
            ]);
        }
    }
}