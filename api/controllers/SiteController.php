<?php

namespace api\controllers;

use api\components\ApiController;
use Yii;
use yii\base\UserException;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\helpers\UnsetArrayValue;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends ApiController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            // No authentication required
            'authenticator' => new UnsetArrayValue(),
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Display error for API
     *
     * @return array
     */
    public function actionError()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            $exception = new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }

        Yii::$app->getResponse()->setStatusCodeByException($exception);

        if ($exception instanceof \yii\base\Exception) {
            $name = $exception->getName();
        } else {
            $name = Yii::t('yii', 'Error');
        }

        $code = $exception->getCode();
        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        }
        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            $message = Yii::t('yii', 'An internal server error occurred.');
        }

        return [
            'name' => $name,
            'message' => $message,
            'code' => $exception->getCode(),
            'status' => $code,
            'type' => get_class($exception),
        ];
    }
}
