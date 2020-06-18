<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */

// prepend # for color value
$model->color = mb_strlen($model->color) ? '#' . $model->color : null;
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'board_id')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'color')->input('color') ?>

    <?= $form->field($model, 'icon', [
        'inputTemplate' => '<div class="input-group"><span class="input-group-addon"><i id="selected-icon" class="' .
            Html::encode($model->icon) . '"></i></span>{input}</div>',
    ])->textInput(['maxlength' => true, 'onkeyup' => "$('#selected-icon').attr('class', $(this).val());"]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
