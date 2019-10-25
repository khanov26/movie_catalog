<?php

use app\models\Movie;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Movie */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Movies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="movie-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Уверены, что хотите удалить этот фильм?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'original_name',
            [
                'label' => 'Жанр',
                'value' => static function($model, $widjet) {
                    /** @var Movie $model */
                    return implode(', ', array_column($model->genres, 'name'));
                },
            ],
            'year',
            [
                'label' => 'Режиссер',
                'attribute' => 'producer.name',
            ],
            [
                'label' => 'Продолжительность',
                'value' => static function ($model, $widget) {
                    /** @var Movie $model */
                    return $model->getFormattedDuration();
                }
            ],
            'age_rating',
            'plot:ntext',
            [
                'attribute' => 'poster_small',
                'format' => 'raw',
                'label' => 'Постер',
                'value' => function ($model, $widjet) {
                    /** @var Movie $model */
                    return Html::img($model->getPoster(Movie::POSTER_SIZE_SMALL), ['class' => 'img-fluid']);
                }
            ],
            'imdb_rating',
            [
                'label' => 'Категория',
                'value' => static function($model, $widjet) {
                    return Movie::CATEGORY_LABELS[$model->category]['single'];
                },
            ],
        ],
    ]) ?>

</div>
