<?php
/**
 * @noinspection PhpUnused Controller actions
 */

namespace api\modules\v1\controllers;

use api\components\ModelApiController;
use api\modules\v1\models\ApiBoard;
use api\modules\v1\models\ApiCategory;
use api\models\query\LinkQuery;
use yii\web\NotFoundHttpException;

/**
 * RESTFul controller for model
 */
class CategoryController extends ModelApiController
{
    const MODEL_CLASS = ApiCategory::class;

    /**
     * Get list of all Models
     *
     * @param integer $board_id
     *
     * @return ApiCategory[]|array
     */
    public function actionIndex($board_id)
    {
        $board = ApiBoard::findOne($board_id);
        if (!$board) {
            throw new NotFoundHttpException('Board not found');
        }
        $this->checkModelAccess($board);

        return ApiCategory::find()
            ->with([
                'links' => static function (LinkQuery $query) {
                    $query->sort();
                },
            ])
            ->where(['board_id' => $board->id])
            ->sort()
            ->all();
    }
}
