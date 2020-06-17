<?php

namespace api\modules\v1\forms;

use common\enums\Language;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class UserForm extends Model
{
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
            [['phone', 'country', 'language', 'first_name'], 'required'],
            // string
            [['first_name', 'last_name', 'middle_name', 'phone', 'country'], 'string', 'max' => 255],
            // range
            [['language'], 'in', 'range' => Language::getKeys()],

        ];
    }

    /**
     * Signs user up.
     *
     * @return bool
     */
    public function edit(User $user)
    {
        if (!$this->validate()) {
            return false;
        }
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->middle_name = $this->middle_name;
        $user->phone = $this->phone;
        $user->country = $this->country;
        $user->language = $this->language;


        if (!$user->save()) {
            $this->addError('first_name', 'Unknown error');
            return false;
        }

        return true;
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
