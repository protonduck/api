<?php

namespace backend\components\grid;

use Yii;
use yii\helpers\Html;
use yii\grid\ActionColumn;

class BigActionColumn extends ActionColumn
{
    /**
     * @var string the template used for composing each cell in the action column.
     * Tokens enclosed within curly brackets are treated as controller action IDs (also called *button names*
     * in the context of action column). They will be replaced by the corresponding button rendering callbacks
     * specified in [[buttons]]. For example, the token `{view}` will be replaced by the result of
     * the callback `buttons['view']`. If a callback cannot be found, the token will be replaced with an empty string.
     *
     * As an example, to only have the view, and update button you can add the ActionColumn to your GridView columns as follows:
     *
     * ```
     * ['class' => 'demi\helpers\grid\BigActionColumn', 'template' => '<div class="btn-group" role="group">{view}</div>'],
     * ```
     *
     * @see buttons
     */
    public $template = '<div class="btn-group" role="group">{view} {update} {delete}</div>';
    /**
     * @inheritdoc
     */
    public $headerOptions = ['class' => 'action-column', 'style' => 'min-width: 135px;'];
    /**
     * @inheritdoc
     */
    public $contentOptions = ['class' => 'big-actions-cell'];
    /**
     * Css-класс для размера кнопок
     *
     * @var string
     */
    public $buttonSize = 'btn-sm';

    /**
     * @inheritdoc
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, [
                    'title' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                    'class' => "btn $this->buttonSize btn-info",
                ]);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                return Html::a('<i class="glyphicon glyphicon-pencil"></i>', $url, [
                    'title' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                    'class' => "btn $this->buttonSize btn-warning",
                ]);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                return Html::a('<i class="glyphicon glyphicon-trash"></i>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                    'class' => "btn $this->buttonSize btn-danger",
                ]);
            };
        }

        parent::initDefaultButtons();
    }
}
