<?php

namespace Linkedout\App\controllers;

use Linkedout\App\models;
use Linkedout\App\utils\TimeUtil;

class InternshipController extends BaseController
{
    protected int $internshipId;

    public function setRouteParams(int $internshipId)
    {
        $this->internshipId = $internshipId;
    }

    public function render(): string
    {
        $personModel = new models\PersonModel($this->database);
        $person = $personModel->getPersonFromJwt();

        if ($person === null) {
            header("Location: /login?r=/internship/$this->internshipId");
            exit;
        }

        $internshipModel = new models\InternshipModel($this->database);
        $internship = $internshipModel->getInternshipById($this->internshipId);

        $companyModel = new models\CompanyModel($this->database);
        $company = $companyModel->getCompanyById($internship->companyId);

        $formattedDuration = TimeUtil::calculateDuration($internship->beginDate, $internship->endDate);

        $appliancesModel = new models\ApplianceModel($this->database);
        $appliance = $appliancesModel->getApplianceById($person->id, $this->internshipId);

        return $this->blade->make('pages.internship', [
            'person' => $person,
            'title' => $internship->title . ' - LinkedOut',
            'internship' => $internship,
            'company' => $company,
            'formattedDuration' => $formattedDuration,
            'appliance' => $appliance != null,
        ])->render();
    }
}
