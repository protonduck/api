<?php

namespace api\enums;

/**
 * Languages enumerable class
 */
class Language extends BasicEnum
{
    const __default = self::EN;

    const EN = 'en';
    const RU = 'ru';

    protected static function labels()
    {
        return [
            self::EN => 'English',
            self::RU => 'Русский',
        ];
    }
}
