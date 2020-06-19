<?php

use common\models\Board;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BoardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Boards';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Board', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            ['class' => '\backend\components\grid\UserColumn'],
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => static function (Board $model) {
                    return $model->image ? Html::img(Html::encode($model->image)) : Yii::$app->formatter->nullDisplay;
                },
            ],
            'visibility:boolean',
            [
                'class' => 'demi\sort\SortColumn',
                'action' => 'change-sort', // optional
                'visible' => (bool)$searchModel->user_id,
            ],
            'created_at',
            ['class' => '\backend\components\grid\BigActionColumn'],
        ],
    ]); ?>
</div>
