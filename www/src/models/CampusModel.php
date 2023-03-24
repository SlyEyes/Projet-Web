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

    public function getCampusForPersonId(int $personId): ?CampusEntity
    {
        $sql = "SELECT campusId, campusName 
                FROM campus 
                WHERE campusId = 
                      (SELECT campusId 
                       FROM promotions 
                       WHERE promotionId =
                             (SELECT promotionId 
                              FROM person_promotion 
                              WHERE personId = :personId 
                              LIMIT 1) 
                       LIMIT 1)
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['personId' => $personId]);

        $result = $stmt->fetch();

        if (empty($result))
            return null;

        return new CampusEntity($result);
    }
}
