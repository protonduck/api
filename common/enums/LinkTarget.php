<?php

namespace common\enums;

/**
 * Link Targets enumerable class
 */
class LinkTarget extends BasicEnum
{
    const __default = self::NORMAL;

    const NORMAL = 0;
    const BLANK = 1;

    protected static function labels()
    {
        return [
            self::NORMAL => 'Normal',
            self::BLANK => 'Blank',
        ];
    }
}
