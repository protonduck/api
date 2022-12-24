<?php

namespace api\fixtures;

use common\models\Category;
use yii\test\ActiveFixture;

class CategoryFixture extends ActiveFixture
{
    public $modelClass = Category::class;
    public $depends = [BoardFixture::class];
}
