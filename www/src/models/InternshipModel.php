<?php

namespace Linkedout\App\models;

use Linkedout\App\entities\InternshipEntity;

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
                            companies.companyName AS company,
                            cities.cityId,
                            cities.cityName AS city,
                            cities.zipcode AS cityZipCode
                        FROM internships
                        INNER JOIN cities ON internships.cityId = cities.cityId
                        INNER JOIN companies ON internships.companyId = companies.companyId
                        WHERE internshipId = :id';
        $statement = $this->db->prepare($sql_request);
        $statement->execute([
            'id' => $id,
        ]);

        $result = $statement->fetch();
        if (!$result) {
            return null;
        }

        return new InternshipEntity($result);
    }

    public function getInternshipByTitle (string $title): ?InternshipEntity
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
                            companies.companyName AS company,
                            cities.cityId,
                            cities.cityName AS city,
                            cities.zipcode AS cityZipCode
                        FROM internships
                        INNER JOIN cities ON internships.cityId = cities.cityId
                        INNER JOIN companies ON internships.companyId = companies.companyId
                        WHERE internshipTitle = :title';
        $statement = $this->db->prepare($sql_request);
        $statement->execute([
            'title' => $title,
        ]);

        $result = $statement->fetch();
        if (!$result) {
            return null;
        }

        return new InternshipEntity($result);
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
                    companies.companyName AS company,
                    cities.cityId,
                    cities.cityName AS city,
                    cities.zipcode AS cityZipCode
                FROM internships 
                INNER JOIN cities ON internships.cityId = cities.cityId
                INNER JOIN companies ON internships.companyId = companies.companyId
                LIMIT :limit 
                OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if (!$result)
            return [];

        return array_map(fn($internship) => new InternshipEntity($internship), $result);
    }

    /**
     * This function is used to get an internship from the database
     * @param $id int The id of a company
     * @return array all internships from a company
     */
    public function getInternshipsByCompanyId(int $id): array
    {
        $sql_request = 'SELECT 
                    internshipId, 
                    internshipTitle, 
                    internshipBeginDate, 
                    internshipEndDate, 
                    numberPlaces, 
                    companies.companyId,
                    cities.cityId,
                    cities.cityName AS city,
                    cities.zipcode AS cityZipCode
                FROM internships
                INNER JOIN cities ON internships.cityId = cities.cityId
                INNER JOIN companies ON internships.companyId = companies.companyId
                WHERE companies.companyId = :id';
        $statement = $this->db->prepare($sql_request);
        $statement->execute([
            'id' => $id,
        ]);

        $result = $statement->fetch();

        return array_map(fn($internship) => new InternshipEntity($internship), $result);
    }
}

