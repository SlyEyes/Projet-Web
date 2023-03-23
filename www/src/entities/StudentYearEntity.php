<?php

namespace Linkedout\App\entities;

class StudentYearEntity
{
    public mixed $id;
    public string $year;

    public function __construct(?array $rawData)
    {
        if (!$rawData)
            return;

        $this->id = $rawData['studentYearId'];
        $this->year = $rawData['studentYear'];
    }
}
