<?php

namespace Linkedout\App\models;

use Linkedout\App\entities\CompanyEntity;

/**
 * Model for the company entity
 * @package Linkedout\App\models
 */
class CompanyModel extends BaseModel
{
    public function getCompaniesBySearch($search, $limit, $firstResult): array
    {
        $sql = 'SELECT 
                    companies.companyId,
                    companies.companyLogo,
                    companies.companyName,
                    companies.companySector,
                    companies.companyWebsite,
                    companies.maskedCompany,
                    companies.companyEmail,
                    MATCH(companyName) AGAINST(:search) as scoreCompanyName,
                    MATCH(companySector) AGAINST(:search) as scoreCompanySector
                FROM companies
                WHERE
                    maskedCompany = 0 AND
                    MATCH(companyName) AGAINST(:search) OR
                    MATCH(companySector) AGAINST(:search)
                ORDER BY scoreCompanyName DESC, scoreCompanySector DESC
                LIMIT :limit
                OFFSET :firstResult';

        $statement = $this->db->prepare($sql);
        $statement->bindValue('search', $search);
        $statement->bindValue('limit', $limit, \PDO::PARAM_INT);
        $statement->bindValue('firstResult', $firstResult, \PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetchAll();

        if (!$result)
        {
            return [];
        }

        return array_map(fn($company) => new CompanyEntity($company), $result);
    }

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
                            companies.maskedCompany,
                            companies.companyEmail,
                            (SELECT COUNT(*) FROM internships WHERE internships.companyId = companies.companyId) AS internshipCount
                        FROM companies
                        WHERE companies.companyId = :id';
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
                            companies.companyEmail,
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
                            companies.companyEmail, 
                            companies.maskedCompany
                        FROM companies
                        WHERE companySector = :sector';
        $statement = $this->db->prepare($sql_request);
        $statement->execute([
            'sector' => $sector,
        ]);

        $result = $statement->fetch();
        if (!$result) {
            return null;
        }

        return new CompanyEntity($result);
    }

    /**
     * This function is used to get all companies from the database
     * @param int $limit The number of companies to return
     * @param int $offset The offset of the first company to return
     * @return CompanyEntity[] The company entity or null if not found
     */
    public function getAllCompanies(int $limit = 50, int $offset = 0): array
    {
        $sql = 'SELECT 
                    companies.companyId,
                    companies.companyLogo, 
                    companies.companyName, 
                    companies.companySector, 
                    companies.companyWebsite,
                    companies.companyEmail,
                    companies.maskedCompany
                FROM companies
                LIMIT :limit 
                OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if (!$result) {
            return [];
        }
        return array_map(fn($company) => new CompanyEntity($company), $result);
    }

    /**
     * This function is used to create a new company
     * @param CompanyEntity $newCompany The company to create
     * @return int The id of the new company
     */
    public function createCompany(CompanyEntity $newCompany): int
    {
        $sql = 'INSERT INTO companies 
                    (companyLogo, companyName, companySector, companyWebsite, companyEmail, maskedCompany) 
                VALUES 
                    (:logo, :name, :sector, :website, :email, :masked)';
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':logo', $newCompany->logo);
        $stmt->bindValue(':name', $newCompany->name);
        $stmt->bindValue(':sector', $newCompany->sector);
        $stmt->bindValue(':website', $newCompany->website);
        $stmt->bindValue(':email', $newCompany->email);
        $stmt->bindValue(':masked', $newCompany->masked, \PDO::PARAM_BOOL);

        $stmt->execute();

        return (int)$this->db->lastInsertId();
    }

    /**
     * This function is used to update a company
     * @param CompanyEntity $company The company to update
     * @return bool True if the company was updated, false otherwise
     */
    public function updateCompany(CompanyEntity $company): bool
    {
        $sql = 'UPDATE companies 
                SET companyLogo = :logo, 
                    companyName = :name, 
                    companySector = :sector, 
                    companyWebsite = :website, 
                    maskedCompany = :masked,
                    companyEmail = :email
                WHERE companyId = :id';
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':id', $company->id, \PDO::PARAM_INT);
        $stmt->bindValue(':logo', $company->logo);
        $stmt->bindValue(':name', $company->name);
        $stmt->bindValue(':sector', $company->sector);
        $stmt->bindValue(':website', $company->website);
        $stmt->bindValue(':masked', $company->masked, \PDO::PARAM_BOOL);
        $stmt->bindValue(':email', $company->email);

        return $stmt->execute();
    }
}
