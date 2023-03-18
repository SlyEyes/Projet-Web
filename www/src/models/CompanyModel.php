<?php

namespace Linkedout\App\models;

use Linkedout\App\entities\CompanyEntity;
use Linkedout\App\services;

/**
 * Model for the company entity
 * @package Linkedout\App\models
 */
class CompanyModel extends BaseModel
{
    /**
     * This function is used to get a company from the database
     * @param $id int The id of the company
     * @return CompanyEntity|null The company entity or null if not found
     */
    public function getCompanyById (int $id): ?CompanyEntity
    {
        $sql_request = 'SELECT 
                            companies.companyId,
                            companies.companyLogo, 
                            companies.companyName, 
                            companies.companySector, 
                            companies.companyWebsite, 
                            companies.maskedCompany
                        FROM companies
                        WHERE companyId = :id';
        $statement = $this->db->prepare($sql_request);
        $statement->execute([
            'id' => $id,
        ]);

        $result = $statement->fetch();
        if (!$result)
        {
            return null;
        }

        return new CompanyEntity($result);
    }

    /**
     * This function is used to get a company from the database
     * @param $name string The name of the company
     * @return CompanyEntity|null The company entity or null if not found
     */
    public function getCompanyByName (string $name): ?CompanyEntity
    {
        $sql_request = 'SELECT 
                            companies.companyId,
                            companies.companyLogo, 
                            companies.companyName, 
                            companies.companySector, 
                            companies.companyWebsite, 
                            companies.maskedCompany
                        FROM companies
                        WHERE companyName = :name';
        $statement = $this->db->prepare($sql_request);
        $statement->execute([
            'name' => $name,
        ]);

        $result = $statement->fetch();
        if (!$result)
        {
            return null;
        }

        return new CompanyEntity($result);
    }

    /**
     * This function is used to get a company from the database
     * @param $sector string The sector of the company
     * @return CompanyEntity|null The company entity or null if not found
     */
    public function getCompanyBySector (string $sector): ?CompanyEntity
    {
        $sql_request = 'SELECT 
                            companies.companyId,
                            companies.companyLogo, 
                            companies.companyName, 
                            companies.companySector, 
                            companies.companyWebsite, 
                            companies.maskedCompany
                        FROM companies
                        WHERE companySector = :sector';
        $statement = $this->db->prepare($sql_request);
        $statement->execute([
            'sector' => $sector,
        ]);

        $result = $statement->fetch();
        if (!$result)
        {
            return null;
        }

        return new CompanyEntity($result);
    }
}