<?php

use yii\db\Migration;

/**
 * Class m200618_174810_create_domains
 */
class m200618_174810_create_domains extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%domains}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique()->comment('Domain name'),
            'ssl_status' => $this->boolean()->comment('SSL-sertificate status: no ssl, trusted, expired etc.'),
            'checked_at' => $this->dateTime(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            // indexes
            'KEY `checked_at_index` (`checked_at`)',
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%domains}}');
    }
}
