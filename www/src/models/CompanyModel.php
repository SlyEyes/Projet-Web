<?php

namespace Linkedout\App\models;

use Linkedout\App\entities\CompanyEntity;
use Linkedout\App\services;

class CompanyModel extends BaseModel
{
    public function getCompanyById (int $id): ?CompanyEntity
    {
        $sql_request = 'SELECT 
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

    public function getCompanyByName (int $name): ?CompanyEntity
    {
        $sql_request = 'SELECT 
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
}