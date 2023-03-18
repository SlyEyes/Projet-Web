<?php

namespace Linkedout\App\enums;

/**
 * The RoleEnum is used to store the different roles of a person
 * @package Linkedout\App\enums
 */
enum RoleEnum: string
{
    case ADMINISTRATOR = 'administrator';
    case TUTOR = 'tutor';
    case STUDENT = 'student';

    /**
     * Create a RoleEnum from a value
     * @param mixed $role The value to convert
     * @return RoleEnum The converted value
     * @throws \Exception
     */
    public static function fromValue(mixed $role): RoleEnum
    {
        return match ($role) {
            'administrator' => self::ADMINISTRATOR,
            'tutor' => self::TUTOR,
            'student' => self::STUDENT,
            default => throw new \Exception('Invalid role'),
        };
    }
}
