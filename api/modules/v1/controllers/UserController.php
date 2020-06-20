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
use common\enums\UserRole;
use Yii;
use yii\filters\auth\HttpBearerAuth;
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
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only' => ['update'],
        ];

        return $behaviors;
    }

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

    public function actionUpdate($id)
    {
        $model = ApiUser::find()->where(['id' => $id])->one();
        if (!$model) {
            throw new NotFoundHttpException('User not found');
        }

        $user = Yii::$app->user;

        if ($user->id !== $model->id && !$user->can(UserRole::ADMIN)) {
            throw new ForbiddenHttpException('Access denied');
        }

        $form = new UserForm();

        if ($form->load(Yii::$app->request->post(), '')) {
            if ($form->edit($model)) {
                return Yii::$app->response->setStatusCode(204, 'Profile updated');
            }

            return $form;
        }
        throw new BadRequestHttpException('Body required');
    }

    public function actionView($id)
    {
        $model = ApiUser::find()->where(['id' => $id])->one();
        if (!$model) {
            throw new NotFoundHttpException('User not found');
        }

        return $model;
    }
}
