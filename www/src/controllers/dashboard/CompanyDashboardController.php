<?php

namespace Linkedout\App\controllers\dashboard;

use Exception;
use Linkedout\App\entities;
use Linkedout\App\enums\DashboardLayoutEnum;
use Linkedout\App\models;

class CompanyDashboardController extends BaseDashboardController
{

    protected function handleGet(?string $error = null): string
    {
        $companyModel = new models\CompanyModel($this->database);
        if ($this->layout === DashboardLayoutEnum::LIST)
            $data = $companyModel->getAllCompanies();

        if ($this->layout === DashboardLayoutEnum::EDIT)
            $data = $companyModel->getCompanyById($this->elementId);

        $pageTitle = $this->getPageTitle(
            !empty($data) && $data instanceof entities\CompanyEntity ? $data->name : null
        );

        return $this->blade->render('pages.dashboard', [
            'person' => $this->person,
            'pageTitle' => $pageTitle,
            'collection' => $this->collection->value,
            'layout' => $this->layout->value,
            'destination' => $this->elementId ?? null,
            'data' => $data ?? null,
            'error' => $error ?? null,
        ]);
    }

    protected function handlePost(): ?string
    {
        $companyModel = new models\CompanyModel($this->database);

        try {
            $newCompany = new entities\CompanyEntity();

            if ($this->layout === DashboardLayoutEnum::EDIT)
                $newCompany->id = $this->elementId;
            $newCompany->name = $_POST['name'];
            $newCompany->logo = $_POST['logo'];
            $newCompany->sector = $_POST['sector'];
            $newCompany->website = $_POST['website'];
            $newCompany->email = $_POST['email'];
            $newCompany->cesiStudents = $_POST['cesi-students'];
            $newCompany->trustRating = $_POST['trust-rating'];
            $newCompany->masked = !empty($_POST['masked']);

            if ($this->layout === DashboardLayoutEnum::CREATE)
                $companyModel->createCompany($newCompany);
            else
                $companyModel->updateCompany($newCompany);

        } catch (Exception $e) {
            return 'Erreur lors de la création de l\'entreprise : ' . $e->getMessage();
        }

        return null;
    }

    protected function handleDelete(): ?string
    {
        return 'Not implemented yet';
    }
}
