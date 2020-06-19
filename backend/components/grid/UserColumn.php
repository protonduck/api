<?php

namespace backend\components\grid;

use yii\grid\DataColumn;
use yii\helpers\Html;

/**
 * Column for grid view
 */
class UserColumn extends DataColumn
{
    public $attribute = 'user_id';
    public $format = 'raw';
    public $filter; // @TODO Make filter as searchable dropdown list

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (!$this->value) {
            $this->value = static function ($model) {
                /* @var \yii\db\ActiveRecord|\common\models\Board $model */
                return Html::a($model->user->fName ?? $model->user_id, ['user/view', 'id' => $model->user_id]);
            };
        }
    }
}
