<?php

use common\components\WebUser;
use common\models\User;
use yii\web\GroupUrlRule;
use yii\web\JsonParser;
use yii\web\JsonResponseFormatter;
use yii\web\Response;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'modules' => [
        'v1' => [
            'class' => api\modules\v1\Module::class,
        ],
    ],
    'components' => [
        'request' => [
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => JsonParser::class,
            ],
        ],
        'response' => [
            'formatters' => [
                Response::FORMAT_JSON => [
                    'class' => JsonResponseFormatter::class,
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'user' => [
            'class' => WebUser::class,
            'identityClass' => User::class,
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null,
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => '/site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                // latest api docs
                'GET docs' => 'v1/docs/index', // alias for "/v1/docs"
                [
                    'class' => GroupUrlRule::class,
                    'prefix' => 'v1',
                    'rules' => [
                        'POST user/signup' => 'user/signup',
                        'POST user/login' => 'user/login',
                        'GET user/<id:\d+>' => 'user/view',
                        'PUT user/<id:\d+>' => 'user/update',
                        'GET docs' => 'docs/index',
                        'GET docs/resource' => 'docs/resource',
                    ],
                ],
                // RESTFul
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/bookmark',
//                    'extraPatterns' => [
//                        'GET current' => 'current',
//                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];
