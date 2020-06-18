<?php

use common\enums\LinkTarget;
use common\models\Link;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Link */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="link-view">

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
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => static function (Link $model) {
                    return Html::a(Html::encode($model->url), Html::encode($model->url), ['target' => '_blank']);
                },
            ],
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => static function (Link $model) {
                    return Html::a($model->category->fName ?? $model->category_id,
                        ['category/view', 'id' => $model->category_id]);
                },
            ],
            [
                'attribute' => 'domain_id',
                'format' => 'raw',
                'value' => static function (Link $model) {
                    return Html::a($model->domain->fName ?? $model->domain_id,
                        ['domain/view', 'id' => $model->domain_id]);
                },
            ],
            'title',
            'description',
            'is_favorite:boolean',
            'favicon',
            [
                'attribute' => 'target',
                'value' => static function (Link $model) {
                    return LinkTarget::getLabel($model->target);
                },
            ],
            'hits',
            'http_status_code',
            'checked_at',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
