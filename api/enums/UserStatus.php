<?php

namespace api\enums;

/**
 * User Statuses enumerable class
 */
class UserStatus extends BasicEnum
{
    const __default = self::ACTIVE;

    const PENDING = 0;
    const ACTIVE = 1;
    const BANNED = 2;

    protected static function labels()
    {
        return [
            self::PENDING => 'Pending',
            self::ACTIVE => 'Active',
            self::BANNED => 'Banned',
        ];
    }
}
