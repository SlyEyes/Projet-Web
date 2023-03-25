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
    public function getPromotionForStudentId(int $personId): ?PromotionEntity
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
    public function setPromotionForStudentId(int $personId, int $promotionId): bool
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
    public function removePromotionForStudentId(int $personId, int $promotionId): bool
    {
        $sql = "DELETE FROM person_promotion WHERE personId = :personId AND promotionId = :promotionId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['personId' => $personId, 'promotionId' => $promotionId]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Get the managed promotions by a tutor. Also fills the campus info
     * @param int $personId
     * @return PromotionEntity[]|null
     */
    public function getPromotionsForTutorId(int $personId): ?array
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

        $results = $stmt->fetchAll();

        if (empty($results))
            return null;

        return array_map(fn($promotion) => new PromotionEntity($promotion), $results);
    }

    /**
     * Add a promotion to a tutor
     * @param int $personId The tutor id
     * @param int $promotionId The promotion id
     * @return bool True if the promotion was added, false otherwise
     */
    public function addPromotionForTutorId(int $personId, int $promotionId): bool
    {
        $sql = "INSERT INTO person_promotion (personId, promotionId) VALUES (:personId, :promotionId)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['personId' => $personId, 'promotionId' => $promotionId]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Remove all promotions from a tutor
     * @param int $personId The tutor id
     * @return bool True if the promotions were removed, false otherwise
     */
    public function removePromotionsForTutorId(int $personId): bool
    {
        $sql = "DELETE FROM person_promotion WHERE personId = :personId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['personId' => $personId]);
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

    /**
     * Select the promotions of a campus that are not currently managed another tutor.
     * It returns the promotions that not managed by any tutor, and the promotions that are managed by the provided tutor
     * @param int $campusId The id of the campus to select the promotions from
     * @param int $tutorId The id of the tutor. If not provided, it will return the promotions that are not managed by any tutor
     * @return PromotionEntity[]|null
     */
    public function getAvailablePromotionsForTutor(int $campusId, int $tutorId = -1): ?array
    {
        $sql = "SELECT promotions.promotionId, 
                        promotions.promotionName,
                        promotions.promotionId, 
                        promotions.promotionName, 
                        promotions.campusId,
                        campus.campusName
                FROM promotions 
                INNER JOIN campus ON promotions.campusId = campus.campusId
                WHERE campus.campusId = :campusId
                AND promotions.promotionId NOT IN (
                    SELECT person_promotion.promotionId
                    FROM person_promotion
                    INNER JOIN persons ON person_promotion.personId = persons.personId
                    WHERE persons.roleId = (SELECT roleId FROM roles WHERE roleName = 'tutor' LIMIT 1)
                    AND persons.personId != :tutorId
                )";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['campusId' => $campusId, 'tutorId' => $tutorId]);

        $results = $stmt->fetchAll();

        if (empty($results))
            return null;

        return array_map(fn($promotion) => new PromotionEntity($promotion), $results);
    }
}
