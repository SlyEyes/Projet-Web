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
            $companyById = $companyModel->getCompanyById($search);
            $companyByName = $companyModel->getCompanyByName($search);
            $companyBySector = $companyModel->getCompanyBySector($search);
            $internshipById = $internshipModel->getInternshipById($search);
            $internshipByTitle = $internshipModel->getInternshipByTitle($search);

            return $this->blade->render('pages.search', [
                'companyById' => $companyById,
                'companyByName' => $companyByName,
                'companyBySector' => $companyBySector,
                'internshipById' => $internshipById,
                'internshipByTitle' => $internshipByTitle,
            ]);
        } else {
            return $this->blade->render('pages.search', [
                'error' => 'Veuillez entrer un nom de société ou de stage valide',
            ]);
        }
    }
}