<?php

namespace api\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\InvalidArgumentException;
use api\enums\UserStatus;

/**
 * Secure keys
 *
 * @property integer $id
 * @property string $user_id
 * @property integer $type          1-activation, 2-change email, 3-reset password
 * @property string $code           Random hash
 * @property string $status
 * @property string $valid_to
 * @property string $created_at     Created date
 * @property string $updated_at     Used date
 *
 * relations
 * @property User $user             User model
 *
 * getters
 * @property string $url
 */
class SecureKey extends ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_USED = 'used';
    const STATUS_FORGOTTEN = 'forgotten';

    const TYPE_ACTIVATE = 1;
    const TYPE_CHANGE_EMAIL = 2;
    const TYPE_RESET_PASSWORD = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%secure_keys}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();

        // $scenarios['demo'] = ['user_id', 'type', 'code', 'status', 'valid_to', 'updated_at'];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // required
            [['user_id', 'type', 'code'], 'required'],
            // integer
            [['user_id', 'type'], 'integer'],
            // default
            ['status', 'default', 'value' => static::STATUS_NEW],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'SECURE_KEY_USER_ID'),
            'type' => Yii::t('app', 'SECURE_KEY_TYPE'),
            'code' => Yii::t('app', 'SECURE_KEY_CODE'),
            'status' => Yii::t('app', 'SECURE_KEY_STATUS'),
            'valid_to' => Yii::t('app', 'SECURE_KEY_VALID_TO'),
            'created_at' => Yii::t('app', 'SECURE_KEY_CREATED_DATE'),
            'updated_at' => Yii::t('app', 'SECURE_KEY_USED_DATE'),
        ];
    }

    /**
     * Relation to the User model
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->andWhere(['users.status' => UserStatus::ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // Устанавливаем срок годности кода для нового ключа
        if ($insert && empty($this->valid_to)) {
            $this->valid_to = date('Y-m-d H:i:s', time() + Yii::$app->params['secureKey.expirationTime']);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        // что-нибудь...

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Проверяем, чтобы срок годности не истёк
     *
     * @return bool
     */
    public function isValidKey()
    {
        $validTo = strtotime($this->valid_to);

        // Срок годности кода должен быть в будущем
        return $validTo && $validTo >= time();
    }

    /**
     * Возвращает отформатированную дату срока действия данного ключа
     *
     * @return string
     */
    public function getFormattedExpireTime()
    {
        return Yii::$app->formatter->asDatetime(strtotime($this->valid_to), 'long');
    }

    /**
     * Возвращает псевдослучайный набор символов
     *
     * @return string набор из 32 "случайных" символов
     */
    public static function generateCode()
    {
        return Yii::$app->security->generateRandomString();
    }

    /**
     * Генерирует новый код активации указанного типа и отправляет его на email
     *
     * @param integer $type                тип кода
     * @param IdentityInterface|User $user моделька пользователя
     * @param string|null $new_email       новый e-mail адрес пользователя при смене оного
     *
     * @return bool
     */
    public static function create($type, IdentityInterface $user, $new_email = null)
    {
        $key = new static();

        $key->type = $type;
        $key->user_id = $user->getId();
        $key->code = static::generateCode();

        if ($key->save()) {
            // Устанавливаем всем предыдущим ключам этого пользователя и такого же типа статус "Забыт"
            static::updateAll(['status' => self::STATUS_FORGOTTEN], 'user_id=:uid AND type=:type AND code!=:code', [
                ':uid' => $key->user_id, ':type' => $key->type, ':code' => $key->code,
            ]);

            if ($type == self::TYPE_CHANGE_EMAIL) {
                // Сохраняем новый email-адрес в модель пользователя
                $user->new_email = $new_email;
                $user->save(false, ['new_email']);
            }

            // Отправляем на почту пользователя письмо с инструкциями
            return $key->sendEmail($user, $new_email);
        }

        return false;
    }

    /**
     * Отправка кода протекции на email-адрес пользователя
     *
     * @param IdentityInterface|User $user моделька пользователя
     * @param null $email                  опционально кастомный e-mail для отправки.
     *
     * @return bool
     * @throws \yii\base\InvalidArgumentException
     */
    public function sendEmail(IdentityInterface $user, $email = null)
    {
        switch ($this->type) {
            case self::TYPE_ACTIVATE:
                $view = 'userActivate-html';
                $subject = Yii::t('app', 'SECURE_KEY_SUBJECT_ACTIVATE_{sitename}', ['sitename' => Yii::$app->name]);
                break;
            case self::TYPE_CHANGE_EMAIL:
                $view = 'userChangeEmail-html';
                $subject = Yii::t('app', 'SECURE_KEY_SUBJECT_CHANGE_EMAIL_{sitename}', ['sitename' => Yii::$app->name]);
                break;
            case self::TYPE_RESET_PASSWORD:
                $view = 'userResetPassword-html';
                $subject = Yii::t('app', 'SECURE_KEY_SUBJECT_RESET_PASSWORD_{sitename}',
                    ['sitename' => Yii::$app->name]);
                break;
            default:
                throw new InvalidArgumentException('Unknown secure key type');
        }

        // На какой email адрес отправлять сообщение
        $send_email = !empty($email) ? $email : $user->email;

        return Yii::$app->mailer->compose($view, ['user' => $user, 'key' => $this])
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setTo([$send_email => $user->name])
            ->setSubject($subject)
            ->send();
    }

    /**
     * Пытается найти ключ по переданным параметрам
     *
     * @param $user_id
     * @param $code
     *
     * @return array|null|SecureKey
     */
    public static function getKey($user_id, $code)
    {
        return static::find()->where(['user_id' => $user_id, 'code' => $code])->one();
    }

    /**
     * Возвращает URL-адрес, который необходимо отправить пользователю на e-mail с инструкциями
     *
     * @return string
     */
    public function getUrl()
    {
        $route = '';
        switch ($this->type) {
            case self::TYPE_ACTIVATE:
                $route = '/dashboard/profile/activate';
                break;
            case self::TYPE_CHANGE_EMAIL:
                $route = '/dashboard/profile/change-email';
                break;
            case self::TYPE_RESET_PASSWORD:
                $route = '/dashboard/profile/reset-password';
                break;
        }

        return Yii::$app->urlManager->createAbsoluteUrl([$route, 'id' => $this->user_id, 'code' => $this->code]);
    }

    /**
     * Активация данного ключа. Действие зависит от его типа.
     * В случае возникновения ошибки будет возвращено FALSE, описание ошибки может быть найдено в $keyModel->errors.
     *
     * @return bool
     */
    public function activate()
    {
        switch ($this->type) {
            case self::TYPE_ACTIVATE:
                // New user activation
                $user = $this->user;
                $user->status = UserStatus::ACTIVE;

                if (!$user->save(false, ['status'])) {
                    $this->addError('user_id', 'Error while user saving');

                    return false;
                }

                break;
            case self::TYPE_CHANGE_EMAIL:
                // Confirmation email changing
                $user = $this->user;
                // Проверим, не изменил ли кто-нибудь ранее свой email на тот, на который мы сейчас хотим изменить
                if (User::find()->where(['email' => $user->new_email])->exists()) {
                    $this->addError('user_id', Yii::t('app/error', 'SECURE_KEY_EMAIL_ALREADY_USED'));

                    return false;
                }
                // Set new_email as primary email
                $user->email = $user->new_email;
                $user->new_email = null;

                // Сохраняем изменения
                if (!$user->save(false, ['email', 'new_email'])) {
                    $this->addError('user_id', 'Error while user saving');

                    return false;
                }

                break;
            case self::TYPE_RESET_PASSWORD:
                // Password already restored, no action needed

                break;
        }

        // Set status "used" for this secure key
        $this->status = self::STATUS_USED;

        // Save secure key changes
        return $this->save(false, ['status']);
    }
}
