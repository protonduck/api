<?php

use common\enums\DomainSslStatus;
use common\models\Domain;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DomainSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Domains';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="domain-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Domain', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            [
                'attribute' => 'ssl_status',
                'filter' => DomainSslStatus::getList(),
                'value' => static function (Domain $model) {
                    return DomainSslStatus::getLabel($model->ssl_status);
                },
            ],
            'checked_at',
            'created_at',
            ['class' => '\backend\components\grid\BigActionColumn'],
        ],
    ]); ?>
</div>
