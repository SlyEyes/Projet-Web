<?php

namespace Linkedout\App\controllers\dashboard;

use Linkedout\App\controllers\BaseController;
use Linkedout\App\entities\PersonEntity;
use Linkedout\App\enums\DashboardCollectionEnum;
use Linkedout\App\enums\DashboardLayoutEnum;
use Linkedout\App\enums\RoleEnum;
use Linkedout\App\models\PersonModel;

abstract class BaseDashboardController extends BaseController
{
    protected DashboardCollectionEnum $collection;
    protected ?int $elementId = null;
    protected DashboardLayoutEnum $layout = DashboardLayoutEnum::LIST;
    protected PersonEntity $person;

    public function __construct($blade, $database)
    {
        parent::__construct($blade, $database);

        $personModel = new PersonModel($this->database);
        $person = $personModel->getPersonFromJwt();

        if ($person === null) {
            header('Location: /login');
            exit;
        }

        if ($person->role != RoleEnum::TUTOR && $person->role != RoleEnum::ADMINISTRATOR) {
            header('Location: /');
            exit;
        }

        $this->person = $person;
    }

    public function setRouteParams(string $collection, ?string $destination): void
    {
        $this->collection = DashboardCollectionEnum::fromValue($collection);

        if (is_numeric($destination)) {
            $this->elementId = (int)$destination;
            $this->layout = DashboardLayoutEnum::EDIT;
        }

        if ($destination === 'new')
            $this->layout = DashboardLayoutEnum::CREATE;
    }

    public function render(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $error = $this->handlePost();
            if ($error == null) {
                header('Location: /dashboard/' . $this->collection->value);
                exit;
            }
        }

        return $this->handleGet($error ?? null);
    }

    abstract protected function handlePost(): ?string;

    abstract protected function handleGet(?string $error = null): string;

    protected function getPageTitle(?string $element = null): string
    {
        $availableTitles = [
            DashboardCollectionEnum::STUDENTS->value => [
                DashboardLayoutEnum::LIST->value => 'Liste des étudiants',
                DashboardLayoutEnum::CREATE->value => 'Nouvel étudiant',
                DashboardLayoutEnum::EDIT->value => "Édition de $element"
            ],
            DashboardCollectionEnum::TUTORS->value => [
                DashboardLayoutEnum::LIST->value => 'Liste des tuteurs',
                DashboardLayoutEnum::CREATE->value => 'Nouveau tuteur',
                DashboardLayoutEnum::EDIT->value => "Édition de $element"
            ],
            DashboardCollectionEnum::ADMINISTRATORS->value => [
                DashboardLayoutEnum::LIST->value => 'Liste des administrateurs',
                DashboardLayoutEnum::CREATE->value => 'Nouvel administrateur',
                DashboardLayoutEnum::EDIT->value => "Édition de $element"
            ],
            DashboardCollectionEnum::COMPANIES->value => [
                DashboardLayoutEnum::LIST->value => 'Liste des entreprises',
                DashboardLayoutEnum::CREATE->value => 'Nouvelle entreprise',
                DashboardLayoutEnum::EDIT->value => "Édition de $element"
            ],
            DashboardCollectionEnum::INTERNSHIPS->value => [
                DashboardLayoutEnum::LIST->value => 'Liste des stages',
                DashboardLayoutEnum::CREATE->value => 'Nouveau stage',
                DashboardLayoutEnum::EDIT->value => "Édition de $element"
            ],
        ];

        return $availableTitles[$this->collection->value][$this->layout->value];
    }
}
