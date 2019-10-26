<?php

namespace app\modules\admin\models;

use app\models\Movie;
use yii\data\ActiveDataProvider;

class SearchMovie extends Movie
{
    /** @var string */
    public $producerName;
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            [['name', 'original_name'], 'string', 'max' => 200],
            ['producerName', 'string', 'max' => 100],
            ['year', 'date', 'format' => 'yyyy'],
            ['category', 'in', 'range' => array_keys(Movie::CATEGORY_LABELS)],
        ];
    }

    public function search($params)
    {
        $query = Movie::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('producer');
        $dataProvider->sort->attributes['producerName'] = [
            'asc' => ['producer.name' => SORT_ASC],
            'desc' => ['producer.name' => SORT_DESC],
        ];

        if ($this->load($params) && $this->validate()) {
            $query->andFilterWhere(['movie.id' => $this->id]);
            $query->andFilterWhere(['like', 'movie.name', $this->name]);
            $query->andFilterWhere(['like', 'movie.original_name', $this->original_name]);
            $query->andFilterWhere(['like', 'producer.name', $this->producerName]);
            $query->andFilterWhere(['movie.year' => $this->year]);
            $query->andFilterWhere(['movie.category' => $this->category]);
        }

        return $dataProvider;
    }
}
