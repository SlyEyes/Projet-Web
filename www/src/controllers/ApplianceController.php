<?php

namespace Linkedout\App\controllers;

use Linkedout\App\models;
use Linkedout\App\utils\TimeUtil;

class ApplianceController extends BaseController
{
    protected ?string $id = null;

    /**
     * The setter for the route parameters
     * @param string|null $id The ID. If null, the user will be redirected to the default collection
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


        $internshipModel = new models\InternshipModel($this->database);
        $internship = $internshipModel->getInternshipById($this->id);

        $companyModel = new models\CompanyModel($this->database);
        $company = $companyModel->getCompanyById($internship->companyId);

        $internship = [
            'id' => $internship->id,
            'title' => $internship->title,
            'city' => $internship->city,
            'duration' => TimeUtil::calculateDuration($internship->beginDate, $internship->endDate),
        ];

        return $this->blade->make('pages.appliance', [
            'person' => $person,
            'company' => $company,
            'internship' => $internship,
            'cities' => $cities
        ]);
    }
}