<?php


namespace common\enums;


class Language extends BasicEnum
{
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
