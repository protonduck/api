<?php

namespace api\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $login;
    public $password;

    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // login and password are both required
            [['login', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params     the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect login or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided login and password.
     *
     * @return array|$this whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return ['api_key' => $this->getUser()->api_key];
        }

        return $this;
    }

    /**
     * Finds user by [[login]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->login);
        }

        return $this->_user;
    }
}
