<?php

namespace Linkedout\App\models;

use Linkedout\App\entities\CampusEntity;

class CampusModel extends BaseModel
{
    /**
     * Get a list of all campuses
     * @return CampusEntity[]
     */
    public function getAllCampuses(): array
    {
        $sql = "SELECT campusId, campusName FROM campus";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $results = $stmt->fetchAll();

        if (empty($results))
            return [];

        return array_map(fn($campus) => new CampusEntity($campus), $results);
    }
}
