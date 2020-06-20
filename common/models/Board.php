<?php

namespace common\models;

use common\components\behaviors\TimestampBehavior;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "boards".
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property string|null $image
 * @property int $visibility public or private board
 * @property int $sort       Sorting order
 * @property string $created_at
 * @property string $updated_at
 *
 * relations
 * @property Category[] $categories
 * @property User $user
 *
 * getters
 * @property-read string $fName
 */
class Board extends \yii\db\ActiveRecord
{
    const SCENARIO_ADMIN = 'admin';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%boards}}';
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $default = ['name', 'image', 'visibility'];

        return [
            // by default allowed to edit only this fields
            static::SCENARIO_DEFAULT => $default,
            static::SCENARIO_ADMIN => array_merge($default, ['user_id']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // required
            [['name', 'user_id'], 'required'],
            // integer
            [['user_id', 'sort'], 'integer'],
            // boolean
            [['visibility'], 'boolean'],
            // string max
            [['name', 'image'], 'string', 'max' => 255],
            // exist
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * Categories relation
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['board_id' => 'id'])->inverseOf('board');
    }

    /**
     * User relation
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            'sortBehavior' => [
                'class' => \demi\sort\SortBehavior::class,
                'sortConfig' => [
                    'sortAttribute' => 'sort',
                    'condition' => static function (\yii\db\Query $query, self $model) {
                        $query->andWhere(['user_id' => $model->user_id]);
                    },
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        // Remove related categories
        foreach ($this->categories as $category) {
            $category->delete();
        }

        parent::afterDelete();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'user_id' => 'User ID',
            'image' => 'Image',
            'visibility' => 'Is Public',
            'sort' => 'Sorting order',
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
     * @return \common\models\query\BoardQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\BoardQuery(get_called_class());
    }
}
