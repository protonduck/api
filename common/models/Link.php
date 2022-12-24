<?php

namespace common\models;

use api\components\behaviors\TimestampBehavior;
use api\enums\LinkTarget;
use common\helpers\FilterHelper;
use common\helpers\UserHelper;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "links".
 *
 * @property int $id
 * @property string $url
 * @property int $category_id
 * @property int $domain_id
 * @property string|null $title
 * @property string|null $description
 * @property int $is_favorite
 * @property string|null $favicon
 * @property int $target             1 - target blank, 0 - normal
 * @property int $hits               Clicks counter
 * @property int|null $http_status_code
 * @property int $sort               Sorting order
 * @property string|null $checked_at Last checkng time
 * @property string $created_at
 * @property string $updated_at
 *
 * relations
 * @property Domain $domain
 * @property Category $category
 *
 * getters
 * @property-read string $fTitle
 * @property-read string $fDescription
 */
class Link extends \yii\db\ActiveRecord
{
    const SCENARIO_ADMIN = 'admin';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%links}}';
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $default = ['url', 'category_id', 'title', 'description', 'is_favorite', 'target', 'sort'];

        return [
            // by default allowed to edit only this fields
            static::SCENARIO_DEFAULT => $default,
            static::SCENARIO_ADMIN => array_merge($default, ['hits', 'http_status_code', 'checked_at', 'favicon']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // filter
            [['url', 'title', 'description'], 'filter', 'filter' => [FilterHelper::class, 'trim']],
            // required
            [['url', 'category_id'], 'required'],
            // integer
            [['category_id', 'domain_id', 'hits', 'http_status_code', 'sort'], 'integer'],
            // string max
            [['url'], 'string', 'max' => 5000],
            [['title', 'favicon'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1000],
            // url
            [['url'], 'url', 'defaultScheme' => 'http'],
            // range
            [['target'], 'in', 'range' => LinkTarget::getKeys()],
            // boolean
            [['is_favorite'], 'boolean'],
            // default
            [['hits'], 'default', 'value' => 0],
            // validateCategory()
            [['category_id'], 'validateCategory'],
        ];
    }

    /**
     * Check category exists and belongs to the current user
     *
     * @param string $attribute
     * @param array $params
     */
    public function validateCategory($attribute, $params = [])
    {
        if ($this->hasErrors('category_id')) {
            return;
        }

        $category = Category::findOne($this->category_id);
        if (!$category) {
            $this->addError('category_id', 'Category not found');
        } elseif (!$category->board) {
            $this->addError('category_id', 'Category no longer refers to the board');
        } elseif (Yii::$app->has('user') && $category->board->user_id !== UserHelper::getCurrentId()) {
            $this->addError('category_id', 'Category belongs to another user');
        }
    }

    /**
     * Domain relation
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDomain()
    {
        return $this->hasOne(Domain::class, ['id' => 'domain_id']);
    }

    /**
     * Category relation
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
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
                        $query->andWhere(['category_id' => $model->category_id]);
                    },
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->domain_id = 0;

        // @TODO: Currently not working
//        // Set domain_id value
//        if (!$this->domain_id) {
//            // Get exists domain_id or creates new domain
//            $this->domain_id = Domain::getIdByUrl($this->url);
//        }
        // Convert empty title string to null
        if ($this->title === '') {
            $this->title = null;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'category_id' => 'Category ID',
            'domain_id' => 'Domain ID',
            'title' => 'Title',
            'description' => 'Description',
            'is_favorite' => 'Is Favorite',
            'favicon' => 'Favicon',
            'target' => '1 - target blank, 0 - normal',
            'hits' => 'Clicks counter',
            'http_status_code' => 'Http Status Code',
            'sort' => 'Sorting order',
            'checked_at' => 'Last checkng time',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Return HTML-encoded Title
     *
     * @return string
     */
    public function getFTitle()
    {
        return Html::encode($this->title);
    }

    /**
     * Return HTML-encoded Description
     *
     * @return string
     */
    public function getFDescription()
    {
        return Html::encode($this->description);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\LinkQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\LinkQuery(get_called_class());
    }
}
