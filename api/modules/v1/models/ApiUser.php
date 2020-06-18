<?php

namespace api\modules\v1\models;

use common\models\User;

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
            'name' => 'name',
            'language' => 'language',
        ];
    }
}
