<?php

use common\models\Link;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LinkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Links';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="link-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Link', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
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
            //'description',
            //'is_favorite',
            //'favicon',
            //'target',
            'hits',
            'http_status_code',
            'checked_at',
            'created_at',
            [
                'class' => 'demi\sort\SortColumn',
                'action' => 'change-sort', // optional
                'visible' => (bool)$searchModel->category_id,
            ],
            ['class' => '\backend\components\grid\BigActionColumn'],
        ],
    ]); ?>
</div>
