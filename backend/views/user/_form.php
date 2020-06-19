<?php

use common\enums\Language;
use common\enums\UserRole;
use common\enums\UserStatus;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'language')->dropDownList(Language::getList()) ?>

    <?= $form->field($model, 'premium_until')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(UserStatus::getList()) ?>

    <?= $form->field($model, 'role')->dropDownList(UserRole::getList()) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
