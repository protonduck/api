<?php


namespace common\enums;


class UserStatus extends BasicEnum
{
    const __default = self::ACTIVE;
    const BANNED = 0;
    const ACTIVE = 1;

    protected static function labels()
    {
        return [
            self::BANNED => 'Banned',
            self::ACTIVE => 'Active',
        ];
    }
}
