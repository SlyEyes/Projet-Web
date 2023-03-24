<?php

namespace Linkedout\App\models;

use Linkedout\App\entities\PromotionEntity;

class PromotionModel extends BaseModel
{
    /**
     * Get the associated promotion for a student. Also fills the campus info
     * @param int $personId
     * @return PromotionEntity|null
     */
    public function getPromotionForPersonId(int $personId): ?PromotionEntity
    {
        $sql = "SELECT promotions.promotionId, 
                        promotions.promotionName,
                        promotions.promotionId, 
                        promotions.promotionName, 
                        promotions.campusId,
                        campus.campusName
                FROM person_promotion 
                INNER JOIN promotions ON person_promotion.promotionId = promotions.promotionId
                INNER JOIN campus ON promotions.campusId = campus.campusId
                WHERE person_promotion.personId = :personId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['personId' => $personId]);

        $results = $stmt->fetch();

        if (empty($results))
            return null;

        return new PromotionEntity($results);
    }

    /**
     * Set the promotion for a student
     * @param int $personId
     * @param int $promotionId
     * @return bool True if the promotion was set, false otherwise
     */
    public function setPromotionForPersonId(int $personId, int $promotionId): bool
    {
        $sql = "INSERT INTO person_promotion (personId, promotionId) VALUES (:personId, :promotionId)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['personId' => $personId, 'promotionId' => $promotionId]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Remove the promotion for a student
     * @param int $personId
     * @param int $promotionId
     * @return bool True if the promotion was removed, false otherwise
     */
    public function removePromotionForPersonId(int $personId, int $promotionId): bool
    {
        $sql = "DELETE FROM person_promotion WHERE personId = :personId AND promotionId = :promotionId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['personId' => $personId, 'promotionId' => $promotionId]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Get the associated promotion for a specific campus. Also fills the campus info
     * @param int $campusId
     * @return PromotionEntity[]|null
     */
    public function getPromotionForCampusId(int $campusId): ?array
    {
        $sql = "SELECT promotions.promotionId, 
                        promotions.promotionName,
                        promotions.promotionId, 
                        promotions.promotionName, 
                        promotions.campusId,
                        campus.campusName
                FROM promotions 
                INNER JOIN campus ON promotions.campusId = campus.campusId
                WHERE campus.campusId = :campusId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['campusId' => $campusId]);

        $results = $stmt->fetchAll();

        if (empty($results))
            return null;

        return array_map(fn($promotion) => new PromotionEntity($promotion), $results);
    }
}
