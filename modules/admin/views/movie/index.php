<?php

use app\models\Movie;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Фильмы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movie-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новый фильм', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name:text:Название (рус)',
            'original_name:text:Название (оригинал)',

            [
                'attribute' => 'poster_small',
                'format' => 'raw',
                'label' => 'Постер',
                'value' => function ($model, $key, $index, $column) {
                    /** @var Movie $model */
                    return Html::img($model->getPoster(Movie::POSTER_SIZE_SMALL), ['class' => 'img-fluid']);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $title = Yii::t('yii', 'View');
                        return Html::a($title, $url, [
                            'class' => 'btn btn-primary',
                        ]);
                    },
                    'update' => function ($url, $model, $key) {
                        $title = Yii::t('yii', 'Update');
                        return Html::a($title, $url, [
                            'class' => 'btn btn-success',
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        $title = Yii::t('yii', 'Delete');
                        return Html::a($title, $url, [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Уверены, что хотите удалить этот фильм?',
                                'method' => 'post',
                            ]
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
