<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user api\models\User */
/* @var $key api\models\SecureKey */

$link = $key->url;
?>
Hello <?= $user->fName ?>,

Follow the link below to reset your password:

<?= $link ?>

Link is valid until: <?= $key->getFormattedExpireTime() ?>
