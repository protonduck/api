<?php
/**
 * @noinspection PhpUnused Controller actions
 */

namespace api\modules\v1\controllers;

use api\components\ApiController;
use api\models\LoginForm;
use api\models\SignupForm;
use api\modules\v1\forms\UserForm;
use api\modules\v1\models\ApiUser;
use api\enums\UserRole;
use api\helpers\UserHelper;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * REST controller for user actions
 */
class UserController extends ApiController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                'only' => ['update', 'view'],
            ],
        ]);
    }

    /**
     * Login
     *
     * @return \api\models\LoginForm|\api\modules\v1\models\ApiUser|array
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post(), '')) {
            $result = $model->login();
            if ($result instanceof ApiUser) {
                $array = $result->toArray();
                $array['api_key'] = $result->api_key;

                return $array;
            }

            return $result; // Validation errors
        }

        throw new BadRequestHttpException('Body required');
    }

    /**
     * Signup
     *
     * @return \api\models\SignupForm|\api\modules\v1\models\ApiUser|array
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post(), '')) {
            $user = $model->signup();

            if ($user instanceof ApiUser) {
                $array = $user->toArray();
                $array['api_key'] = $user->api_key;

                return $array;
            }

            return $user; // validation errors
        }

        throw new BadRequestHttpException('Body required');
    }

    /**
     * Update profile
     *
     * @return \api\modules\v1\forms\UserForm|\api\modules\v1\models\ApiUser|array
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate()
    {
        $model = ApiUser::find()->where(['id' => UserHelper::getCurrentId()])->active()->one();
        if (!$model) {
            throw new NotFoundHttpException('User not found');
        }

        $form = new UserForm();

        if ($form->load(Yii::$app->request->post())) {
            if ($form->update($model)) {
                return $model; // successfully updated
            }

            return $form; // validation errors
        }
        throw new BadRequestHttpException('Body required');
    }

    /**
     * Profile info
     *
     * @return \api\modules\v1\models\ApiUser|array|\common\models\User|null
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView()
    {
        $model = ApiUser::find()->where(['id' => UserHelper::getCurrentId()])->active()->one();
        if (!$model) {
            throw new NotFoundHttpException('User not found');
        }

        return $model;
    }
}
