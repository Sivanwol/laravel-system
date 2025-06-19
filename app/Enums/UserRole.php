<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'super_admin';
    case PLATFORM_ADMIN = 'admin';
    case USER = 'user';
    case MANAGER = 'manager';
    case DEVELOPER = 'developer';

    /**
     * Get all roles as an array of values
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get roles for admin panel access
     *
     * @return array<string>
     */
    public static function adminPanelRoles(): array
    {
        return [self::ADMIN->value, self::PLATFORM_ADMIN->value];
    }
}
