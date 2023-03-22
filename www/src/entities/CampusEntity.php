<?php

namespace Linkedout\App\entities;

class CampusEntity
{
    public mixed $id;
    public string $name;

    public function __construct(array $rawData)
    {
        $this->id = $rawData['campusId'];
        $this->name = $rawData['campusName'];
    }
}
