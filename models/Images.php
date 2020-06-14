<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "images".
 *
 * @property int $id
 * @property string $album_id
 * @property string $image
 * @property string $image_delete_hash
 * @property string $image_url
 * @property string $timestamp
 */
class Images extends \yii\db\ActiveRecord
{
    public $image;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['image_id'], 'file', 'extensions' => 'jpg, png'],
            [['album_id', 'image_delete_hash', 'image_url'], 'required'],
            [['album_id', 'image_delete_hash', 'image_url'], 'string'],
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
            'image_id' => 'Image ID',
            'image_delete_hash' => 'Image Delete Hash',
            'image_url' => 'Image Url',
            'timestamp' => 'Timestamp',
        ];
    }

    public function upload() {
         if ($this->validate()) {
            $this->image->saveAs('../uploads/' . $this->image->baseName . '.' .
               $this->image->extension);
            return true;
         } else {
            return false;
         }
      }
}
