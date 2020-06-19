<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Link]].
 *
 * @see \common\models\Link
 */
class LinkQuery extends \yii\db\ActiveQuery
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
     * @return \common\models\Link[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Link|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
