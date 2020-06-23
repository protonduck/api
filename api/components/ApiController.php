<?php

namespace api\components;

use yii\filters\auth\HttpBearerAuth;
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
                'class' => HttpBearerAuth::class,
            ],
            'corsFilter' => [
                // Access-Control-Allow-Origin: *
                'class' => Cors::class,
            ],
        ]);
    }
}
