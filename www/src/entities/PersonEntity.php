<?php

namespace Linkedout\App\entities;

class PersonEntity
{
    public int $id;
    public string $email;
    public string $password;
    public string $firstName;
    public string $lastName;
    public int $roleId;

    public function __construct(array $rawData)
    {
        $this->id = $rawData['personId'];
        $this->email = $rawData['email'];
        $this->password = $rawData['password'];
        $this->firstName = $rawData['firstName'];
        $this->lastName = $rawData['lastName'];
        $this->roleId = $rawData['roleId'];
    }
}
