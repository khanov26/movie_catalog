<?php

/** @var Movie $model */

use app\models\Movie;
use yii\helpers\Url; ?>

<div class="movie row mb-4">
    <a href="<?= Url::to(['/movie/view', 'id' => $model->id]) ?>" class="movie-poster col-md-2">
        <img src="<?= $model->getPoster(Movie::POSTER_SIZE_SMALL) ?>" alt="" class="img-fluid">
    </a>
    <div class="movie-about col-md-10 d-flex flex-column">
        <h6 class="movie-name"><?= $model->name ?></h6>
        <small class="text-muted"><?= $model->year ?>, <?= $model->countries[0]->name ?>,
            <?= $model->genres[0]->name ?>, <?= $model->getFormattedDuration() ?></small>
        <p class="plot-content my-3"><?= $model->plot ?></p>

        <div class="row mt-auto">
            <dl class="col-md-6 mb-0">
                <dt>Режиссер</dt>
                <dd><?= $model->producer->name ?></dd>
            </dl>
            <dl class="col-md-6 mb-0">
                <dt>Рейтинги</dt>
                <dd>IMDb: <span class="text-muted"><?= $model->imdb_rating ?></span></dd>
            </dl>
        </div>
    </div>
</div>
