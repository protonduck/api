<?php

namespace api\fixtures;

use api\models\Domain;
use yii\test\ActiveFixture;

class DomainFixture extends ActiveFixture
{
    public $modelClass = Domain::class;
    public $depends = [];
}
