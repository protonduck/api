<?php

namespace api\models\query;

/**
 * This is the ActiveQuery class for [[\api\models\Link]].
 *
 * @see \api\models\Link
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
     * @return \api\models\Link[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \api\models\Link|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
