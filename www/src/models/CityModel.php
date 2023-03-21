<?php

namespace Linkedout\App\models;

use Linkedout\App\entities\CityEntity;

class CityModel extends BaseModel
{
    /**
     * This function is used to get all the cities with the given zipcode
     * @param string $zipcode The zipcode to search
     * @return CityEntity[] The list of cities inside CityEntity
     */
    public function getCitiesByZipcode(string $zipcode): array
    {
        $sql_request = 'SELECT * FROM cities WHERE cities.zipcode = :zipcode';
        $statement = $this->db->prepare($sql_request);
        $statement->execute([
            'zipcode' => $zipcode,
        ]);

        $result = $statement->fetchAll();
        if (!$result)
            return [];
        return array_map(fn($city) => new CityEntity($city), $result);
    }
}
