<?php

namespace Linkedout\App\enums;

use Exception;

enum DashboardCollectionEnum: string
{
    case STUDENTS = 'students';
    case TUTORS = 'tutors';
    case ADMINISTRATORS = 'administrators';
    case COMPANIES = 'companies';
    case INTERNSHIPS = 'internships';

    public static function fromValue(string $collection): DashboardCollectionEnum
    {
        return match ($collection) {
            'students' => self::STUDENTS,
            'tutors' => self::TUTORS,
            'administrators' => self::ADMINISTRATORS,
            'companies' => self::COMPANIES,
            'internships' => self::INTERNSHIPS,
            default => throw new Exception('Invalid collection'),
        };
    }
}
