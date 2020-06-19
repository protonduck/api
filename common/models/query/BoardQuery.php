<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Board]].
 *
 * @see \common\models\Board
 */
class BoardQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * Apply default sorting
     *
     * @return $this
     */
    public function sort()
    {
        return $this->orderBy(['sort' => SORT_ASC]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Board[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Board|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
