<?php

use common\models\Category;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
            'updated_at',
        ],
    ]) ?>

</div>
