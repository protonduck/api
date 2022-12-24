<?php

namespace api\models\query;

/**
 * This is the ActiveQuery class for [[\api\models\Domain]].
 *
 * @see \api\models\Domain
 */
class DomainQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \api\models\Domain[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \api\models\Domain|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
