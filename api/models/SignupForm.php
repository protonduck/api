<?php

namespace api\models;

use api\modules\v1\models\ApiUser;
use common\enums\Language;
use common\enums\UserStatus;
use common\models\SecureKey;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $name;
    public $email;
    public $password;
    public $language;

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
            [['email'], 'email', 'message'=>'email_invalid'],
            // range
            [['language'], 'in', 'range' => Language::getKeys()],
            // unique
            [['email'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email', 'message'=>'email_not_unique'],
            // default
            [['language'], 'default', 'value' => Language::getDefault()],
        ];
    }

    /**
     * Signs user up.
     *
     * @return $this|ApiUser
     */
    public function signup()
    {
        if (!$this->validate()) {
            return $this;
        }

        $user = new ApiUser();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->language = $this->language;
        $user->status = UserStatus::getDefault();

        $user->generateAuthKey();
        $user->generateApiKey();

        if (!$user->save()) {
            $this->addErrors($user->getErrors());
            if (!$this->errors) {
                $this->addError('email', 'Unknown error');
            }
        }

        $this->sendEmail($user);

        return $user;
    }

    /**
     * Sends welcome or confirmation email to user
     *
     * @param User $user user model to with email should be send
     *
     * @return bool whether the email was sent
     */
    protected function sendEmail($user): bool
    {
        if ($user->status == UserStatus::PENDING) {
            return SecureKey::create(SecureKey::TYPE_ACTIVATE, $user);
        }

        // Send welcome email
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'userWelcome-html', 'text' => 'userWelcome-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Welcome to ' . Yii::$app->name)
            ->send();
    }
}
