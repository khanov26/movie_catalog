<?php

/** @var \yii\web\View $this */
/** @var array $moviesArray */

use app\models\Movie;
use yii\helpers\Url;

?>

<?php foreach ($moviesArray as $category => $movies): ?>
    <div class="movies-block mb-5">
        <h3 class="title"><?= Movie::CATEGORY_LABELS[$category]['plural'] ?></h3>
        <div class="movies row flex-nowrap overflow-auto">
            <?php /** @var Movie $movie */
            foreach ($movies as $movie): ?>
                <div class="movie col-2">
                    <a href="<?= Url::to(['/movie/view', 'id' => $movie->id]) ?>">
                        <img src="<?= $movie->getPoster(Movie::POSTER_SIZE_SMALL) ?>" alt="" class="img-fluid">
                    </a>
                    <div class="movie-name small"><?= $movie->name ?></div>
                    <small class="text-muted"><?= $movie->year ?>, <?= $movie->countries[0]->name ?></small>
                </div> <!-- /.movie -->
            <?php endforeach; ?>

        </div><!-- /.movies -->
    </div><!-- /.movies-block -->
<?php endforeach; ?>
