<?php

namespace Linkedout\App\models;

use Linkedout\App\entities\ApplianceEntity;

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
     * Get all appliances for a user
     * @param int $personId
     * @return ApplianceEntity[]
     */
    public function getAppliancesForPerson(int $personId): array
    {
        $sql = "SELECT internshipId, personId, ratingId, wishDate, applianceDate, responseDate, validation 
                FROM appliance 
                WHERE personId = :userId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['userId' => $personId]);

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
}
