<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $key common\models\SecureKey */

$link = $key->url;
?>
<div>
    <p>Здравствуйте <?= $user->fName ?>,</p>
    <br/>
    <p>Для подтверждения вашего <span style="text-decoration: underline">нового</span> email-адреса, пройтите по этой ссылке:</p>
    <p>
        <?= Html::a(Html::encode($link), $link) ?>
        <br/>
        Ссылка действительна до: <?= $key->getFormattedExpireTime() ?>
    </p>
</div>
