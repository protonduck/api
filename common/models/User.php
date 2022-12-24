<?php

namespace common\models;

use common\enums\Language;
use common\helpers\FilterHelper;
use Yii;
use yii\helpers\Html;
use yii\web\IdentityInterface;
use api\components\behaviors\TimestampBehavior;
use common\enums\UserRole;
use common\enums\UserStatus;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $new_email
 * @property string $password_hash
 * @property string|null $premium_until
 * @property string $language
 * @property integer $status
 * @property string $role
 * @property string $auth_key
 * @property string $api_key
 * @property string $created_at
 * @property string $updated_at
 *
 * relations
 * @property Board[] $boards
 *
 * getters
 * @property-read string $fName     HTML-encoded name
 *
 * setters
 * @property-write string $password write-only password
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const SCENARIO_ADMIN = 'admin';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $default = ['name', 'language'];

        return [
            static::SCENARIO_DEFAULT => $default,
            static::SCENARIO_ADMIN => array_merge($default, ['email', 'premium_until', 'status', 'role']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // filter
            [['name', 'email'], 'filter', 'filter' => [FilterHelper::class, 'trim']],
            // required
            [['name', 'email'], 'required'],
            // string max
            [['name', 'email', 'new_email'], 'string', 'max' => 255],
            // string length
            [['auth_key', 'api_key'], 'string', 'length' => 32],
            // email
            [['email', 'new_email'], 'email'],
            // uniqeue
            [['email'], 'unique'],
            [['auth_key'], 'unique'],
            [['api_key'], 'unique'],
            // range
            [['status'], 'in', 'range' => UserStatus::getKeys()],
            [['role'], 'in', 'range' => UserRole::getKeys()],
            [['language_id'], 'in', 'range' => Language::getKeys()],
            // default
            [['language'], 'default', 'value' => Language::getDefault()],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::find()->where(['id' => $id])->active()->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->where(['api_key' => $token])->active()->one();
    }

    /**
     * Finds user by email
     *
     * @param string $email
     *
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::find()->where(['email' => $email])->active()->one();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates API authentication key
     */
    public function generateApiKey()
    {
        $this->api_key = Yii::$app->security->generateRandomString();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'new_email' => 'New Email',
            'password_hash' => 'Password Hash',
            'premium_until' => 'Premium Until',
            'language' => 'Language',
            'status' => 'Status',
            'role' => 'Role',
            'auth_key' => 'Auth Key',
            'api_key' => 'API Key',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Boards relation
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBoards()
    {
        return $this->hasMany(Board::class, ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * Return HTML-encoded name
     *
     * @return string
     */
    public function getFName()
    {
        return Html::encode($this->name);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\UserQuery(get_called_class());
    }
}
