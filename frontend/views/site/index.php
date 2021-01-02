<?php

/* @var $this yii\web\View */

$this->title = 'Bookmarks';

if (!empty(Yii::$app->params['api_endpoint'])) {
    $endpoint = Yii::$app->params['api_endpoint'];
} else {
    $hostname = Yii::$app->request->hostName;
    $endpoint = Yii::$app->request->hostInfo . '/v1';
    $endpoint = str_replace($hostname, 'api.' . $hostname, $endpoint);
}

?>

<div id="app" endpoint="<?= $endpoint ?>"></div>
