<?php

namespace common\helpers;

use common\enums\UserRole;
use common\models\User;
use Yii;

/**
 * UserHelper
 */
class UserHelper
{
    /**
     * Check that user has access
     *
     * @param string $permissionName Permission name
     * @param null|int $userId       But default - current logged in user
     *
     * @return bool
     */
    public static function can($permissionName, $userId = null)
    {
        if ($userId) {
            return User::find()->where(['id' => $userId, 'role' => $permissionName])->exists();
        }

        return Yii::$app->has('user') && Yii::$app->user->can($permissionName);
    }

    /**
     * Check that user has moderator access
     *
     * @param null|int $userId But default - current logged in user
     *
     * @return bool
     */
    public static function isModerator($userId = null)
    {
        return static::can(UserRole::ADMIN, $userId);
    }

    /**
     * Return current logged id
     *
     * @return int|null
     */
    public static function getCurrentId()
    {
        return Yii::$app->has('user') ? Yii::$app->user->id : null;
    }
}
