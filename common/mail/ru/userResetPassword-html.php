<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user api\models\User */
/* @var $key api\models\SecureKey */

$link = $key->url;
?>
<div>
    <p>Здравствуйте <?= $user->fName ?>,</p>
    <br/>
    <p>Для изменения пароля, пройтите по этой ссылке:</p>
    <p>
        <?= Html::a(Html::encode($link), $link) ?>
        <br/>
        Ссылка действительна до: <?= $key->getFormattedExpireTime() ?>
    </p>
</div>