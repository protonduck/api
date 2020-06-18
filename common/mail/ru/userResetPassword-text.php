<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $key common\models\SecureKey */

$link = $key->url;
?>
Здравствуйте <?= $user->fName ?>,

Для изменения пароля, пройтите по этой ссылке:

<?= $link ?>

Ссылка действительна до: <?= $key->getFormattedExpireTime() ?>
