<?php

namespace Linkedout\App\controllers\dashboard;

use Exception;
use Linkedout\App\entities;
use Linkedout\App\enums\DashboardLayoutEnum;
use Linkedout\App\models;


class InternshipDashboardController extends BaseDashboardController
{

    protected function handleGet(?string $error = null): string
    {
        $internshipModel = new models\InternshipModel($this->database);
        $studentYearModel = new models\StudentYearModel($this->database);

        if ($this->layout == DashboardLayoutEnum::LIST)
            $data = $internshipModel->getAllInternships();

        if ($this->layout == DashboardLayoutEnum::EDIT) {
            $data = $internshipModel->getInternshipById($this->elementId);
            $internshipStudentYears = $studentYearModel->getStudentYearsForInternship($this->elementId);
            if (!empty($internshipStudentYears))
                $internshipStudentYearsIds = array_map(fn($studentYear) => $studentYear->id, $internshipStudentYears);
        }

        if ($this->layout == DashboardLayoutEnum::LIST || $this->layout == DashboardLayoutEnum::EDIT) {
            $companyModel = new models\CompanyModel($this->database);
            $companies = $companyModel->getAllCompanies();
            $studentYears = $studentYearModel->getStudentYears();
        }

        $pageTitle = $this->getPageTitle(
            !empty($data) && $data instanceof entities\InternshipEntity ? $data->title : null
        );

        return $this->blade->render('pages.dashboard', [
            'person' => $this->person,
            'pageTitle' => $pageTitle,
            'collection' => $this->collection->value,
            'layout' => $this->layout->value,
            'destination' => $this->elementId ?? null,
            'data' => $data ?? null,
            'error' => $error ?? null,
            'companies' => $companies ?? null,
            'studentYears' => $studentYears ?? null,
            'internshipStudentYearsIds' => $internshipStudentYearsIds ?? null
        ]);
    }

    protected function handlePost(): ?string
    {
        $internshipModel = new models\InternshipModel($this->database);
        $studentYearModel = new models\StudentYearModel($this->database);

        try {
            $newInternship = new entities\InternshipEntity();

            if ($this->layout == DashboardLayoutEnum::EDIT)
                $newInternship->id = (int)$this->elementId;
            $newInternship->title = $_POST['title'];
            $newInternship->description = $_POST['description'];
            $newInternship->skills = $_POST['skills'];
            $newInternship->salary = (int)$_POST['salary'];
            $newInternship->beginDate = $_POST['begin-date'];
            $newInternship->endDate = $_POST['end-date'];
            $newInternship->offerDate = date('Y-m-d');
            $newInternship->numberPlaces = (int)$_POST['places'];
            $newInternship->masked = !empty($_POST['masked']);
            $newInternship->city = new entities\CityEntity();
            $newInternship->city->id = (int)$_POST['cityId'];
            $newInternship->companyId = $_POST['companyId'];

            $this->database->beginTransaction();

            if ($this->layout == DashboardLayoutEnum::CREATE) {
                $newInternship->id = $internshipModel->createInternship($newInternship);
            } else {
                $internshipModel->updateInternship($newInternship);
                $studentYearModel->removeStudentYearsForInternship($newInternship->id);
            }

            for ($i = 0; $i < count($_POST['student-years'] ?? []); $i++)
                $studentYearModel->addStudentYearForInternship($newInternship->id, (int)$_POST['student-years'][$i]);

            $this->database->commit();
        } catch (Exception $e) {
            $this->database->rollBack();
            return 'Erreur lors de la création du stage : ' . $e->getMessage();
        }

        return null;
    }
}
