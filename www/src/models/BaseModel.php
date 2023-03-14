<?php

namespace Linkedout\App\models;

use PDO;

abstract class BaseModel
{
    protected PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
}
