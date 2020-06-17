<?php

namespace api\modules\v1\models;

use common\models\User;
use yii\helpers\Json;

/**
 * User model for API
 */
class ApiUser extends User
{
    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'id' => 'id',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'middle_name' => 'middle_name',
            // 'email' => 'email',
            'country' => 'country',
            'language' => 'language',
            'group_id' => 'group_id',
        ];
    }
}
