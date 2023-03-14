<?php

namespace Linkedout\App\models;

use Linkedout\App\entities\PersonEntity;

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
}
