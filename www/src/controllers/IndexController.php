<?php

namespace Linkedout\App\controllers;

class IndexController extends BaseController
{
    public function render(): string
    {
        return $this->blade->make('pages.index');
    }
}
