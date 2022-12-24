<?php
return [
    'name' => 'Bookmarks',
    'language' => 'ru-RU', // en-US | ru-RU
    'sourceLanguage' => 'en',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'api\components\rbac\PhpManager',
            'itemFile' => '@api/components/rbac/items.php',
            'assignmentFile' => '@api/components/rbac/assignments.php',
            'ruleFile' => '@api/components/rbac/rules.php',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
    ],
];
