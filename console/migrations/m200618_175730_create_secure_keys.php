<?php

use yii\db\Migration;

/**
 * Class m200618_175730_create_secure_keys
 */
class m200618_175730_create_secure_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%secure_keys}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'type' => $this->boolean()->notNull()->comment('1-activation, 2-change email, 3-reset password'),
            'code' => $this->char(32)->notNull()->comment('Random hash'),
            'status' => "ENUM('new','used','forgotten') NOT NULL DEFAULT 'new'",
            'valid_to' => $this->dateTime()->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            // indexes
            'KEY `user_id_index` (`user_id`)',
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%secure_keys}}');
    }
}
