<?php


namespace api\components;

use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\rest\IndexAction;

/**
 * Base ActiveController
 */
class ActiveApiController extends ActiveController
{
    /**
     * Data filter to be used for the search filter composition.
     *
     * @var array|null Object config for [\yii\data\DataFilter]
     * @see \yii\rest\IndexAction::$dataFilter
     */
    public $dataFilter;
    /**
     * Prepare a data provider that should return a collection of the models
     *
     * Just set this value to `[$this, 'prepareDataProvider']` in the `public function init()`
     * and create new controller method:
     *
     * ```
     * public function prepareDataProvider(\yii\rest\IndexAction $action, \yii\data\DataFilter $filter = null)
     * {
     *     $searchModel = new PostSearch();
     *     return $searchModel->search(Yii::$app->request->queryParams);
     * }
     * ```
     *
     * @var callable|null
     */
    public $prepareDataProvider;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = $this->prepareDataProvider;
        $actions['index']['dataFilter'] = $this->dataFilter;

        return $actions;
    }

    /**
     * Prepare a data provider that should return a collection of the models
     *
     * @param IndexAction $action
     * @param \yii\data\DataFilter|null $filter
     *
     * @return \yii\data\ActiveDataProvider|null
     * @see \yii\rest\IndexAction::$prepareDataProvider
     */
    public function prepareDataProvider(IndexAction $action, $filter = null)
    {
        return null;
    }
}
