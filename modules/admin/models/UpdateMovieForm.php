<?php

namespace app\modules\admin\models;

use app\models\Movie;
use Yii;
use yii\db\ActiveRecord;
use yii\imagine\Image;

class UpdateMovieForm extends MovieForm
{
    /** @var Movie */
    public $movie;

    public function loadData()
    {
        $this->name = $this->movie->name;
        $this->originalName = $this->movie->original_name;
        $this->year = $this->movie->year;
        $this->duration = $this->movie->getFormattedDuration();
        $this->ageRating = $this->movie->age_rating;
        $this->plot = $this->movie->plot;
        $this->imdbRating = $this->movie->imdb_rating;
        $this->category = $this->movie->category;
        $this->producer = $this->movie->producer->name;
        $this->genres = implode(', ', array_column($this->movie->genres, 'name'));
        $this->countries = implode(', ', array_column($this->movie->countries, 'name'));
        $this->actors = array_column($this->movie->actors, 'name');
    }

    public function save(): bool
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $newProducer = $this->saveProducer();
                $newGenres = $this->saveGenres();
                $newCountries = $this->saveCountries();
                $newActors = $this->saveActors();

                $this->movie->name = $this->name;
                $this->movie->original_name = $this->originalName;
                $this->movie->year = $this->year;
                $this->movie->setDuration($this->duration);
                $this->movie->age_rating = $this->ageRating;
                $this->movie->plot = $this->plot;
                $this->movie->imdb_rating = $this->imdbRating;
                $this->movie->category = $this->category;

                if ($this->poster !== null) {
                    $oldPoster = $this->movie->poster;
                    $oldPosterSmall = $this->movie->poster_small;
                    $oldPosterExtraSmall = $this->movie->poster_extra_small;

                    Image::resize($this->poster->tempName, 380, null)->save();
                    $newPoster = Yii::$app->storage->saveFile($this->poster->tempName, $this->poster->extension, false);
                    $this->movie->poster = $newPoster;
                    Image::resize($this->poster->tempName, 160, null)->save();
                    $newPosterSmall = Yii::$app->storage->saveFile($this->poster->tempName, $this->poster->extension, false);
                    $this->movie->poster_small = $newPosterSmall;
                    Image::resize($this->poster->tempName, null, 45)->save();
                    $newPosterExtraSmall = Yii::$app->storage->saveFile($this->poster->tempName, $this->poster->extension);
                    $this->movie->poster_extra_small = $newPosterExtraSmall;
                }

                $this->movie->save();

                //update producer
                $oldProducer = $this->movie->producer;
                if (!$oldProducer->equals($newProducer)) {
                    $this->linkModels($this->movie, 'producer', $newProducer);
                }

                // update genres
                $oldGenres = $this->movie->genres;
                $genresToBeLinked = array_diff($newGenres, $oldGenres);
                $genresToBeUnlinked = array_diff($oldGenres, $newGenres);
                $this->linkModels($this->movie, 'genres', $genresToBeLinked);
                $this->unlinkModels($this->movie, 'genres', $genresToBeUnlinked);

                //update countries
                $oldCountries = $this->movie->countries;
                $countriesToBeLinked = array_diff($newCountries, $oldCountries);
                $countriesToBeUnlinked = array_diff($oldCountries, $newCountries);
                $this->linkModels($this->movie, 'countries', $countriesToBeLinked);
                $this->unlinkModels($this->movie, 'countries', $countriesToBeUnlinked);

                // update actors
                $oldActors = $this->movie->actors;
                $actorsToBeLinked = array_diff($newActors, $oldActors);
                $actorsToBeUnlinked = array_diff($oldActors, $newActors);
                $this->linkModels($this->movie, 'actors', $actorsToBeLinked);
                $this->unlinkModels($this->movie, 'actors', $actorsToBeUnlinked);

                if (isset($oldPoster, $oldPosterSmall, $oldPosterExtraSmall)) {
                    //delete old poster files
                    Yii::$app->storage->deleteFile($oldPoster);
                    Yii::$app->storage->deleteFile($oldPosterSmall);
                    Yii::$app->storage->deleteFile($oldPosterExtraSmall);
                }

                $transaction->commit();
                Yii::info("New movie '{$this->name}' updated", __METHOD__);
                return true;
            } catch (\Throwable $e) {
                $transaction->rollBack();

                if (isset($newPoster, $newPosterSmall, $newPosterExtraSmall)) {
                    // delete new uploaded poster files
                    Yii::$app->storage->deleteFile($newPoster);
                    Yii::$app->storage->deleteFile($newPosterSmall);
                    Yii::$app->storage->deleteFile($newPosterExtraSmall);
                }

                Yii::error($e->getMessage(), __METHOD__);
                return false;
            }
        }
        Yii::error($this->getErrors(), __METHOD__);
    }

    protected function unlinkModels(ActiveRecord $mainModel, string $linkName, $modelToLink)
    {
        if (is_array($modelToLink)) {
            array_walk($modelToLink, static function ($model) use ($mainModel, $linkName) {
                $mainModel->unlink($linkName, $model, true);
            });
        } else {
            $mainModel->unlink($linkName, $modelToLink, true);
        }
    }
}