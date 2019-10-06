<?php

/** @var \yii\web\View $this */
/** @var \app\models\Movie $movie */

use app\models\Movie;use yii\helpers\Html;

$this->title = Yii::$app->name . ' - ' . $movie->name;
$this->params['breadcrumbs'][] = ['label' => 'Фильмы', 'url' => ['list', 'category' => Movie::CATEGORY_MOVIE]];
$this->params['breadcrumbs'][] = $movie->name;

?>

<div class="movie row">
    <div class="movie-poster col-md-4">
        <img class="img-fluid" src="<?= $movie->getPoster() ?>" alt="">
    </div>
    <div class="movie-about col-md-8">
        <h4 class="movie-name"><?= $movie->name ?></h4>
        <h6 class="text-muted"><?= $movie->original_name ?></h6>
        <p class="text-muted"><?= $movie->year ?>, <?= $movie->countries[0]->name ?>, <?= $movie->genres[0]->name ?></p>

        <ul class="nav nav-tabs mb-3" id="movie-about-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="movie-description-tab" data-toggle="tab" href="#movie-description" role="tab" aria-controls="movie-description" aria-selected="true">Описание фильма</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="actors-tab" data-toggle="tab" href="#actors" role="tab" aria-controls="actors" aria-selected="false">Актерский состав</a>
            </li>
        </ul>
        <div class="tab-content" id="movie-about-tab-content">
            <div class="tab-pane fade show active" id="movie-description" role="tabpanel" aria-labelledby="home-tab">
                <dl class="row">
                    <dt class="col-sm-4">Возраст</dt>
                    <dd class="col-sm-8"><?= $movie->age_rating ?>+</dd>

                    <dt class="col-sm-4">Жанр</dt>
                    <dd class="col-sm-8">
                        <?php
                        $genreLinks = array_map(static function ($genre) {
                            return Html::a($genre->name, '#');
                        }, $movie->genres);
                        echo implode(', ', $genreLinks);
                        ?>
                    </dd>

                    <dt class="col-sm-4">Режиссер</dt>
                    <dd class="col-sm-8"><?= Html::a($movie->producer->name, '#') ?></dd>

                    <dt class="col-sm-4 text-truncate">Страна</dt>
                    <dd class="col-sm-8">
                        <?php
                        $countryLinks = array_map(static function ($country) {
                            return Html::a($country->name, '#');
                        }, $movie->countries);
                        echo implode(', ', $countryLinks);
                        ?>
                    </dd>

                    <dt class="col-sm-4 text-truncate">Год выпуска</dt>
                    <dd class="col-sm-8"><?= $movie->year ?></dd>

                    <dt class="col-sm-4">Продолжительность</dt>
                    <dd class="col-sm-8"><?= $movie->getFormattedDuration() ?></dd>
                </dl>

                <h5 class="plot mt-3">Сюжет</h5>
                <p class="plot-content"><?= $movie->plot ?></p>
                <h5 class="ratings">Рейтинги</h5>
                <p class="ratings-content">
                    IMDb: <a href="#" class="muted-text"><?= $movie->imdb_rating ?></a>
                </p>
            </div>
            <div class="tab-pane fade" id="actors" role="tabpanel" aria-labelledby="profile-tab">
                <?php
                $actors = array_map(static function ($actor) {
                    return Html::a($actor->name, '#', ['class' => 'clearfix']);
                }, $movie->actors);
                echo implode('', $actors);
                ?>
            </div>
        </div>
    </div>
</div>
