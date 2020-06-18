<?php

use common\enums\Language;
use common\enums\UserRole;
use common\enums\UserStatus;
use common\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'email:email',
            'new_email:email',
            'premium_until',
            [
                'attribute' => 'language',
                'filter' => Language::getList(),
                'value' => static function (User $model) {
                    return Language::getLabel($model->language);
                },
            ],
            [
                'attribute' => 'status',
                'filter' => UserStatus::getList(),
                'value' => static function (User $model) {
                    return UserStatus::getLabel($model->status);
                },
            ],
            [
                'attribute' => 'role',
                'filter' => UserRole::getList(),
                'value' => static function (User $model) {
                    return UserRole::getLabel($model->role);
                },
            ],
            'created_at',
            ['class' => '\backend\components\grid\BigActionColumn'],
        ],
    ]); ?>
</div>
