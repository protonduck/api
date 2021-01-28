<?php
return [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        // fix console create url
        'urlManager' => [
            'baseUrl' => 'http://admin.bookmarks.local:8025',
        ],
    ],
];
