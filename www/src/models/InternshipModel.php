<?php

namespace Linkedout\App\models;

use Linkedout\App\entities\InternshipEntity;
use Linkedout\App\services;

class InternshipModel extends BaseModel
{
    public function getInternshipById (int $id): ?InternshipEntity
    {
        $sql_request = ''; // TODO: add sql request
        $statement = $this->db->prepare($sql_request);
        $statement->execute([
            'id' => $id,
        ]);

        $result = $statement->fetch();
        if (!$result)
        {
            return null;
        }

        return new InternshipEntity($result);
    }

    public function getInternshipByName (int $name): ?InternshipEntity
    {
        $sql_request = ''; // TODO: add sql request
        $statement = $this->db->prepare($sql_request);
        $statement->execute([
            'name' => $name,
        ]);

        $result = $statement->fetch();
        if (!$result)
        {
            return null;
        }

        return new InternshipEntity($result);
    }
}