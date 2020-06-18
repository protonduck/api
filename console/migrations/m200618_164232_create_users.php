<?php

use yii\db\Migration;

class m200618_164232_create_users extends Migration
{
    public function up()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'new_email' => $this->string(),
            'password_hash' => $this->string()->notNull(),
            'premium_until' => $this->dateTime(),
            'language' => $this->char(2)->notNull()->defaultValue('en'),
            'status' => $this->tinyInteger(3)->notNull()->defaultValue(1)->comment('0 - pending, 1 - active, 3 - banned'),
            'role' => "ENUM('user', 'admin') NOT NULL DEFAULT 'user'",
            'auth_key' => $this->char(32)->notNull()->unique(),
            'api_key' => $this->char(32)->notNull()->unique(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            // indexes
            'KEY `status_index` (`status`)',
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

    public function down()
    {
        $this->dropTable('{{%users}}');
    }
}
