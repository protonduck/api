<?php
/**
 * @noinspection PhpUnused Controller actions
 */

namespace api\modules\v1\controllers;

use api\components\ModelApiController;
use api\modules\v1\models\ApiBoard;
use api\helpers\UserHelper;
use common\models\query\CategoryQuery;
use common\models\query\LinkQuery;
use yii\db\ActiveQuery;

/**
 * RESTFul controller for model
 */
class BoardController extends ModelApiController
{
    const MODEL_CLASS = ApiBoard::class;

    /**
     * Get list of all Models
     *
     * @return ApiBoard[]|array
     */
    public function actionIndex()
    {
        return ApiBoard::find()
            ->with([
                'categories' => static function (CategoryQuery $query) {
                    $query->sort()->with([
                        'links' => static function (LinkQuery $query) {
                            $query->sort();
                        },
                    ]);
                },
            ])
            ->where(['user_id' => UserHelper::getCurrentId()])
            ->sort()
            ->all();
    }
}
