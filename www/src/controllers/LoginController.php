<?php

namespace Linkedout\App\controllers;

use Linkedout\App\models;

class LoginController extends BaseController
{
    public function render(): string
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method == 'GET')
            return $this->blade->make('pages.login');

        if (empty($_POST['email']) || empty($_POST['password']))
            return $this->blade->make('pages.login', [
                'error' => 'Vérifiez la présence des champs email et password',
            ]);

        $email = $_POST['email'];
        $password = $_POST['password'];

        $personModel = new models\PersonModel($this->database);

        $person = $personModel->getPersonByEmail($email);

        if (empty($person) || !password_verify($password, $person->password))
            return $this->blade->make('pages.login', [
                'error' => 'Email ou mot de passe incorrect',
                'email' => $email,
            ]);

        $redirect = '/';

        http_response_code(302);
        header("Location: {$redirect}");
        die();
    }
}
