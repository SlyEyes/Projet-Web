<?php

namespace Linkedout\App\models;

use Linkedout\App\entities\PersonEntity;
use Linkedout\App\services;

class PersonModel extends BaseModel
{
    public function getPersonById($id): ?PersonEntity
    {
        $sql = 'SELECT * FROM persons WHERE personId = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch();
        if (!$result)
            return null;
        return new PersonEntity($result);
    }

    public function getPersonByEmail($email): ?PersonEntity
    {
        $sql = 'SELECT * FROM persons WHERE email = :email';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);

        $result = $stmt->fetch();
        if (!$result)
            return null;
        return new PersonEntity($result);
    }

    public function getPersonFromJwt(): ?PersonEntity
    {
        if (empty($_COOKIE['TOKEN']))
            return null;

        $jwtService = new services\JwtService();
        try {
            $tokenData = $jwtService->decodeToken($_COOKIE['TOKEN']);
        } catch (\Exception) {
            return null;
        }

        return $this->getPersonById($tokenData['id']);
    }
}
