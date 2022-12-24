<?php

namespace api\fixtures;

use api\models\User;
use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;
}
