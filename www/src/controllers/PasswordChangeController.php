<?php

namespace Linkedout\App\controllers;

use Linkedout\App\entities\PersonEntity;
use Linkedout\App\models;

class PasswordChangeController extends BaseController
{
    public function render(): string
    {
        $personModel = new models\PersonModel($this->database);
        $person = $personModel->getPersonFromJwt();

        if (empty($person)) {
            header('Location: /login');
            exit;
        }

        if ($person->passwordChanged) {
            header('Location: /');
            exit;
        }

        if (!empty($_POST['password']) && !empty($_POST['password-confirm'])) {
            $error = $this->updatePassword($person, $_POST['password'], $_POST['password-confirm']);
        }

        return $this->blade->render('pages.password-change', [
            'person' => $person,
            'redirect' => $_GET['r'] ?? null,
            'error' => $error ?? null,
        ]);
    }

    private function updatePassword(PersonEntity $person, string $password, string $confirm): ?string
    {
        if ($password != $confirm)
            return 'Les mots de passe ne correspondent pas';

        if (strlen($password) < 8)
            return 'Le mot de passe doit faire au moins 8 caractères';

        if (password_verify($password, $person->password))
            return 'Le mot de passe doit être différent de l\'ancien';

        $personModel = new models\PersonModel($this->database);
        $person->password = $password;
        $person->passwordChanged = true;
        $personModel->updatePerson($person);

        if (!empty($_POST['redirect']) && str_starts_with($_POST['redirect'], '/'))
            $redirect = $_POST['redirect'];

        http_response_code(302);
        header('Location: ' . ($redirect ?? '/profile'));
        die();
    }
}
