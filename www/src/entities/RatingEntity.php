<?php

namespace Linkedout\App\entities;

class RatingEntity
{
    public int $id;
    public int $rating;

    public function __construct(?array $rawData = null)
    {
        if (!$rawData)
            return;

        $this->id = $rawData['ratingId'];
        $this->rating = $rawData['rate'];
    }
}
