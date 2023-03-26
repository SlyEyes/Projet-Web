<?php
// TODO : add attributes for promotions and campus
namespace Linkedout\App\entities;

use Linkedout\App\enums;

/**
 * The person entity is responsible for storing all the data related to a person
 * @package Linkedout\App\entities
 */
class PersonEntity
{
    public int $id;
    public string $email;
    public string $password;
    public string $firstName;
    public string $lastName;
    public bool $passwordChanged = false;
    public enums\RoleEnum $role;

    // This function is used to create a new PersonEntity object
    public function __construct(?array $rawData = null)
    {
        if (!$rawData)
            return;

        $this->id = $rawData['personId'];
        $this->email = $rawData['email'];
        $this->password = $rawData['password'];
        $this->firstName = $rawData['firstName'];
        $this->lastName = $rawData['lastName'];
        $this->passwordChanged = (bool)$rawData['personPasswordChanged'];
        $this->role = enums\RoleEnum::fromValue($rawData['roleName']);
    }
}
