<?php

namespace Linkedout\App\models;

use Linkedout\App\entities;
use Linkedout\App\enums\RoleEnum;

class RoleModel extends BaseModel
{
    public function getRoleFromEnum(RoleEnum $role): ?entities\RoleEntity
    {
        $sql = 'SELECT * FROM roles WHERE roleName = :roleName';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['roleName' => $role->value]);

        $result = $stmt->fetch();

        if (!$result)
            return null;
        return new entities\RoleEntity($result);
    }
}
