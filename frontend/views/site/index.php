<?php

/* @var $this yii\web\View */

$this->title = 'Bookmarks';
$hostname = Yii::$app->request->hostName;
$endpoint = Yii::$app->request->hostInfo . '/v1';
$endpoint = str_replace($hostname, 'api.' . $hostname, $endpoint);
?>
<div id="app" endpoint="<?= $endpoint ?>"></div>
