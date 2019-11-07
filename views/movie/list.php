<?php

/** @var \yii\web\View $this */
/** @var Movie[] $movies */
/** @var string $categoryLabel */
/** @var \yii\data\Pagination $pages */

use app\models\Movie;
use yii\bootstrap4\LinkPager;
use yii\helpers\Url;

$this->title = Yii::$app->name . ' - ' . $categoryLabel;
$this->params['breadcrumbs'][] = $categoryLabel;

?>

<div class="movie-list">
    <?php foreach ($movies as $movie) {
        echo $this->render('_movie', [
            'model' => $movie,
        ]);
    } ?>

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
