<?php

namespace api\modules\v1\forms;

use common\enums\Language;
use common\helpers\UserHelper;
use common\models\SecureKey;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\db\Query;

/**
 * Signup form
 */
class UserForm extends Model
{
    public $name;
    public $email;
    public $password;
    public $language;

    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // required
            [['name', 'password', 'email'], 'required'],
            // string
            [['name', 'email'], 'string', 'max' => 255],
            [['password'], 'string', 'min' => 6, 'max' => 100],
            // email
            [['email'], 'email'],
            // range
            [['language'], 'in', 'range' => Language::getKeys()],
            // unique
            [
                ['email'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email',
                'filter' => static function (Query $query) {
                    $query->andWhere(['!=', 'id', UserHelper::getCurrentId()]);
                }
            ],
            // default
            [['language'], 'default', 'value' => Language::getDefault()],
        ];
    }

    /**
     * Update profile
     *
     * @param \common\models\User $user
     *
     * @return bool
     */
    public function update(User $user)
    {
        if (!$this->validate()) {
            return false;
        }
        $user->name = $this->name;
        $user->language = $this->language;

        // Change password
        if ($this->password) {
            $user->setPassword($this->password);
        }

        // Save changes
        if (!$user->save()) {
            $this->addError('first_name', 'Unknown error');

            return false;
        }

        // Send changing email
        if ($this->email) {
            SecureKey::create(SecureKey::TYPE_CHANGE_EMAIL, $user, $this->email);
        }

        return true;
    }
}
