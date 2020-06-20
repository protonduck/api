<?php

namespace api\helpers;

use yii\db\ActiveRecord;

class TimeHelper
{
    /**
     * Convert datetime string to unix format
     *
     * @param string $attribute
     *
     * @return \Closure
     */
    public static function dateTimeToUnix(string $attribute)
    {
        return static function (ActiveRecord $model) use ($attribute) {
            $unix = strtotime($model->$attribute);

            return $unix !== false ? $unix : null;
        };
    }
}
