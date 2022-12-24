<?php

namespace api\components;

use Yii;
use api\modules\v1\models\ApiBoard;
use api\modules\v1\models\ApiCategory;
use api\modules\v1\models\ApiLink;
use api\helpers\UserHelper;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Common API controller for models: ApiBoard, ApiCategory, ApiLink
 */
class ModelApiController extends ApiController
{
    const MODEL_CLASS = null;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
        ]);
    }

    /**
     * @return ApiBoard|ApiCategory|ApiLink|object
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public static function getModel()
    {
        if (static::MODEL_CLASS === null) {
            throw new InvalidConfigException(__CLASS__ . '::MODEL_CLASS required');
        }

        return Yii::createObject(static::MODEL_CLASS);
    }

    /**
     * Get model
     *
     * @param int $id
     *
     * @return ApiBoard|ApiCategory|ApiLink|array
     */
    public function actionView($id)
    {
        return $this->findModel($id);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = static::getModel();

        if (!$model->load(Yii::$app->request->post())) {
            throw new BadRequestHttpException('Body required');
        }
        if ($model->save()) {
            $this->response->setStatusCode(201);
        }

        return $model;
    }

    /**
     * Updates an existing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!$model->load(Yii::$app->request->post())) {
            throw new BadRequestHttpException('Body required');
        }
        $model->save();

        return $model;
    }

    /**
     * Deletes an existing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        $this->response->setStatusCode(204);
    }

    /**
     * Finds model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * If user unable to access to this model a 403 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return ApiBoard|ApiCategory|ApiLink the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\web\ForbiddenHttpException if the cannot access to the model
     */
    protected function findModel($id)
    {
        $modelClass = static::getModel();
        if (($model = $modelClass::findOne($id)) !== null) {
            // Check access to the model for currently logged in user
            $this->checkModelAccess($model);

            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Check access for current logged in user to selected model
     *
     * @param ApiBoard|ApiCategory|ApiLink $model
     *
     * @return bool
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\ForbiddenHttpException if the cannot access to the model
     */
    protected function checkModelAccess($model): bool
    {
        $userId = UserHelper::getCurrentId();
        $accessAllowed = null;

        // Determine model class
        // Board should be visible for all, or user is owner
        if ($model instanceof ApiBoard) {
            $accessAllowed = $model->visibility || $model->user_id == $userId;
        }
        if (($model instanceof ApiCategory) && $model->board) {
            $accessAllowed = $model->board->visibility || $model->board->user_id == $userId;
        }
        if (($model instanceof ApiLink) && isset($model->category->board)) {
            $accessAllowed = $model->category->board->visibility || $model->category->board->user_id == $userId;
        }

        // if access denied
        if ($accessAllowed === false) {
            throw new ForbiddenHttpException('Not allowed');
        }
        // if access allowed
        if ($accessAllowed === true) {
            return true;
        }

        // if unable to determine model class
        throw new ServerErrorHttpException('Need to update checkModelAccess() method for this model');
    }
}
