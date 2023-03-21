<?php

namespace Linkedout\App\controllers;

use DateTime;
use Exception;
use Linkedout\App\models;

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

        try {
            $beginDate = new DateTime($internship->beginDate);
            $endDate = new DateTime($internship->endDate);
            $days = $beginDate->diff($endDate)->days;
            $months = round($days / 30);
            $formattedDuration = $months . ' mois';
        } catch (Exception) {
            $formattedDuration = 'Durée invalide';
        }

        return $this->blade->make('pages.internship', [
            'person' => $person,
            'internship' => $internship,
            'company' => $company,
            'formattedDuration' => $formattedDuration
        ])->render();
    }
}
