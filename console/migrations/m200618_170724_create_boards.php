<?php

use yii\db\Migration;

/**
 * Class m200618_170724_create_boards
 */
class m200618_170724_create_boards extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%boards}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'image' => $this->string(),
            'visibility' => $this->tinyInteger(1)->notNull()->defaultValue(1)->comment('public or private board'),
            'sort' => $this->integer()->notNull()->defaultValue(1)->comment('Sorting order'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            // indexes
            'KEY `user_id_index` (`user_id`)',
            'KEY `sort_index` (`sort`)',
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%boards}}');
    }
}
