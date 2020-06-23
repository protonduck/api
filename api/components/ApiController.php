<?php

namespace api\components;

use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

/**
 * Base API controller, bearer authentification
 */
class ApiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                'class' => QueryParamAuth::class,
            ],
            'corsFilter' => [
                // Access-Control-Allow-Origin: *
                'class' => Cors::class,
//                'cors' => [
//                    'Origin' => ['*'],
//                    'Access-Control-Allow-Credentials' => true,
//                    'Access-Control-Max-Age' => 86400,
//                    'Access-Control-Request-Headers' => ['*'],
//                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
//                    'Access-Control-Expose-Headers' => [],
//                ],
            ],
        ]);
    }
}
