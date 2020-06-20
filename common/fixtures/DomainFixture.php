<?php

namespace common\fixtures;

use common\models\Domain;
use yii\test\ActiveFixture;

class DomainFixture extends ActiveFixture
{
    public $modelClass = Domain::class;
    public $depends = [];
}
