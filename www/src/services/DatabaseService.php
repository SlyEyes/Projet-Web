<?php

namespace Linkedout\App\services;

use PDO;

class DatabaseService
{
    private PDO $database;

    public function __construct()
    {
        $this->database = new \PDO('mysql:host=' . $_ENV['MYSQL_HOST'] . ';dbname=' . $_ENV['MYSQL_DATABASE'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD']);
    }

    public function getDatabase()
    {
        return $this->database;
    }
}
