<?php

use yii\db\Migration;

/**
 * Class m200618_172405_create_categories
 */
class m200618_172405_create_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'board_id' => $this->integer()->notNull(),
            'description' => $this->string(),
            'color' => $this->char(6)->comment('color HEX-code'),
            'icon' => $this->string()->comment('fas fa-video'),
            'sort' => $this->integer()->notNull()->defaultValue(1)->comment('Sorting order'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            // indexes
            'KEY `board_id_sort_index` (`board_id`, `sort`)',
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%categories}}');
    }
}
