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
            'category_id' => 'category_id',
            'title' => 'title',
            'description' => 'description',
            'is_favorite' => static function (self $model) {
                return (bool)$model->is_favorite;
            },
            'favicon' => 'favicon',
            'target' => 'target',
            'hits' => 'hits',
            'http_status_code' => 'http_status_code',
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
