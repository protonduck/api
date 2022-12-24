<?php

namespace api\fixtures;

use api\models\Board;
use yii\test\ActiveFixture;

class BoardFixture extends ActiveFixture
{
    public $modelClass = Board::class;
    public $depends = [UserFixture::class];
}
