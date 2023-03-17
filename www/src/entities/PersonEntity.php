<?php
// TODO : add attributes for promotions and campus
namespace Linkedout\App\entities;

// This class is used to store the data of a person
class PersonEntity
{
    public string $email;
    public string $password;
    public string $firstName;
    public string $lastName;
    public string $role;

    // This function is used to create a new PersonEntity object
    public function __construct(array $rawData)
    {
        $this->email = $rawData['email'];
        $this->password = $rawData['password'];
        $this->firstName = $rawData['firstName'];
        $this->lastName = $rawData['lastName'];
        $this->role = $rawData['roleName'];
    }
}
