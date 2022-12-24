<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user api\models\User */
/* @var $key api\models\SecureKey */

$link = $key->url;
?>
<div>
    <p>Hello <?= $user->fName ?>,</p>
    <br/>
    <p>Follow the link below to reset your password:</p>
    <p>
        <?= Html::a(Html::encode($link), $link) ?>
        <br/>
        Link is valid until: <?= $key->getFormattedExpireTime() ?>
    </p>
</div>