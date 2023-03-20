<?php

namespace Linkedout\App\entities;

class CityEntity
{
    public mixed $id;
    public string $name;
    public string $zipcode;

    public function __construct(array $data)
    {
        $this->id = $data['cityId'];
        $this->name = $data['cityName'];
        $this->zipcode = $data['zipcode'];
    }
}
