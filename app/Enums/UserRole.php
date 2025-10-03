<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case CLIENT = 'client';
    case DRIVER = 'driver';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
