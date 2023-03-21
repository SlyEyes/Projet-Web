<?php

namespace Linkedout\App\controllers\api;

use Exception;
use Linkedout\App\models;

class CityController extends ApiController
{
    private string $zipcode;

    public function setRouteParams(int $zipcode): void
    {
        $this->zipcode = (string)$zipcode;
    }

    public function fetch(): array
    {
        if (strlen($this->zipcode) !== 5) {
            $this->responseCode = 400;
            throw new Exception('Invalid zipcode');
        }

        $cityModel = new models\CityModel($this->database);
        $cities = $cityModel->getCitiesByZipcode($this->zipcode);

        return [
            'cities' => $cities,
        ];
    }
}
