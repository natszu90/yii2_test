<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "albums".
 *
 * @property int $id
 * @property string $album_id
 * @property string|null $title
 * @property string|null $description
 * @property string $timestamp
 */
class Albums extends \yii\db\ActiveRecord
{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'albums';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['album_id'], 'required'],
            [['album_id', 'title', 'description', 'album_url'], 'string'],
            [['timestamp'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'album_id' => 'Album ID',
            'title' => 'Title',
            'description' => 'Description',
            'album_url' => 'Album Url',
            'timestamp' => 'Timestamp',
        ];
    }

    public function getAllRecord()
    {

    }
}
