<?php

namespace App;

enum UserRole: string
{
    case SUPER_ADMIN = 'Super Admin';
    case ADMIN = 'Admin';
    case CITIZEN = 'Citizen';

    /***********************
     * To get all user roles in string *
     **********************/
    public static function casesInString() : array {
        return [
            Self::SUPER_ADMIN->value,
            Self::ADMIN->value,
            Self::CITIZEN->value,
        ];
    }

    /***********************
     * To get array of admin and above access *
     **********************/
    public static function adminAccess(string $type = 'enum') : array {
        return match ($type) {
            'string' => [Self::SUPER_ADMIN->value, Self::ADMIN->value],
            'enum' => [Self::SUPER_ADMIN, Self::ADMIN],
        };
    }
}
