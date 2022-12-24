<?php

namespace api\fixtures;

use api\models\Link;
use yii\test\ActiveFixture;

class LinkFixture extends ActiveFixture
{
    public $modelClass = Link::class;
    public $depends = [CategoryFixture::class];
}
