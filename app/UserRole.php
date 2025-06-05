<?php

namespace App;

enum UserRole: string
{
    case SUPER_ADMIN = 'Super Admin';
    case ADMIN = 'Admin';
    case CITIZEN = 'Citizen';
    case MK = 'MK';

    /***********************
     * To get all user roles in string *
     **********************/
    public static function casesInString(): array
    {
        return [
            Self::SUPER_ADMIN->value,
            Self::ADMIN->value,
            Self::CITIZEN->value,
            Self::MK->value,
        ];
    }

    /***********************
     * To get array of admin and above access *
     **********************/
    public static function adminAccess(string $type = 'enum'): array
    {
        return match ($type) {
            'string' => [Self::SUPER_ADMIN->value, Self::ADMIN->value, Self::MK->value],
            'enum' => [Self::SUPER_ADMIN, Self::ADMIN, Self::MK],
        };
    }
}
