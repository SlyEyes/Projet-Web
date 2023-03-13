<?php

namespace Linkedout\App\controllers;

class LoginController extends BaseController
{
    public function render(): string
    {
        return $this->blade->make('pages.login');
    }
}
