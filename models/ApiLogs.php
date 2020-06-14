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
class ApiLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'api_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_url', 'request_body', 'response'], 'required'],
            [['timestamp'], 'safe'],
        ];
    }
}
