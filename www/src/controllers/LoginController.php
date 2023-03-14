<?php

namespace Linkedout\App\controllers;

use Linkedout\App\models;
use Linkedout\App\services;

class LoginController extends BaseController
{
    public function render(): string
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $personModel = new models\PersonModel($this->database);

        if ($method == 'GET') {
            $person = $personModel->getPersonFromJwt();

            return $this->blade->make('pages.login', [
                'person' => $person
            ]);
        }

        if (empty($_POST['email']) || empty($_POST['password']))
            return $this->blade->make('pages.login', [
                'error' => 'Vérifiez la présence des champs email et password',
            ]);

        $email = $_POST['email'];
        $password = $_POST['password'];


        $person = $personModel->getPersonByEmail($email);

        if (empty($person) || !password_verify($password, $person->password))
            return $this->blade->make('pages.login', [
                'error' => 'Email ou mot de passe incorrect',
                'email' => $email,
            ]);

        $redirect = '/profile';

        $jwtService = new services\JwtService();
        $token = $jwtService->generateToken(['id' => $person->id]);

        setcookie('TOKEN', $token, time() + 3600 * 24 * 7, '/', '', true, true);

        http_response_code(302);
        header("Location: {$redirect}");
        die();
    }
}
