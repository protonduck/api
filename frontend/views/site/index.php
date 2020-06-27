<?php

/* @var $this yii\web\View */

$this->title = 'Boards';

?>

<?php if (!Yii::$app->user->isGuest) : ?>
    <board-component
        endpoint="http://api.bookmarks.local:8025/v1/boards"
        accesstoken="<?= Yii::$app->user->identity->api_key ?>"
    ></board-component>
<?php else: ?>
    Please login.
<?php endif; ?>
