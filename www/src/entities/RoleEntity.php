<?php

namespace Linkedout\App\entities;

use Linkedout\App\enums\RoleEnum;

class RoleEntity
{
    public int $id;
    public string $name;
    public RoleEnum $enum;

    public function __construct(array $data)
    {
        $this->id = $data['roleId'];
        $this->name = $data['roleName'];
        $this->enum = RoleEnum::fromValue($data['roleName']);
    }
}
