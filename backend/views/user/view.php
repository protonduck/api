<?php

use common\enums\Language;
use common\enums\UserRole;
use common\enums\UserStatus;
use common\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

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
            'api_key',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
