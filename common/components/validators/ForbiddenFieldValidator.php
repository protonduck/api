<?php

namespace common\components\validators;

use common\enums\UserRole;
use common\helpers\UserHelper;
use yii\validators\Validator;

/**
 * ForbiddenSearchFieldValidator
 */
class ForbiddenFieldValidator extends Validator
{
    public $permissionName = UserRole::MODERATOR;

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        if ($model->$attribute !== null && !UserHelper::can($this->permissionName)) {
            $model->addError($attribute, "$attribute can have value only if client has {$this->permissionName} access");
        }
    }
}
