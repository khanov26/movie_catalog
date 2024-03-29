<?php

namespace app\models;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\ModelEvent;
use yii\db\ActiveRecord;
use yii\db\Exception;

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
 * @property string $poster_extra_small
 * @property double $imdb_rating
 * @property int $category
 *
 * @property Producer $producer
 * @property Actor[] $actors
 * @property Country[] $countries
 * @property Genre[] $genres
 */
class Movie extends ActiveRecord
{
    const CATEGORY_MOVIE = 1;
    const CATEGORY_SERIAL = 2;
    const CATEGORY_CARTOON = 3;

    const CATEGORY_LABELS = [
        self::CATEGORY_MOVIE => ['single' => 'Фильм', 'plural' => 'Фильмы'],
        self::CATEGORY_SERIAL => ['single' => 'Сериал', 'plural' => 'Сериалы'],
        self::CATEGORY_CARTOON => ['single' => 'Мультфильм', 'plural' => 'Мультфильмы'],
    ];

    const POSTER_SIZE_MEDIUM = 1;
    const POSTER_SIZE_SMALL = 2;
    const POSTER_SIZE_EXTRA_SMALL = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'movie';
    }

    public function init()
    {
        parent::init();

        $this->on(self::EVENT_BEFORE_INSERT, [$this, 'isDuplicate']);
    }

    public function isDuplicate(ModelEvent $event)
    {
        if (self::find()
            ->where(['name' => $this->name, 'original_name' => $this->original_name, 'year' => $this->year])
            ->exists()) {
            $event->isValid = false;
            Yii::error("Movie '{$this->name}' already exists", __METHOD__);
            throw new Exception('Trying to create a duplicate');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название (рус)',
            'original_name' => 'Название (оригинал)',
            'year' => 'Год выпуска',
            'producer_id' => 'Режиссер',
            'duration' => 'Продолжительность',
            'age_rating' => 'Возрастной рейтинг',
            'plot' => 'Сюжет',
            'poster' => 'Постер',
            'imdb_rating' => 'Рейтинг Imdb',
            'category' => 'Категория',
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

    public function getPoster(int $posterSize = self::POSTER_SIZE_MEDIUM): string
    {
        switch ($posterSize) {
            case self::POSTER_SIZE_SMALL:
                $posterUri = $this->poster_small;
                break;
            case self::POSTER_SIZE_EXTRA_SMALL:
                $posterUri = $this->poster_extra_small;
                break;
            default:
                $posterUri = $this->poster;
        }

        return Yii::$app->storage->getFileUri($posterUri);
    }

    public function getFormattedDuration(): string
    {
        $hours = floor($this->duration / 3600);
        $minutes = floor($this->duration / 60 % 60);
        $seconds = floor($this->duration % 60);

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    public function setDuration(string $formattedDuration)
    {
        $n = sscanf($formattedDuration, '%02d:%02d:%02d', $hours, $minutes, $seconds);
        if ($n !== 3) {
            throw new InvalidArgumentException('Incorrect duration format');
        }

        $this->duration = $hours * 3600 + $minutes * 60 + $seconds;
    }
}
