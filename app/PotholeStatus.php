<?php

namespace App;

use Str;

enum PotholeStatus: string
{
    case All = 'All'; // Only for check
    case OPEN = 'Open';
    case ASSIGNED = 'Assigned';
    case IN_PROGRESS = 'In Progress';
    case RESOLVED = 'Resolved';
    case VERIFIED = 'Verified';

    /***********************
     * To get all pothole status in string *
     **********************/
    public static function casesInString(): array
    {
        return [
            Self::OPEN->value,
            Self::ASSIGNED->value,
            Self::IN_PROGRESS->value,
            Self::RESOLVED->value,
            Self::VERIFIED->value
        ];
    }

    /***********************
     * To get all pothole status in kebab case *
     **********************/
    public static function casesInKabab(): array
    {
        return [
            Str::kebab(Self::OPEN->value),
            Str::kebab(Self::ASSIGNED->value),
            Str::kebab(Self::IN_PROGRESS->value),
            Str::kebab(Self::RESOLVED->value),
            Str::kebab(Self::VERIFIED->value)
        ];
    }
}
