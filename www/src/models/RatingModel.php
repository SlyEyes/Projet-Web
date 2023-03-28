<?php

namespace Linkedout\App\models;

use Linkedout\App\entities\RatingEntity;
use PDOException;

class RatingModel extends BaseModel
{
    /**
     * Get a rating for a specific person - internship combo
     * @param int $personId The ID of the person
     * @param int $internshipId The ID of the internship
     * @return RatingEntity|null The rating, or null if not found
     */
    public function getRatingForUserInternship(int $personId, int $internshipId): ?RatingEntity
    {
        $sql = "SELECT rating.rate,
                        rating.ratingId
                FROM rating
                INNER JOIN appliance ON rating.ratingId = appliance.ratingId
                INNER JOIN internships on appliance.internshipId = internships.internshipId
                WHERE appliance.personId = :personId AND internships.internshipId = :internshipId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'personId' => $personId,
            'internshipId' => $internshipId
        ]);
        $result = $stmt->fetch();

        if (!$result)
            return null;

        return new RatingEntity($result);
    }

    /**
     * Get all ratings for a specific company
     * @param int $companyId
     * @return RatingEntity[]|null
     */
    public function getRatingsForCompany(int $companyId): ?array
    {
        $sql = "SELECT rating.rate,
                        rating.ratingId
                FROM rating
                INNER JOIN appliance ON rating.ratingId = appliance.ratingId
                INNER JOIN internships on appliance.internshipId = internships.internshipId
                INNER JOIN persons on appliance.personId = persons.personId
                WHERE internships.companyId = :companyId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'companyId' => $companyId
        ]);
        $result = $stmt->fetchAll();

        if (!$result)
            return null;

        return array_map(fn($rating) => new RatingEntity($rating), $result);
    }

    /**
     * Create a rating for a specific person - internship combo
     * @param RatingEntity $rating The rating to create. The ID will be set to the newly created ID
     * @param int $personId The ID of the person
     * @param int $internshipId The ID of the internship
     * @return RatingEntity The rating with the newly created ID
     */
    public function createRating(RatingEntity $rating, int $personId, int $internshipId): RatingEntity
    {
        $this->db->beginTransaction();

        try {
            $sql = "INSERT INTO rating (rate) VALUES (:rate)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'rate' => $rating->rating
            ]);
            $rating->id = $this->db->lastInsertId();

            $sql = "UPDATE appliance SET ratingId = :ratingId WHERE personId = :personId AND internshipId = :internshipId";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'ratingId' => $rating->id,
                'personId' => $personId,
                'internshipId' => $internshipId,
            ]);

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }

        return $rating;
    }

    /**
     * Update a rating
     * @param RatingEntity $rating The rating to update
     * @return bool True if the rating was updated, false otherwise
     */
    public function updateRating(RatingEntity $rating): bool
    {
        $sql = "UPDATE rating SET rate = :rate WHERE ratingId = :ratingId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'rate' => $rating->rating,
            'ratingId' => $rating->id
        ]);

        return $stmt->rowCount() > 0;
    }
}
