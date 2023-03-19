<?php
// TODO : modify sql requests to add attributes for promotions and campus
namespace Linkedout\App\models;

use Linkedout\App\entities\PersonEntity;
use Linkedout\App\enums\RoleEnum;
use Linkedout\App\services;


/**
 * Model for the person entity
 * @package Linkedout\App\models
 */
class PersonModel extends BaseModel
{
    /**
     * This function is used to get a person from the database
     * @param $id int|string The id of the person
     * @return PersonEntity|null The person entity or null if not found
     */
    public function getPersonById(mixed $id): ?PersonEntity
    {
        $sql = 'SELECT 
                    persons.personId, 
                    persons.email, 
                    persons.password, 
                    persons.firstName, 
                    persons.lastName,
                    roles.roleName
                FROM persons 
                INNER JOIN roles ON persons.roleId = roles.roleId
                WHERE personId = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch();
        if (!$result)
            return null;
        return new PersonEntity($result);
    }

    /**
     * This function is used to get a person from the database
     * @param $email string The email of the person
     * @return PersonEntity|null The person entity or null if not found
     */
    public function getPersonByEmail(string $email): ?PersonEntity
    {
        $sql = 'SELECT 
                    persons.personId, 
                    persons.email,
                    persons.password,
                    persons.firstName,
                    persons.lastName,
                    roles.roleName
                FROM persons 
                INNER JOIN roles ON persons.roleId = roles.roleId
                WHERE email = :email';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);

        $result = $stmt->fetch();
        if (!$result)
            return null;
        return new PersonEntity($result);
    }

    /**
     * This function is used to get the current logged in person based on the jwt token.
     * The token is stored in a cookie named TOKEN, and contains the id of the person.
     * To decode the token, the JwtService is used.
     * @return PersonEntity|null The person entity or null if not found
     * @see JwtService
     */
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

    /**
     * This function is used to get all the persons from the database
     * @param $limit int The limit of the query
     * @param $offset int The offset of the query
     * @return array The array of person entities
     */
    public function getAllPersons(int $limit = 50, int $offset = 0, array $roles = [RoleEnum::STUDENT, RoleEnum::TUTOR, RoleEnum::ADMINISTRATOR]): array
    {
        $roles = array_map(fn($role) => $role->value, $roles);
        $sql = 'SELECT 
                    persons.personId, 
                    persons.email, 
                    persons.password, 
                    persons.firstName, 
                    persons.lastName,
                    roles.roleName
                FROM persons 
                INNER JOIN roles ON persons.roleId = roles.roleId
                WHERE roles.roleName IN (:roles)
                LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue('roles', implode(',', $roles));
        $stmt->execute();

        $result = $stmt->fetchAll();
        $persons = [];
        foreach ($result as $person) {
            $persons[] = new PersonEntity($person);
        }
        return $persons;
    }
}
