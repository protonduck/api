<?php

/* @var $this yii\web\View */

$this->title = 'API docs';

\api\assets\SwaggerAsset::register($this);
?>
<div id="swagger-ui"></div>

<script>
    window.onload = function () {
        // Begin Swagger UI call region
        const ui = SwaggerUIBundle({
            url: '<?= \yii\helpers\Url::to(['docs/resource'])?>',
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "StandaloneLayout"
        })
        // End Swagger UI call region

        window.ui = ui
    }
</script>
