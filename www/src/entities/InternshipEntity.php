<?php

// TODO: add attributes for ratings
namespace Linkedout\App\entities;

// This class is used to store the data of an internship
class InternshipEntity
{
    public int $id;
    public string $title;
    public string $description;
    public string $skills;
    public int $salary;
    public string $offerDate;
    public string $beginDate;
    public string $endDate;
    public int $numberPlaces;
    public bool $masked;
    public string $company;
    public CityEntity $city;

    // This function is used to create a new InternshipEntity object
    public function __construct(?array $rawData = null)
    {
        if ($rawData === null)
            return;

        $this->id = $rawData['internshipId'];
        $this->title = $rawData['internshipTitle'];
        $this->description = $rawData['internshipDescription'];
        $this->skills = $rawData['internshipSkills'];
        $this->salary = $rawData['internshipSalary'];
        $this->offerDate = $rawData['internshipOfferDate'];
        $this->beginDate = $rawData['internshipBeginDate'];
        $this->endDate = $rawData['internshipEndDate'];
        $this->numberPlaces = $rawData['numberPlaces'];
        $this->masked = $rawData['maskedInternship'];
        $this->company = $rawData['company'];
        $this->city = new CityEntity($rawData);
    }
}
