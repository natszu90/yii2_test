<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%images}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%albums}}`
 */
class m200613_202924_create_images_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%images}}', [
            'id' => $this->primaryKey(),
            'album_id' => $this->text()->notNull(),
            'image' => $this->text()->notNull(),
            'image_delete_hash' => $this->text()->notNull(),
            'image_url' => $this->text()->notNull(),
            'timestamp' => $this->timestamp(),
        ]);

        // creates index for column `album_id`
        $this->createIndex(
            '{{%idx-images-album_id}}',
            '{{%images}}',
            'album_id'
        );

        // add foreign key for table `{{%albums}}`
        $this->addForeignKey(
            '{{%fk-images-album_id}}',
            '{{%images}}',
            'album_id',
            '{{%albums}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%albums}}`
        $this->dropForeignKey(
            '{{%fk-images-album_id}}',
            '{{%images}}'
        );

        // drops index for column `album_id`
        $this->dropIndex(
            '{{%idx-images-album_id}}',
            '{{%images}}'
        );

        $this->dropTable('{{%images}}');
    }
}
