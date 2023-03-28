<?php

namespace Linkedout\App\models;

use Linkedout\App\entities\InternshipEntity;
use PDO;

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
    public function getInternshipById(int $id): ?InternshipEntity
    {
        $sql_request = 'SELECT internships.internshipId, 
                            internships.internshipTitle,
                            internships.internshipDescription, 
                            internships.internshipSkills, 
                            internships.internshipSalary, 
                            internships.internshipOfferDate, 
                            internships.internshipBeginDate, 
                            internships.internshipEndDate, 
                            internships.numberPlaces, 
                            internships.maskedInternship,
                            companies.companyId,
                            companies.companyName,
                            cities.cityId,
                            cities.cityName,
                            cities.zipcode
                        FROM internships
                        INNER JOIN cities ON internships.cityId = cities.cityId
                        INNER JOIN companies ON internships.companyId = companies.companyId
                        WHERE internshipId = :id';
        $statement = $this->db->prepare($sql_request);
        $statement->execute([
            'id' => $id,
        ]);

        $result = $statement->fetch();
        if (!$result)
            return null;

        return new InternshipEntity($result);
    }

    public function getInternshipsBySearch($search, $limit,$firstResult, $f): array
    {
        $internshipDuration = 'ROUND((DATEDIFF(internships.internshipEndDate, internships.internshipBeginDate) + 1) / 30)';

        $sql = "SELECT internships.internshipId, 
                            internships.internshipTitle,
                            internships.internshipDescription, 
                            internships.internshipSkills, 
                            internships.internshipSalary, 
                            internships.internshipOfferDate, 
                            internships.internshipBeginDate, 
                            internships.internshipEndDate, 
                            internships.numberPlaces, 
                            internships.maskedInternship,
                            :internshipDuration
                                AS internshipDuration,
                            companies.companyId,
                            companies.companyName,
                            cities.cityId,
                            cities.cityName,
                            cities.zipcode,
                            MATCH(internshipTitle) AGAINST(:search) as scoreInternshipTitle,
                            MATCH(internshipSkills) AGAINST(:search) as scoreInternshipSkills
                        FROM internships
                        INNER JOIN cities ON internships.cityId = cities.cityId
                        INNER JOIN companies ON internships.companyId = companies.companyId
                        WHERE 
                            maskedInternship = 0 AND
                            maskedCompany = 0 AND
                            MATCH(internshipTitle) AGAINST(:search) OR
                            MATCH(internshipSkills) AGAINST(:search) AND
                            :internshipDuration >= :f0 AND
                            :internshipDuration <= :f1
                        ORDER BY scoreInternshipTitle DESC, scoreInternshipSkills DESC
                        LIMIT :limit
                        OFFSET :firstResult";

        $statement = $this->db->prepare($sql);
        $statement->bindValue('internshipDuration', $search);
        $statement->bindValue('search', $search);
        $statement->bindValue('limit', $limit, \PDO::PARAM_INT);
        $statement->bindValue('firstResult', $firstResult, \PDO::PARAM_INT);
        $statement->bindValue('f0', $f[0], \PDO::PARAM_INT);
        $statement->bindValue('f1', $f[1], \PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetchAll();

        if (!$result) {
            return [];
        }

        return array_map(fn($internship) => new InternshipEntity($internship), $result);
    }

    /**
     * Query all the internships from the database
     * @param $limit int The maximum number of internships to return
     * @param $offset int The offset of the first internship to return
     * @return InternshipEntity[] The list of internships
     */
    public function getAllInternships(int $limit = 50, int $offset = 0): array
    {
        $sql = 'SELECT 
                    internships.internshipId, 
                    internships.internshipTitle,
                    internships.internshipDescription, 
                    internships.internshipSkills, 
                    internships.internshipSalary, 
                    internships.internshipOfferDate, 
                    internships.internshipBeginDate, 
                    internships.internshipEndDate, 
                    internships.numberPlaces, 
                    internships.maskedInternship, 
                    companies.companyId,
                    companies.companyName,
                    cities.cityId,
                    cities.cityName,
                    cities.zipcode
                FROM internships 
                INNER JOIN cities ON internships.cityId = cities.cityId
                INNER JOIN companies ON internships.companyId = companies.companyId
                LIMIT :limit 
                OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if (!$result)
            return [];

        return array_map(fn($internship) => new InternshipEntity($internship), $result);
    }

    /**
     * This function is used to get all available internships from a company. Do not return masked internships
     * @param $id int The id of a company
     * @return array all internships from a company
     */
    public function getInternshipsByCompanyId(int $id): array
    {
        $sql_request = 'SELECT 
                    internships.internshipId, 
                    internships.internshipTitle, 
                    internships.internshipDescription,
                    internships.internshipSkills,
                    internships.internshipSalary,
                    internships.internshipBeginDate, 
                    internships.internshipEndDate,
                    internships.internshipOfferDate, 
                    internships.numberPlaces, 
                    internships.maskedInternship,
                    companies.companyId,
                    companies.companyId,
                    companies.companyName,
                    cities.cityId,
                    cities.cityName,
                    cities.zipcode
                FROM internships
                INNER JOIN cities ON internships.cityId = cities.cityId
                INNER JOIN companies ON internships.companyId = companies.companyId
                WHERE companies.companyId = :id AND internships.maskedInternship = 0';
        $statement = $this->db->prepare($sql_request);
        $statement->execute([
            'id' => $id,
        ]);

        $result = $statement->fetchAll();

        if (!$result)
            return [];

        return array_map(fn($internship) => new InternshipEntity($internship), $result);
    }

    /**
     * Creates an internship in the database
     * @param InternshipEntity $internship The internship to create
     * @return int The id of the created internship
     */
    public function createInternship(InternshipEntity $internship): int
    {
        $sql = 'INSERT INTO internships (internshipTitle,
                         internshipDescription,
                         internshipSkills,
                         internshipSalary,
                         internshipOfferDate,
                         internshipBeginDate,
                         internshipEndDate,
                         numberPlaces,
                         maskedInternship,
                         cityId,
                         companyId)
                VALUES (:internshipTitle, 
                        :internshipDescription, 
                        :internshipSkills, 
                        :internshipSalary, 
                        :internshipOfferDate,
                        :internshipBeginDate, 
                        :internshipEndDate, 
                        :numberPlaces, 
                        :maskedInternship, 
                        :cityId, 
                        :companyId)';
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue('internshipTitle', $internship->title);
        $stmt->bindValue('internshipDescription', $internship->description);
        $stmt->bindValue('internshipSkills', $internship->skills);
        $stmt->bindValue('internshipSalary', $internship->salary, PDO::PARAM_INT);
        $stmt->bindValue('internshipOfferDate', $internship->offerDate);
        $stmt->bindValue('internshipBeginDate', $internship->beginDate);
        $stmt->bindValue('internshipEndDate', $internship->endDate);
        $stmt->bindValue('numberPlaces', $internship->numberPlaces, PDO::PARAM_INT);
        $stmt->bindValue('maskedInternship', $internship->masked, PDO::PARAM_BOOL);
        $stmt->bindValue('cityId', $internship->city->id, PDO::PARAM_INT);
        $stmt->bindValue('companyId', $internship->companyId, PDO::PARAM_INT);

        $stmt->execute();

        return (int)$this->db->lastInsertId();
    }

    /**
     * Updates an internship in the database
     * @param InternshipEntity $internship The internship to update
     * @return bool True if the update was successful, false otherwise
     */
    public function updateInternship(InternshipEntity $internship): bool
    {
        $sql = 'UPDATE internships 
                SET internshipTitle = :internshipTitle,
                    internshipDescription = :internshipDescription,
                    internshipSkills = :internshipSkills,
                    internshipSalary = :internshipSalary,
                    internshipOfferDate = :internshipOfferDate,
                    internshipBeginDate = :internshipBeginDate,
                    internshipEndDate = :internshipEndDate,
                    numberPlaces = :numberPlaces,
                    maskedInternship = :maskedInternship,
                    cityId = :cityId,
                    companyId = :companyId
                WHERE internshipId = :internshipId';
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue('internshipTitle', $internship->title);
        $stmt->bindValue('internshipDescription', $internship->description);
        $stmt->bindValue('internshipSkills', $internship->skills);
        $stmt->bindValue('internshipSalary', $internship->salary, PDO::PARAM_INT);
        $stmt->bindValue('internshipOfferDate', $internship->offerDate);
        $stmt->bindValue('internshipBeginDate', $internship->beginDate);
        $stmt->bindValue('internshipEndDate', $internship->endDate);
        $stmt->bindValue('numberPlaces', $internship->numberPlaces, PDO::PARAM_INT);
        $stmt->bindValue('maskedInternship', $internship->masked, PDO::PARAM_BOOL);
        $stmt->bindValue('cityId', $internship->city->id, PDO::PARAM_INT);
        $stmt->bindValue('companyId', $internship->companyId, PDO::PARAM_INT);
        $stmt->bindValue('internshipId', $internship->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Deletes an internship from the database
     * @param $id int The id of the internship to delete
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteInternship(int $id): bool
    {
        $sql = 'DELETE FROM internships WHERE internshipId = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
