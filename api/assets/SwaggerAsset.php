<?php

namespace api\assets;

use yii\web\AssetBundle;

/**
 * Swagger asset bundle.
 */
class SwaggerAsset extends AssetBundle
{
    public $sourcePath = '@api/assets/swagger';
    public $baseUrl = '@web';
    public $css = [
        'css/swagger-ui.css',
    ];
    public $js = [
        'js/swagger-ui-bundle.js',
        'js/swagger-ui-standalone-preset.js',
    ];
    public $depends = [
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
}
