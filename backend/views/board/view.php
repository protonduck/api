<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Board */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Boards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="board-view">

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
                'attribute' => 'user_id',
                'format' => 'raw',
                'value' => Html::a($model->user->fName ?? $model->user_id, ['user/view', 'id' => $model->user_id]),
            ],
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => $model->image ? Html::img(Html::encode($model->image)) : Yii::$app->formatter->nullDisplay,
            ],
            'visibility:boolean',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
