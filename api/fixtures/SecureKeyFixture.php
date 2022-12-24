<?php

namespace api\fixtures;

use api\models\SecureKey;
use yii\test\ActiveFixture;

class SecureKeyFixture extends ActiveFixture
{
    public $modelClass = SecureKey::class;
    public $depends = [UserFixture::class];
}
