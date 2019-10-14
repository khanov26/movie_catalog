<?php

use app\modules\admin\assets\movie\MovieAsset;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\MovieForm */
/* @var $genres array */
/* @var $countries array */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Фильмы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

MovieAsset::register($this);

?>

<div class="movie-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'genres' => $genres,
        'countries' => $countries,
    ]) ?>

</div>

