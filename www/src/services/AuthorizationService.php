<?php

namespace Linkedout\App\services;

use Linkedout\App\entities;
use Linkedout\App\enums\RoleEnum;

/**
 * The authorization service is responsible for handling all the authorization related checks
 */
class AuthorizationService
{
    private ?entities\PersonEntity $personEntity;

    function __construct(?entities\PersonEntity $personEntity)
    {
        $this->personEntity = $personEntity;
    }

    /**
     * Check if the current user has one of the given roles
     * @param RoleEnum $role1 The first role to check
     * @param RoleEnum|null $role2 The second role to check, optional
     * @param RoleEnum|null $role3 The third role to check, optional
     * @return bool True if the user has one of the given roles, false otherwise
     */
    function hasAuthorization(RoleEnum $role1, ?RoleEnum $role2 = null, ?RoleEnum $role3 = null): bool
    {
        if ($this->personEntity === null)
            return false;

        $roles = [$role1, $role2, $role3];
        $roles = array_filter($roles, fn($role) => $role !== null);
        $roles = array_map(fn($role) => $role->value, $roles);
        return in_array($this->personEntity->role->value, $roles);
    }

    /**
     * Redirect the user to the given path if he does not have one of the given roles
     * @param string $path The path to redirect to
     * @param RoleEnum $role1 The first role to check
     * @param RoleEnum|null $role2 The second role to check, optional
     * @param RoleEnum|null $role3 The third role to check, optional
     * @return void
     */
    function redirectIfNotAuthorized(string $path, RoleEnum $role1, ?RoleEnum $role2 = null, ?RoleEnum $role3 = null): void
    {
        if ($this->hasAuthorization($role1, $role2, $role3))
            return;

        header("Location: {$path}");
        exit;
    }
}
