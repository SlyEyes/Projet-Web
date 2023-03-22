<?php

namespace Linkedout\App\entities;

class PromotionEntity
{
    public mixed $id;
    public string $name;
    public mixed $campusId;
    public ?CampusEntity $campus = null;


    public function __construct(array $rawData)
    {
        $this->id = $rawData['promotionId'];
        $this->name = $rawData['promotionName'];
        $this->campusId = $rawData['campusId'];
        if (isset($rawData['campusName']))
            $this->campus = new CampusEntity($rawData);
    }
}
