<?php

/** @var \yii\web\View $this */
/** @var \app\models\AdvancedSearchMovie $model */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var array $countries */
/** @var array $ageRatings */
/** @var array $genres */
/** @var int $minYear */

use app\models\Movie;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\LinkPager;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$label = 'Расширенный поиск';
$this->title = Yii::$app->name . ' - ' . $label;
$this->params['breadcrumbs'][] = $label;

$this->registerJs('function getUrlParameter(url, param) {
        let pattern = new RegExp(`${param}=([0-9]+)`);
        let result = url.match(pattern);
        if (result !== null) {
            return result[1];
        }
        return null;
    }

    $(document).on("click", ".pagination a", function (event) {
        event.preventDefault();
        let page = getUrlParameter($(this).attr("href"), "page");
        let form = $("#advanced-search-form");
        let actionUrl = form.attr("action");
        actionUrl += `?page=${page}`;
        form.attr("action", actionUrl);
        form.submit();
    });');

?>
<?php Pjax::begin([
    'clientOptions' => ['method' => 'POST'],
]) ?>

<?php $form = ActiveForm::begin([
    'id' => 'advanced-search-form',
    'action' => Url::to(['']),
    'options' => [
        'data-pjax' => '',
        'class' => 'row',
    ],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-md-8\">{input}\n{hint}\n{error}</div>",
        'labelOptions' => ['class' => 'col-md-4 control-label'],
        'options' => ['class' => 'row form-group'],
    ],
]); ?>
<div class="col-md-6">
    <!--    Категория-->
    <?= $form->field($model, 'category')
        ->dropDownList(ArrayHelper::getColumn(Movie::CATEGORY_LABELS, 'single'),
            ['prompt' => 'Любая']) ?>

    <!--    Страна-->
    <?= $form->field($model, 'country')
        ->dropDownList(array_column($countries, 'name', 'id'),
            ['prompt' => 'Любая']) ?>

    <!--    Возрастной рейтинг-->
    <?= $form->field($model, 'age_rating')
        ->dropDownList(ArrayHelper::map($ageRatings, 'age_rating', function ($elem) {
            return $elem['age_rating'] . '+';
        })) ?>

    <!--    Жанр-->
    <?= $form->field($model, 'genre')
        ->dropDownList(array_column($genres, 'name', 'id'),
            ['prompt' => 'Любой']) ?>


    <!--    Рейтинг iMDB-->
    <?php
    $imdbRatings = [];
    for ($i = 1; $i <= 10; $i += 0.5) {
        $imdbRatings["$i"] = $i;
    }
    ?>
    <?= $form->field($model, 'imdb_rating')->dropDownList($imdbRatings) ?>
</div>

<div class="col-md-6">
    <!--    Год выпуска-->
    <?php
    $years = [];
    for ($i = $minYear, $curYear = (int)date('Y'); $i <= $curYear; $i++) {
        $years[$i] = $i;
    }
    if (empty($model->yearTo)) {
        $model->yearTo = $curYear;
    }
    ?>
    <div class="row form-group">
        <label class="col-md-4">Год выпуска</label>
        <div class="col-md-8 row no-gutters">

            <?= $form->field($model, 'yearFrom', [
                'template' => "<div class=\"d-flex\">{label}\n{input}</div>\n{hint}\n{error}",
                'labelOptions' => ['class' => 'mr-2 control-label'],
                'options' => ['class' => 'col-6 pr-1'],
            ])->dropDownList($years) ?>

            <?= $form->field($model, 'yearTo', [
                'template' => "<div class=\"d-flex\">{label}\n{input}</div>\n{hint}\n{error}",
                'labelOptions' => ['class' => 'mr-2 control-label'],
                'options' => ['class' => 'col-6 pl-1'],
            ])->dropDownList($years) ?>
        </div>
    </div>

    <!--    Название-->
    <?= $form->field($model, 'name')->textInput() ?>

    <!--    Актер-->
    <?= $form->field($model, 'actor')->textInput() ?>

    <!--    Режиссер-->
    <?= $form->field($model, 'producerName')->textInput() ?>

    <!--    Найти-->
    <div class="row form-group">
        <div class="col-md-8 offset-md-4">
            <input type="submit" class="btn btn-primary" value="Найти">
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<hr>
<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_movie',
    'layout' => "{items}\n{pager}",
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
<?php Pjax::end(); ?>
