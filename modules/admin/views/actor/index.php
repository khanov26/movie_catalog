<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="actor-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить +', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $title = Yii::t('yii', 'View');
                        return Html::a($title, $url);
                    },
                    'update' => function ($url, $model, $key) {
                        $title = Yii::t('yii', 'Update');
                        return Html::a($title, $url);
                    },
                    'delete' => function ($url, $model, $key) {
                        $title = Yii::t('yii', 'Delete');
                        return Html::a($title, $url);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
