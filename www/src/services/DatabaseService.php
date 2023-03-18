<?php

namespace Linkedout\App\services;

use PDO;

/**
 * Service for the database. On construction, it creates a PDO instance and stores it in the database attribute.
 * This attribute is then used by the models to access the database.
 * @package Linkedout\App\services
 */
class DatabaseService
{
    private PDO $database;

    public function __construct()
    {
        $this->database = new \PDO('mysql:host=' . $_ENV['MYSQL_HOST'] . ';dbname=' . $_ENV['MYSQL_DATABASE'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD']);
    }

    /**
     * Getter for the database attribute
     * @return PDO
     */
    public function getDatabase(): PDO
    {
        return $this->database;
    }
}
