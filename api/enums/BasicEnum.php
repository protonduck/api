<?php

namespace api\enums;

/**
 * Base enumerable class
 *
 * USAGE:
 *
 * // simple
 * $user->status = UserStatus::ACTIVE;
 * $user->status = UserStatus::getDefault();
 *
 * // rules
 * [['status'], 'in', 'range' => UserStatus::getKeys()],
 *
 * // grid filter and value
 * [
 *     'attribute' => 'status',
 *     'filter' => UserStatus::getList(),
 *     'value' => function ($model) {
 *         return UserStatus::getLabel($model->status);
 *     },
 * ]
 */
abstract class BasicEnum
{
    /**
     * @var mixed Default value (key)
     */
    const __default = null;

    /**
     * Return list of key=>label
     *
     * @return array
     */
    protected static function labels()
    {
        return [];
    }

    /**
     * Get list of all data: key=>label
     *
     * @return array
     */
    public static function getList()
    {
        return static::labels();
    }

    /**
     * Get label value. Null if not exists
     *
     * @param string $key
     *
     * @return string|null
     */
    public static function getLabel($key)
    {
        $labels = static::labels();

        return isset($labels[$key]) ? $labels[$key] : null;
    }

    /**
     * Get list of keys
     *
     * @return array
     */
    public static function getKeys()
    {
        return array_keys(static::labels());
    }

    /**
     * Get default value for this enumerable list
     *
     * @return mixed|null
     */
    public static function getDefault()
    {
        return static::__default;
    }
}