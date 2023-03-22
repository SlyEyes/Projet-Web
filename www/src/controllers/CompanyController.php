<?php

namespace Linkedout\App\controllers;

use Linkedout\App\models;
use Linkedout\App\utils\TimeUtil;

class CompanyController extends BaseController
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

        $companyModel = new models\CompanyModel($this->database);
        $company = $companyModel->getCompanyById($this->id);

        $internshipModel = new models\InternshipModel($this->database);
        $internships = $internshipModel->getInternshipsByCompanyId($this->id);

        $cities = array_map(fn($internship) => $internship->city->name, $internships);
        $cities = array_unique($cities);
        $cities = implode(', ', $cities);

        $internships = array_map(fn($internship) => [
            'id' => $internship->id,
            'title' => $internship->title,
            'city' => $internship->city,
            'duration' => TimeUtil::calculateDuration($internship->beginDate, $internship->endDate),
        ], $internships);

        return $this->blade->make('pages.company', [
            'person' => $person,
            'title' => $company->name . ' - LinkedOut',
            'company' => $company,
            'internships' => $internships,
            'cities' => $cities,
        ]);
    }
}
