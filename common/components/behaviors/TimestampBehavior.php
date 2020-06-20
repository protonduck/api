<?php

namespace common\components\behaviors;

use yii\db\Expression;

/**
 * Yii2 TimestampBehavior for date columns in format: Y-m-d H:i:s
 */
class TimestampBehavior extends \yii\behaviors\TimestampBehavior
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // Override the default value time() to `NOW()`
        if (empty($this->value)) {
            $this->value = date('Y-m-d H:i:s'); // new Expression('NOW()')
        }
    }
}
