<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%albums}}`.
 */
class m200613_201606_create_albums_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%albums}}', [
            'id' => $this->primaryKey(),
            'album_id' => $this->text()->notNull(),
            'delete_hash' => $this->text()->notNull(),
            'title' => $this->text(),
            'description' => $this->text(),
            'album_url' => $this->text(),
            'timestamp' => $this->timestamp(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%albums}}');
    }
}
