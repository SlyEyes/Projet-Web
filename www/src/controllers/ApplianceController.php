<?php

namespace Linkedout\App\controllers;

use Linkedout\App\entities;
use Linkedout\App\enums\RoleEnum;
use Linkedout\App\models;

class ApplianceController extends BaseController
{
    protected ?string $applianceId = null;

    protected ?entities\InternshipEntity $internship = null;
    protected ?entities\ApplianceEntity $appliance = null;
    protected ?entities\CompanyEntity $company = null;
    protected ?entities\PersonEntity $person = null;
    protected ?entities\RatingEntity $rating = null;

    /**
     * The setter for the route parameters
     * @param string|null $id The ID. If null, the user will be redirected to the default collection
     * @return void
     */
    public function setRouteParams(?string $id): void
    {
        $this->applianceId = $id;
    }

    public function render(): string
    {
        $personModel = new models\PersonModel($this->database);
        $this->person = $personModel->getPersonFromJwt();

        if ($this->person == null) {
            header("Location: /login?r=/internship/$this->applianceId/apply");
            exit;
        }

        if ($this->person->role != RoleEnum::STUDENT) {
            header("Location: /dashboard/internships/$this->applianceId");
            exit;
        }

        $internshipModel = new models\InternshipModel($this->database);
        $this->internship = $internshipModel->getInternshipById($this->applianceId);

        if ($this->internship == null || $this->internship->masked)
            return $this->blade->make('pages.error', [
                'person' => $this->person,
                'title' => 'Stage introuvable - LinkedOut',
                'message' => 'Impossible de trouver ce stage.'
            ]);

        $companyModel = new models\CompanyModel($this->database);
        $this->company = $companyModel->getCompanyById($this->internship->companyId);

        $applianceModel = new models\ApplianceModel($this->database);
        $this->appliance = $applianceModel->getApplianceById($this->person->id, $this->internship->id);

        $ratingModel = new models\RatingModel($this->database);
        if ($this->appliance && $this->appliance->validation)
            $this->rating = $ratingModel->getRatingForUserInternship($this->person->id, $this->internship->id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $postulate = !empty($_POST['postulate']) && $_POST['postulate'] == 'on';
            $response = !empty($_POST['response']) && $_POST['response'] == 'on';
            $rate = !empty($_POST['rate']) ? (int)$_POST['rate'] : null;

            if (!$postulate && !$response && !$rate)
                $error = 'Aucune action spécifiée.';

            // check if more than one action is specified
            if ($postulate + $response + !!$rate > 1)
                $error = 'Vous ne pouvez pas effectuer plusieurs action à la fois.';

            if (empty($error) && $postulate)
                $error = $this->handleAppliance();

            if (empty($error) && $response)
                $error = $this->handleResponse();

            if (empty($error) && $rate)
                $error = $this->handleRating($rate);
        }

        return $this->blade->make('pages.appliance', [
            'person' => $this->person,
            'company' => $this->company,
            'internship' => $this->internship,
            'appliance' => $this->appliance,
            'rating' => $this->rating,
            'error' => $error ?? null,
        ]);
    }

    /**
     * Handles the creation of the appliance. If an appliance was already created due to a wish, it will be updated
     * @return string|null The error message, or null if no error
     */
    private function handleAppliance(): ?string
    {
        // Check if user already postulated
        if ($this->appliance != null && $this->appliance->applianceDate != null)
            return 'Vous avez déjà postulé à ce stage.';

        // Create or update appliance
        $applianceModel = new models\ApplianceModel($this->database);

        $newAppliance = $this->appliance == null;
        if ($newAppliance)
            $this->appliance = new entities\ApplianceEntity();
        $this->appliance->personId = $this->person->id;
        $this->appliance->internshipId = $this->internship->id;
        $this->appliance->wishDate = new \DateTime();
        $this->appliance->applianceDate = new \DateTime();
        if ($newAppliance)
            $applianceModel->createAppliance($this->appliance);
        else
            $applianceModel->updateAppliance($this->appliance);

        return null;
    }

    /**
     * Handles the response reporting of the appliance
     * @return string|null The error message, or null if no error
     */
    private function handleResponse(): ?string
    {
        // Check if user already postulated
        if ($this->appliance == null || $this->appliance->applianceDate == null)
            return 'Vous n\'avez pas postulé à ce stage.';

        // Check if user already signaled a response
        if ($this->appliance->responseDate != null)
            return 'Vous avez déjà signalé une réponse de ce stage.';

        // Create or update appliance
        $applianceModel = new models\ApplianceModel($this->database);

        $this->appliance->responseDate = new \DateTime();
        $applianceModel->updateAppliance($this->appliance);

        return null;
    }

    /**
     * Handles the rating of the internship
     * @param int $rate The rating
     * @return string|null The error message, or null if no error
     */
    private function handleRating(int $rate): ?string
    {
        if ($rate < 1 || $rate > 5)
            return 'La note doit être comprise entre 1 et 5.';

        $ratingModel = new models\RatingModel($this->database);

        if ($this->rating) {
            $this->rating->rating = $rate;
            $ratingModel->updateRating($this->rating, $this->person->id, $this->internship->id);
        } else {
            $this->rating = new entities\RatingEntity();
            $this->rating->rating = $rate;
            $ratingModel->createRating($this->rating, $this->person->id, $this->internship->id);
        }

        return null;
    }
}
