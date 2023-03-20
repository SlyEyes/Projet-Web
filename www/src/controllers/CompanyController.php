<?php

namespace Linkedout\App\controllers;

use Linkedout\App\models;

class CompanyController extends BaseController
{
    protected ?string $id = null;

    /**
     * The setter for the route parameters
     * @param string|null $collection The ID. If null, the user will be redirected to the default collection
     * @return void
     */
    public function setRouteParams(?string $id): void
    {
        $this->id = $id;
    }

    public function render(): string
    {


        $personModel = new models\PersonModel($this->database);
        $person = $personModel->getPersonFromJwt();



        $companyModel = new models\CompanyModel($this->database);
        $company = $companyModel->getCompanyById($this->id);

        $internshipsmodel = new models\InternshipModel($this->database);
        $internships = $internshipsmodel->getInternshipsByCompanyId($this->id);


        return $this->blade->make('pages.company', [
            'person' => $person
        ]); 
    }
}