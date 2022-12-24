<?php

use yii\db\Migration;

/**
 * Class m200618_173509_create_links
 */
class m200618_173509_create_links extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%links}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string(5000)->notNull(),
            'category_id' => $this->integer()->notNull(),
            'title' => $this->string(),
            'description' => $this->string(1000),
            'is_favorite' => $this->boolean()->notNull()->defaultValue(0),
            'favicon' => $this->string(),
            'target' => $this->boolean()->notNull()->defaultValue(0)->comment('1 - target blank, 0 - normal'),
            'hits' => $this->integer()->notNull()->defaultValue(0)->comment('Clicks counter'),
            'http_status_code' => $this->smallInteger(),
            'sort' => $this->integer()->notNull()->defaultValue(1)->comment('Sorting order'),
            'checked_at' => $this->dateTime()->comment('Last checkng time'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            // indexes
            'KEY `category_id_sort_index` (`category_id`, `sort`)',
            'KEY `checked_at_index` (`checked_at`)',
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%links}}');
    }
}
