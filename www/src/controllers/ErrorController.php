<?php

namespace Linkedout\App\controllers;

use Linkedout\App\models\PersonModel;

class ErrorController extends BaseController
{
    protected ?int $statusCode;
    protected ?string $errorTitle;
    protected ?string $errorMessage;
    protected ?string $errorTrace;

    public function setRouteParams(?int $code = null, ?string $title = null, ?string $message = null, ?string $trace = null): void
    {
        $this->statusCode = $code ?? 400;
        $this->errorTitle = $title ?? 'Une erreur est survenue 🥲';
        $this->errorMessage = $message ?? 'Une erreur inconnue est survenue. Veuillez nous excuser pour la gêne occasionnée.';
        $this->errorTrace = $trace;
    }

    public function render(): string
    {
        try {
            $personModel = new PersonModel($this->database);
            $person = $personModel->getPersonFromJwt();
        } catch (\Exception) {
            $person = null;
        }

        http_response_code($this->statusCode);

        return $this->blade->render('pages.error', [
            'person' => $person,
            'title' => 'Erreur - LinkedOut',
            'errorTitle' => $this->errorTitle,
            'message' => $this->errorMessage,
            'trace' => $this->errorTrace,
        ]);
    }
}
