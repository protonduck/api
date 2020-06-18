<?php

namespace common\enums;

/**
 * User Roles enumerable class
 */
class UserRole extends BasicEnum
{
    const __default = self::USER;

    const USER = 'user';
    const ADMIN = 'admin';

    protected static function labels()
    {
        return [
            self::USER => 'User',
            self::ADMIN => 'Admin',
        ];
    }
}
