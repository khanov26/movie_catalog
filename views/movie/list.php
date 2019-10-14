<?php

/** @var \yii\web\View $this */

use app\models\Movie;
use yii\bootstrap4\LinkPager;
use yii\helpers\Url;

/** @var Movie[] $movies */
/** @var string $categoryLabel */
/** @var \yii\data\Pagination $pages */

$this->title = $categoryLabel;
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="movie-list">
    <?php foreach ($movies as $movie): ?>
        <div class="movie row mb-4">
            <a href="<?= Url::to(['/movie/view', 'id' => $movie->id]) ?>" class="movie-poster col-md-2">
                <img src="<?= $movie->getPoster(Movie::POSTER_SIZE_SMALL) ?>" alt="" class="img-fluid">
            </a>
            <div class="movie-about col-md-10 d-flex flex-column">
                <h6 class="movie-name"><?= $movie->name ?></h6>
                <small class="text-muted"><?= $movie->year ?>, <?= $movie->countries[0]->name ?>, <?= $movie->genres[0]->name ?>, <?= $movie->getFormattedDuration() ?></small>
                <p class="plot-content my-3"><?= $movie->plot ?></p>

                <div class="row mt-auto">
                    <dl class="col-md-6 mb-0">
                        <dt>Режиссер</dt>
                        <dd><?= $movie->producer->name ?></dd>
                    </dl>
                    <dl class="col-md-6 mb-0">
                        <dt>Рейтинги</dt>
                        <dd>IMDb: <span class="text-muted"><?= $movie->imdb_rating ?></span></dd>
                    </dl>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?= LinkPager::widget([
        'pagination' => $pages,
        'options' => [
            'class' => 'mt-4',
        ],
        'listOptions' => [
            'class' => 'pagination justify-content-center',
        ]
    ]) ?>
</div>
