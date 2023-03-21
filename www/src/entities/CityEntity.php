<?php

namespace Linkedout\App\entities;

class CityEntity
{
    public mixed $id;
    public string $name;
    public string $zipcode;

    public function __construct(?array $data = null)
    {
        if ($data === null)
            return;

        $this->id = $data['cityId'];
        $this->name = $data['cityName'];
        $this->zipcode = $data['zipcode'];
    }
}
