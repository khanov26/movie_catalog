<?php

namespace app\modules\admin\models;

use app\models\Actor;
use app\models\Country;
use app\models\Genre;
use app\models\Movie;
use app\models\Producer;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use yii\web\UploadedFile;

abstract class MovieForm extends Model
{
    /** @var string */
    public $name;

    /** @var string */
    public $originalName;

    /** @var int */
    public $year;

    /** @var string */
    public $producer;

    /** @var string */
    public $duration;

    /** @var int */
    public $ageRating;

    /** @var string */
    public $plot;

    /** @var UploadedFile */
    public $poster;

    /** @var float */
    public $imdbRating;

    /** @var array */
    public $actors;

    /** @var string */
    public $countries;

    /** @var string */
    public $genres;

    /** @var int */
    public $category;


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название (рус)',
            'originalName' => 'Название (оригинал)',
            'year' => 'Год выпуска',
            'producer' => 'Режиссер',
            'duration' => 'Продолжительность',
            'ageRating' => 'Возраст',
            'plot' => 'Сюжет',
            'poster' => 'Постер',
            'imdbRating' => 'Рейтинг Imdb',
            'genres' => 'Жанр',
            'countries' => 'Страна',
            'category' => 'Категория',
        ];
    }

    public function rules()
    {
        return [
            [['name', 'originalName', 'year', 'producer', 'duration', 'ageRating', 'plot', 'imdbRating',
                'actors', 'countries', 'genres', 'category'], 'trim'],

            [['name', 'originalName', 'year', 'producer', 'duration', 'ageRating', 'plot', 'imdbRating',
                'actors', 'countries', 'genres', 'category'], 'required', 'message' => 'Необходимо заполнить поле'],

            [['name', 'originalName'], 'string', 'max' => 200],

            ['year', 'date', 'format' => 'yyyy'],

            ['producer', 'string', 'max' => 100],

            ['duration', 'match', 'pattern' => '/\d{2}:\d{2}:\d{2}/', 'message' => 'Данные должны быть формата чч:мм:сс'],

            ['poster', 'file', 'extensions' => ['jpg', 'jpeg', 'png'], 'message' => 'Файл должен быть формата jpg или png'],

            ['category', 'in', 'range' => [Movie::CATEGORY_MOVIE, Movie::CATEGORY_SERIAL, Movie::CATEGORY_CARTOON]],

            ['actors', 'each', 'rule' => ['trim']],
        ];
    }

    public function save(): bool
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $producer = $this->saveProducer();
                $genres = $this->saveGenres();
                $countries = $this->saveCountries();
                $actors = $this->saveActors();

                $movie = new Movie();
                $movie->name = $this->name;
                $movie->original_name = $this->originalName;
                $movie->year = $this->year;
                $movie->setDuration($this->duration);
                $movie->age_rating = $this->ageRating;
                $movie->plot = $this->plot;
                $movie->imdb_rating = $this->imdbRating;
                $movie->category = $this->category;

                Image::resize($this->poster->tempName, 380, null)->save();
                $poster = Yii::$app->storage->saveFile($this->poster, false);
                $movie->poster = $poster;
                Image::resize($this->poster->tempName, 160, null)->save();
                $posterSmall = Yii::$app->storage->saveFile($this->poster);
                $movie->poster_small = $posterSmall;

                $this->linkModels($movie, 'producer', $producer);
                $this->linkModels($movie, 'genres', $genres);
                $this->linkModels($movie, 'countries', $countries);
                $this->linkModels($movie, 'actors', $actors);

                $transaction->commit();
                Yii::info("New movie '{$this->name}' added", __METHOD__);
                return true;
            } catch (\Throwable $e) {
                $transaction->rollBack();

                if (isset($poster, $posterSmall)) {
                    // delete new uploaded poster files
                    Yii::$app->storage->deleteFile($poster);
                    Yii::$app->storage->deleteFile($posterSmall);
                }

                Yii::error($e->getMessage(), __METHOD__);
                return false;
            }
        }
        Yii::error($this->getErrors(), __METHOD__);
        return false;
    }

    protected function saveProducer()
    {
        $producer = Producer::findOne(['name' => $this->producer]);

        if ($producer === null) {
            $producer = new Producer(['name' => $this->producer]);
            $producer->save();
        }

        return $producer;
    }

    protected function saveGenres()
    {
        $genresNames = preg_split('/,\s*/', $this->genres, null, PREG_SPLIT_NO_EMPTY);

        return array_map(static function($genreName) {
            $genre = Genre::findOne(['name' => $genreName]);
            if ($genre === null) {
                $genre = new Genre(['name' => $genreName]);
                $genre->save();
            }
            return $genre;
        }, $genresNames);
    }

    protected function saveCountries()
    {
        $countriesNames = preg_split('/,\s*/', $this->countries, null, PREG_SPLIT_NO_EMPTY);

        return array_map(static function($countryName) {
            $country = Country::findOne(['name' => $countryName]);
            if ($country === null) {
                $country = new Country(['name' => $countryName]);
                $country->save();
            }
            return $country;
        }, $countriesNames);
    }

    protected function saveActors()
    {
        $newActors = [];
        foreach ($this->actors as $actorName) {
            if (!empty($actorName)) {
                $actor = Actor::findOne(['name' => $actorName]);
                if ($actor === null) {
                    $actor = new Actor(['name' => $actorName]);
                    $actor->save();
                }
                $newActors[] = $actor;
            }
        }

        return $newActors;
    }

    protected function linkModels(ActiveRecord $mainModel, string $linkName, $modelToLink)
    {
        if (is_array($modelToLink)) {
            array_walk($modelToLink, static function ($model) use ($mainModel, $linkName) {
                $mainModel->link($linkName, $model);
            });
        } else {
            $mainModel->link($linkName, $modelToLink);
        }
    }
}
