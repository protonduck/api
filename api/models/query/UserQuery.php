<?php

namespace api\models\query;

use api\enums\UserStatus;

/**
 * This is the ActiveQuery class for [[\api\models\User]].
 *
 * @see \api\models\User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /**
     * Filter only active users
     *
     * @return $this
     */
    public function active()
    {
        return $this->andWhere(['status' => UserStatus::ACTIVE]);
    }

    /**
     * {@inheritdoc}
     * @return \api\models\User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \api\models\User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
