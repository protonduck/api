<?php

namespace common\fixtures;

use common\models\Link;
use yii\test\ActiveFixture;

class LinkFixture extends ActiveFixture
{
    public $modelClass = Link::class;
    public $depends = [CategoryFixture::class, DomainFixture::class];
}
