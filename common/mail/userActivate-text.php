<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $key common\models\SecureKey */

$link = $key->url;
?>
Hello <?= $user->fName ?>,

Follow the link below to activate your account:

<?= $link ?>

Link is valid until: <?= $key->getFormattedExpireTime() ?>
