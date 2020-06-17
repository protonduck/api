<?php


namespace common\enums;


class UserRole extends BasicEnum
{
    const __default = self::USER;
    const USER = 'user';
    const MODERATOR = 'moderator';

    protected static function labels()
    {
        return [
            self::USER => 'User',
            self::MODERATOR => 'Moderator',
        ];
    }
}
