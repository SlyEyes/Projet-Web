<?php

namespace Linkedout\App\models;

use Linkedout\App\entities\InternshipEntity;
use Linkedout\App\services;

/**
 * Model for the internship entity
 * @package Linkedout\App\models
 */
class InternshipModel extends BaseModel
{
    /**
     * This function is used to get an internship from the database
     * @param $id int The id of the internship
     * @return InternshipEntity|null The internship entity or null if not found
     */
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
}