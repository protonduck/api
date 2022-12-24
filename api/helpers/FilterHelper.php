<?php

namespace api\helpers;

class FilterHelper
{
    /**
     * Check value type and trim string
     *
     * @param mixed $value
     *
     * @return string|null
     */
    public static function trim($value): ?string
    {
        return !is_string($value) ? null : trim($value);
    }
}
