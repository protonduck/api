<?php

namespace common\enums;

/**
 * Domain SSL statuses enumerable class
 */
class DomainSslStatus extends BasicEnum
{
    const __default = self::NO_SSL;

    const NO_SSL = 0;
    const TRUSTED = 1;
    const EXPIRED = 2;

    protected static function labels()
    {
        return [
            self::NO_SSL => 'No SSL',
            self::TRUSTED => 'Trusted',
            self::EXPIRED => 'Expired',
        ];
    }
}
