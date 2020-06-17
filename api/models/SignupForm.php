<?php

namespace api\models;

use api\modules\v1\models\ApiUser;
use common\enums\Language;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $login;
    public $password;
    public $email;
    public $first_name;
    public $last_name;
    public $middle_name;
    public $phone;
    public $country;
    public $language;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // required
            [['login', 'phone', 'country', 'language', 'first_name', 'password', 'email'], 'required'],
            // string
            [['login', 'first_name', 'last_name', 'middle_name', 'email', 'phone', 'country'], 'string', 'max' => 255],
            [['password'], 'string', 'min' => 6],
            // range
            [['language'], 'in', 'range' => Language::getKeys()],
            // unique
            [['login'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'login'],
            [['email'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email'],
            [['phone'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'phone'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return array|$this|ApiUser
     */
    public function signup()
    {
        if (!$this->validate()) {
            return $this;
        }

        $user = new ApiUser();
        $user->login = $this->login;
        $user->setPassword($this->password);
        $user->email = $this->email;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->middle_name = $this->middle_name;
        $user->phone = $this->phone;
        $user->country = $this->country;
        $user->language = $this->language;
        $user->generateAuthKey();
        $user->generateApiKey();

        if (!$user->save()) {
            $this->addError('login', 'Unknown error');
        }

        return $user;
    }

    /**
     * Sends confirmation email to user
     *
     * @param User $user user model to with email should be send
     *
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
