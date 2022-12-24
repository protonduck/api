<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user api\models\User */
/* @var $key api\models\SecureKey */

$link = $key->url;
?>
Здравствуйте <?= $user->fName ?>,

Для подтверждения вашего email-адреса и активации аккаунта, пройтите по этой ссылке:

<?= $link ?>

Ссылка действительна до: <?= $key->getFormattedExpireTime() ?>
