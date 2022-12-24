<?php

namespace api\fixtures;

use api\models\Category;
use yii\test\ActiveFixture;

class CategoryFixture extends ActiveFixture
{
    public $modelClass = Category::class;
    public $depends = [BoardFixture::class];
}
