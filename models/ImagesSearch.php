<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Images;

/**
 * ImagesSearch represents the model behind the search form of `app\models\Images`.
 */
class ImagesSearch extends Images
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['album_id', 'image_id', 'image_delete_hash', 'image_url', 'timestamp'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Images::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'timestamp' => $this->timestamp,
        ]);

        $query->andFilterWhere(['like', 'album_id', $this->album_id])
            ->andFilterWhere(['like', 'image_id', $this->image_id])
            ->andFilterWhere(['like', 'image_delete_hash', $this->image_delete_hash])
            ->andFilterWhere(['like', 'image_url', $this->image_url]);

        return $dataProvider;
    }
}
