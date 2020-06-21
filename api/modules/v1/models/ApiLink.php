<?php

namespace api\modules\v1\models;

use api\helpers\TimeHelper;
use common\models\Link;

/**
 * Link model for API
 *
 * @property ApiCategory $category
 */
class ApiLink extends Link
{
    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'id' => 'id',
            'url' => 'url',
            'category_id' => static function (self $model) {
                return $model->category_id !== null ? (int)$model->category_id : null;
            },
            'title' => 'title',
            'description' => 'description',
            'is_favorite' => static function (self $model) {
                return (bool)$model->is_favorite;
            },
            'favicon' => 'favicon',
            'target' => static function (self $model) {
                return $model->target !== null ? (int)$model->target : null;
            },
            'hits' => static function (self $model) {
                return (int)$model->hits;
            },
            'http_status_code' => static function (self $model) {
                return $model->http_status_code ? (int)$model->http_status_code : null;
            },
            'sort' => 'sort',
            'created_at' => TimeHelper::dateTimeToUnix('created_at'),
            'updated_at' => TimeHelper::dateTimeToUnix('updated_at'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return '';
    }

    /**
     * Specify relation to API model instead normal model
     * {@inheritdoc}
     */
    public function getCategory()
    {
        return $this->hasOne(ApiCategory::class, ['id' => 'category_id']);
    }
}
