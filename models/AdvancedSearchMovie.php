<?php

namespace app\models;

use app\modules\admin\models\SearchMovie;
use Yii;
use yii\data\ActiveDataProvider;

class AdvancedSearchMovie extends SearchMovie
{
    /** @var int */
    public $country;

    /** @var int */
    public $yearFrom;

    /** @var int */
    public $yearTo;

    /** @var string */
    public $actor;

    /** @var int */
    public $genre;


    /**
     * {@inheritDoc}
     */
    public function attributeLabels()
    {
        $labels = [
            'name' => 'Название',
            'producerName' => 'Режиссер',
            'country' => 'Страна',
            'yearFrom' => 'с',
            'yearTo' => 'по',
            'actor' => 'Актер',
            'genre' => 'Жанр',
        ];

        return array_merge(parent::attributeLabels(), $labels);
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        $rules = [
            [['yearFrom', 'yearTo'], 'date', 'format' => 'yyyy'],
            ['actor', 'trim'],
            ['actor', 'string', 'max' => 100],
            ['genre', 'integer'],
            ['imdb_rating', 'number', 'min' => 1, 'max' => 10],
        ];

        return array_merge(parent::rules(), $rules);
    }

    public function search($params)
    {
        $query = Movie::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['moviesPerPage'],
                'defaultPageSize' => Yii::$app->params['moviesPerPage'],
            ],
        ]);

        if ($this->load($params) && $this->validate()) {
            $query->andFilterWhere(['like', 'movie.name', $this->name]);
            $query->andFilterWhere(['>=', 'movie.year', $this->yearFrom]);
            $query->andFilterWhere(['<=', 'movie.year', $this->yearTo]);
            $query->andFilterWhere(['movie.category' => $this->category]);
            $query->andFilterWhere(['>=', 'movie.imdb_rating', $this->imdb_rating]);

            if (!empty($this->country)) {
                $query->innerJoin('movie_country mc', 'mc.movie_id = movie.id');
                $query->andWhere(['mc.country_id' => $this->country]);
            }

            if (!empty($this->genre)) {
                $query->innerJoin('movie_genre mg', 'mg.movie_id = movie.id');
                $query->andWhere(['mg.genre_id' => $this->genre]);
            }

            if (!empty($this->producerName)) {
                $query->joinWith('producer');
                $query->andWhere(['like', 'producer.name', $this->producerName]);
            }

            if (!empty($this->actor)) {
                $query->joinWith('actors',false);
                $query->andWhere(['like', 'actor.name', $this->actor]);
            }


        }

        return $dataProvider;
    }
}
