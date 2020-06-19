<?php

use common\models\Category;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            [
                'attribute' => 'board_id',
                'format' => 'raw',
                'value' => static function (Category $model) {
                    return Html::a($model->board->fName ?? $model->board_id, ['board/view', 'id' => $model->board_id]);
                },
            ],
            'description',
            [
                'attribute' => 'color',
                'format' => 'raw',
                'value' => static function (Category $model) {
                    if (!$model->color) {
                        return Yii::$app->formatter->nullDisplay;
                    }

                    return '<div style="width: 100%; background-color: #' . Html::encode($model->color) . ';">&nbsp;</div>';
                },
            ],
            [
                'attribute' => 'icon',
                'format' => 'raw',
                'value' => static function (Category $model) {
                    if (!$model->icon) {
                        return Yii::$app->formatter->nullDisplay;
                    }

                    return '<i class="' . Html::encode($model->icon) . '"></i> ' . Html::encode($model->icon);
                },
            ],
            'created_at',
            [
                'class' => 'demi\sort\SortColumn',
                'action' => 'change-sort', // optional
                'visible' => (bool)$searchModel->board_id,
            ],
            ['class' => '\backend\components\grid\BigActionColumn'],
        ],
    ]); ?>
</div>
