<?php

namespace common\fixtures;

use common\models\SecureKey;
use yii\test\ActiveFixture;

class SecureKeyFixture extends ActiveFixture
{
    public $modelClass = SecureKey::class;
    public $depends = [UserFixture::class];
}
