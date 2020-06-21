<?php
/**
 * @noinspection PhpUnused Controller actions
 */

namespace api\modules\v1\controllers;

use api\components\ModelApiController;
use api\modules\v1\models\ApiLink;
use common\helpers\UserHelper;
use common\models\query\CategoryQuery;

/**
 * RESTFul controller for model
 */
class LinkController extends ModelApiController
{
    const MODEL_CLASS = ApiLink::class;

    /**
     * Get list of all Models
     *
     * @return ApiLink[]|array
     */
    public function actionIndex()
    {
        return ApiLink::find()
            ->joinWith([
                'category' => static function (CategoryQuery $query) {
                    $query->joinWith(['board'], false);
                },
            ], false)
            ->andWhere(['boards.user_id' => UserHelper::getCurrentId()])
            ->sort()
            ->all();
    }
}
