<?php

use app\models\Movie;
use yii\bootstrap4\LinkPager;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \app\modules\admin\models\SearchMovie */

$this->title = 'Фильмы';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('https://kit.fontawesome.com/633a2100bb.js');
?>
<div class="movie-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новый фильм', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name:text',
            'original_name:text',
            [
                'label' => 'Режиссер',
                'attribute' => 'producerName',
                'value' => static function($model) {
                    return $model->producer->name;
                },
            ],

            [
                'attribute' => 'poster_small',
                'format' => 'raw',
                'label' => 'Постер',
                'value' => static function ($model, $key, $index, $column) {
                    /** @var Movie $model */
                    return Html::img($model->getPoster(Movie::POSTER_SIZE_EXTRA_SMALL), ['class' => 'img-fluid']);
                }
            ],
            'year',
            [
                'label' => 'Категория',
                'attribute' => 'category',
                'filter' => ArrayHelper::getColumn(Movie::CATEGORY_LABELS, 'single'),
                'value' => static function($model) {
                    return Movie::CATEGORY_LABELS[$model->category]['single'];
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => static function ($url) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                            'class' => 'text-primary',
                        ]);
                    },
                    'update' => static function ($url) {
                        return Html::a('<i class="fas fa-pencil-alt"></i>', $url, [
                            'class' => 'text-success',
                        ]);
                    },
                    'delete' => static function ($url) {
                        return Html::a('<i class="fas fa-trash-alt"></i>', $url, [
                            'class' => 'text-danger',
                            'data' => [
                                'confirm' => 'Уверены, что хотите удалить этот фильм?',
                                'method' => 'post',
                            ]
                        ]);
                    },
                ],
                'template' => '{view}<br>{update}<br>{delete}',
            ],
        ],
        'pager' => [
            'class' => LinkPager::class,
            'options' => [
                'class' => 'mt-4',
            ],
            'listOptions' => [
                'class' => 'pagination justify-content-center',
            ]
        ],
    ]) ?>
</div>
