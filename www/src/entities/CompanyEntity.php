<?php

namespace Linkedout\App\entities;

// This class is used to store the data of a company
class CompanyEntity
{
    public int $id;
    public string $logo;
    public string $name;
    public string $sector;
    public string $website;
    public bool $masked;

    // This function is used to create a new CompanyEntity object
    public function __construct(?array $rawData = null)
    {
        if ($rawData == null)
            return;

        $this->id = $rawData['companyId'];
        $this->logo = $rawData['companyLogo'];
        $this->name = $rawData['companyName'];
        $this->sector = $rawData['companySector'];
        $this->website = $rawData['companyWebsite'];
        $this->masked = $rawData['maskedCompany'];
    }
}