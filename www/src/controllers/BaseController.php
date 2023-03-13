<?php

namespace Linkedout\App\controllers;

use Jenssegers\Blade\Blade;
use PDO;

abstract class BaseController
{
    protected Blade $blade;
    protected PDO $database;

    public function __construct($blade, $database)
    {
        $this->blade = $blade;
        $this->database = $database;
    }

    abstract public function render(): string;
}
