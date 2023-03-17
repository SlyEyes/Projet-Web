<?php

namespace Linkedout\App\models;

use PDO;

// This class is used to extend the database connection to all models
abstract class BaseModel
{
    protected PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
}
