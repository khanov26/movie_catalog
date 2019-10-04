<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "movie".
 *
 * @property int $id
 * @property string $name
 * @property string $original_name
 * @property int $year
 * @property int $producer_id
 * @property int $duration
 * @property int $age_rating
 * @property string $plot
 * @property string $poster
 * @property string $poster_small
 * @property double $imdb_rating
 *
 * @property Producer $producer
 * @property Actor[] $actors
 * @property Country[] $countries
 * @property Genre[] $genres
 */
class Movie extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'movie';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year', 'producer_id', 'duration', 'age_rating'], 'integer'],
            [['plot'], 'string'],
            [['imdb_rating'], 'number'],
            [['name', 'original_name'], 'string', 'max' => 200],
            [['poster', 'poster_small'], 'string', 'max' => 255],
            [['producer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Producer::className(), 'targetAttribute' => ['producer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'original_name' => 'Original Name',
            'year' => 'Year',
            'producer_id' => 'Producer ID',
            'duration' => 'Duration',
            'age_rating' => 'Age Rating',
            'plot' => 'Plot',
            'poster' => 'Poster',
            'poster_small' => 'Poster Small',
            'imdb_rating' => 'Imdb Rating',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducer()
    {
        return $this->hasOne(Producer::className(), ['id' => 'producer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActors()
    {
        return $this->hasMany(Actor::className(), ['id' => 'actor_id'])->viaTable('movie_actor', ['movie_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(Country::className(), ['id' => 'country_id'])->viaTable('movie_country', ['movie_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGenres()
    {
        return $this->hasMany(Genre::className(), ['id' => 'genre_id'])->viaTable('movie_genre', ['movie_id' => 'id']);
    }
}
