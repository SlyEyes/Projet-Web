<?php

namespace Linkedout\App\models;

use Linkedout\App\entities\ApplianceEntity;
use PDO;

/**
 * The base model class
 * @package Linkedout\App\models
 */
class ApplianceModel extends BaseModel
{
    /**
     * Get an appliance by its id
     * @param int $personId The id of the person
     * @param int $internshipId The id of the internship
     * @return ApplianceEntity|null
     */
    public function getApplianceById(int $personId, int $internshipId): ?ApplianceEntity
    {
        $sql = "SELECT internshipId, personId, ratingId, wishDate, applianceDate, responseDate, validation 
                FROM appliance 
                WHERE internshipId = :internshipId AND personId = :personId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['internshipId' => $internshipId, 'personId' => $personId]);

        $result = $stmt->fetch();
        if ($result === false)
            return null;

        return new ApplianceEntity($result);
    }

    /**
     * Get the wishlist for a person
     * @param int $personId
     * @return ApplianceEntity[]
     */
    public function getWishlistByPersonId(int $personId): array
    {
        $sql = "SELECT appliance.internshipId,
                        appliance.personId,
                        appliance.ratingId,
                        appliance.wishDate,
                        appliance.applianceDate,
                        appliance.responseDate,
                        appliance.validation,
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
                        internships.cityId,
                        companies.companyId,
                        companies.companyName,
                        cities.cityName,
                        cities.zipcode
                FROM appliance 
                INNER JOIN internships ON appliance.internshipId = internships.internshipId
                INNER JOIN companies on internships.companyId = companies.companyId
                INNER JOIN cities ON cities.cityId = internships.cityId
                WHERE appliance.personId = :userId 
                    AND appliance.applianceDate IS NULL 
                    AND wishDate IS NOT NULL 
                    AND internships.maskedInternship = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['userId' => $personId]);

        $result = $stmt->fetchAll();
        if ($result === false)
            return [];

        return array_map(fn($appliance) => new ApplianceEntity($appliance), $result);
    }

    /**
     * Get all appliances for a user
     * @param int $personId The id of the person
     * @param bool $validated If it returns only validated or not validated appliances.
     * If not set, it returns not validated appliances
     * @return ApplianceEntity[]
     */
    public function getAppliancesByPersonId(int $personId, bool $validated = false): array
    {
        $sql = "SELECT appliance.internshipId,
                        appliance.personId,
                        appliance.ratingId,
                        appliance.wishDate,
                        appliance.applianceDate,
                        appliance.responseDate,
                        appliance.validation,
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
                        internships.cityId,
                        companies.companyId,
                        companies.companyName,
                        cities.cityName,
                        cities.zipcode
                FROM appliance 
                INNER JOIN internships ON appliance.internshipId = internships.internshipId
                INNER JOIN companies on internships.companyId = companies.companyId
                INNER JOIN cities ON cities.cityId = internships.cityId
                WHERE appliance.personId = :userId 
                    AND appliance.applianceDate IS NOT NULL 
                    AND internships.maskedInternship = 0
                    AND appliance.validation = :validation";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('userId', $personId, PDO::PARAM_INT);
        $stmt->bindValue('validation', $validated, PDO::PARAM_BOOL);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if ($result === false)
            return [];

        return array_map(fn($appliance) => new ApplianceEntity($appliance), $result);
    }

    /**
     * Create a new appliance
     * @param ApplianceEntity $appliance
     * @return bool
     */
    public function createAppliance(ApplianceEntity $appliance): bool
    {
        $sql = "INSERT INTO appliance (internshipId, personId, ratingId, wishDate, applianceDate, responseDate, validation) 
                VALUES (:internshipId, :personId, :ratingId, :wishDate, :applianceDate, :responseDate, :validation)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue('internshipId', $appliance->internshipId);
        $stmt->bindValue('personId', $appliance->personId);
        if (!empty($appliance->ratingId))
            $stmt->bindValue('ratingId', $appliance->ratingId);
        else
            $stmt->bindValue('ratingId', null);
        if (!empty($appliance->wishDate))
            $stmt->bindValue('wishDate', $appliance->wishDate->format('y-m-d'));
        else
            $stmt->bindValue('wishDate', null);
        if (!empty($appliance->applianceDate))
            $stmt->bindValue('applianceDate', $appliance->applianceDate->format('y-m-d'));
        else
            $stmt->bindValue('applianceDate', null);
        if (!empty($appliance->responseDate))
            $stmt->bindValue('responseDate', $appliance->responseDate->format('y-m-d'));
        else
            $stmt->bindValue('responseDate', null);
        $stmt->bindValue('validation', $appliance->validation, \PDO::PARAM_BOOL);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    /**
     * Update an appliance
     * @param ApplianceEntity $appliance
     * @return bool
     */
    public function updateAppliance(ApplianceEntity $appliance): bool
    {
        $sql = "UPDATE appliance 
                SET ratingId = :ratingId, wishDate = :wishDate, applianceDate = :applianceDate, responseDate = :responseDate, validation = :validation 
                WHERE internshipId = :internshipId AND personId = :personId";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue('internshipId', $appliance->internshipId);
        $stmt->bindValue('personId', $appliance->personId);
        if (!empty($appliance->ratingId))
            $stmt->bindValue('ratingId', $appliance->ratingId);
        else
            $stmt->bindValue('ratingId', null);
        if (!empty($appliance->wishDate))
            $stmt->bindValue('wishDate', $appliance->wishDate->format('y-m-d'));
        else
            $stmt->bindValue('wishDate', null);
        if (!empty($appliance->applianceDate))
            $stmt->bindValue('applianceDate', $appliance->applianceDate->format('y-m-d'));
        else
            $stmt->bindValue('applianceDate', null);
        if (!empty($appliance->responseDate))
            $stmt->bindValue('responseDate', $appliance->responseDate->format('y-m-d'));
        else
            $stmt->bindValue('responseDate', null);
        $stmt->bindValue('validation', $appliance->validation, \PDO::PARAM_BOOL);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    /**
     * Delete an appliance
     * @param int $personId
     * @param int $internshipId
     * @return bool
     */
    public function deleteAppliance(int $personId, int $internshipId): bool
    {
        $sql = "DELETE FROM appliance 
                WHERE internshipId = :internshipId AND personId = :personId";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'internshipId' => $internshipId,
            'personId' => $personId,
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Get all the appliances for a specified internship. Do not return wishlisted internships
     * @param int $internshipId
     * @return ApplianceEntity[]|null
     */
    public function getAppliancesForInternship(int $internshipId): ?array
    {
        $sql = 'SELECT internshipId, personId, ratingId, wishDate, applianceDate, responseDate, validation 
                FROM appliance 
                WHERE internshipId = :internshipId AND applianceDate IS NOT NULL';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('internshipId', $internshipId, PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll();

        if (!$result)
            return null;

        return array_map(fn($appliance) => new ApplianceEntity($appliance), $result);
    }

    /**
     * Remove an internship from the wishlists of all students
     * @param int $internshipId
     * @return bool True if the internship has been removed from all wishlists
     */
    public function removeInternshipFromWishlists(int $internshipId): bool
    {
        $sql = "DELETE FROM appliance WHERE internshipId = :internshipId";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'internshipId' => $internshipId,
        ]);

        return $stmt->rowCount() > 0;
    }
}
