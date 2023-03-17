<?php
// TODO : modify sql requests to add attributes for promotions and campus
namespace Linkedout\App\models;

use Linkedout\App\entities\PersonEntity;
use Linkedout\App\services;

// This class is used to make requests to the database for the person entity
class PersonModel extends BaseModel
{
    public function getPersonById($id): ?PersonEntity
    {
        $sql = 'SELECT 
                    persons.personId, 
                    persons.email, 
                    persons.password, 
                    persons.firstName, 
                    persons.lastName,
                    role.roleName
                FROM persons 
                INNER JOIN role ON persons.roleId = role.roleId
                WHERE personId = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch();
        if (!$result)
            return null;
        return new PersonEntity($result);
    }

    public function getPersonByEmail($email): ?PersonEntity
    {
        $sql = 'SELECT 
                    persons.email,
                    persons.password,
                    persons.firstName,
                    persons.lastName,
                    role.roleName
                FROM persons 
                INNER JOIN role ON persons.roleId = role.roleId
                WHERE email = :email';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);

        $result = $stmt->fetch();
        if (!$result)
            return null;
        return new PersonEntity($result);
    }

    // This function is used to get the person's id from a cookie
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
