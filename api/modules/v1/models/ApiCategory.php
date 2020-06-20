<?php

namespace api\modules\v1\models;

use api\helpers\TimeHelper;
use common\models\Category;

/**
 * Category model for API
 *
 * @property ApiBoard $board
 * @property ApiLink[] $links
 */
class ApiCategory extends Category
{
    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'board_id' => 'board_id',
            'description' => 'description',
            'color' => 'color',
            'icon' => 'icon',
            'sort' => 'sort',
            'created_at' => TimeHelper::dateTimeToUnix('created_at'),
            'updated_at' => TimeHelper::dateTimeToUnix('updated_at'),
            'links' => static function (self $model) {
                return $model->links;
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function extraFields()
    {
        return ['links'];
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
    public function getBoard()
    {
        return $this->hasOne(ApiBoard::class, ['id' => 'board_id']);
    }

    /**
     * Specify relation to API model instead normal model
     * {@inheritdoc}
     */
    public function getLinks()
    {
        return $this->hasMany(ApiLink::class, ['category_id' => 'id'])->inverseOf('category');
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(array $fields = [], array $expand = ['links'], $recursive = true)
    {
        return parent::toArray($fields, $expand, $recursive);
    }
}
