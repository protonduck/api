<?php

namespace common\models;

use api\components\behaviors\TimestampBehavior;
use common\enums\DomainSslStatus;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "domains".
 *
 * @property int $id
 * @property string $name         Domain name
 * @property int|null $ssl_status SSL-sertificate status: no ssl, trusted, expired etc.
 * @property string|null $checked_at
 * @property string $created_at
 * @property string $updated_at
 *
 * relations
 * @property Link[] $links
 *
 * getters
 * @property-read string $fName
 */
class Domain extends \yii\db\ActiveRecord
{
    const SCENARIO_ADMIN = 'admin';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%domains}}';
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return [
            // by default allowed to edit only this fields
            static::SCENARIO_DEFAULT => ['name'],
            static::SCENARIO_ADMIN => ['name', 'ssl_status'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // required
            [['name'], 'required'],
            // string max
            [['name'], 'string', 'max' => 255],
            // unique
            [['name'], 'unique'],
            // range
            [['ssl_status'], 'in', 'range' => DomainSslStatus::getKeys()],
        ];
    }

    /**
     * Links relation
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasMany(Link::class, ['domain_id' => 'id'])->inverseOf('domain');
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Domain name',
            'ssl_status' => 'SSL',
            'checked_at' => 'Checked At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
     * @return \common\models\query\DomainQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\DomainQuery(get_called_class());
    }

    /**
     * Get domain id by name, create new record if name does not exist
     *
     * @param string $url URL address
     *
     * @return int|null
     */
    public static function getIdByUrl(string $url): ?int
    {
        $url = trim($url);
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) {
            return null;
        }

        $id = static::find()->select(['id'])->where(['name' => $host])->limit(1)->scalar();
        if ($id === false) {
            $model = new static();
            $model->name = $host;
            if (!$model->save()) {
                return null;
            }
            $id = $model->id;
        }

        return (int)$id;
    }
}
