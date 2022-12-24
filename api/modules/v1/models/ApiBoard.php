<?php

namespace api\modules\v1\models;

use api\helpers\TimeHelper;
use api\helpers\UserHelper;
use common\models\Board;

/**
 * Board model for API
 *
 * @property ApiCategory[] $categories
 */
class ApiBoard extends Board
{
    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'image' => 'image',
            'visibility' => 'visibility',
            'sort' => 'sort',
            'created_at' => TimeHelper::dateTimeToUnix('created_at'),
            'updated_at' => TimeHelper::dateTimeToUnix('updated_at'),
            'categories' => static function (self $model) {
                return $model->categories;
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function extraFields()
    {
        return ['categories'];
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
    public function getCategories()
    {
        return $this->hasMany(ApiCategory::class, ['board_id' => 'id'])->inverseOf('board');
    }

    /**
     * {@inheritdoc}
     */
    public function beforeValidate()
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        if (!$this->user_id) {
            $this->user_id = UserHelper::getCurrentId();
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(array $fields = [], array $expand = ['categories'], $recursive = true)
    {
        return parent::toArray($fields, $expand, $recursive);
    }
}
