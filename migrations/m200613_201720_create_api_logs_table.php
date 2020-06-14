<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%api_logs}}`.
 */
class m200613_201720_create_api_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%api_logs}}', [
            'id' => $this->primaryKey(),
            'request_url' => $this->text(),
            'request_body' => $this->text(),
            'response' => $this->text(),
            'timestamp' => $this->timestamp(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%api_logs}}');
    }
}
