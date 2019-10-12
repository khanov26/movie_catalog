<?php

use app\models\Movie;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\MovieForm */
/* @var $producers array */
/* @var $genres array */
/* @var $countries array */

?>


<div class="movie-form">

    <?php $form = ActiveForm::begin(); ?>

    <ul class="nav nav-tabs mb-3" id="movie-about-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="movie-description-tab" data-toggle="tab" href="#movie-description" role="tab"
               aria-controls="movie-description" aria-selected="true">Описание фильма</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="actors-tab" data-toggle="tab" href="#actors" role="tab" aria-controls="actors"
               aria-selected="false">Актерский состав</a>
        </li>
    </ul>

    <div class="tab-content" id="movie-about-tab-content">
        <div class="tab-pane fade show active" id="movie-description" role="tabpanel" aria-labelledby="home-tab">

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'originalName')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'category')->dropDownList(Movie::CATEGORY_LABELS) ?>

            <?= $form->field($model, 'year')->textInput() ?>

            <?= $form->field($model, 'producer')->widget(AutoComplete::class, [
                'clientOptions' => [
                    'source' => $producers,
                    'minLength' => 3,
                ],
            ])->textInput() ?>

            <?php $genres = Json::encode($genres); ?>

            <?= $form->field($model, 'genres')->widget(AutoComplete::class, [
                'clientOptions' => [
                    'source' => new JsExpression("function( request, response ) {
                            response( $.ui.autocomplete.filter(
                                 $genres, request.term.split( /,\s*/ ).pop() ) );
                        }"),
                    'select' => new JsExpression('function( event, ui ) {
                            var terms = this.value.split( /,\s*/ );
                            // remove the current input
                            terms.pop();
                            // add the selected item
                            terms.push( ui.item.value );
                            // add placeholder to get the comma-and-space at the end
                            terms.push( "" );
                            this.value = terms.join( ", " );
                            return false;
                        }'),
                    'focus' => new JsExpression('function() {
                            // prevent value inserted on focus
                            return false;
                        }'),
                ],
            ])->textInput() ?>

            <?php $countries = Json::encode($countries); ?>

            <?= $form->field($model, 'countries')->widget(AutoComplete::class, [
                'clientOptions' => [
                    'source' => new JsExpression("function( request, response ) {
                            response( $.ui.autocomplete.filter(
                                 $countries, request.term.split( /,\s*/ ).pop() ) );
                        }"),
                    'select' => new JsExpression('function( event, ui ) {
                            var terms = this.value.split( /,\s*/ );
                            // remove the current input
                            terms.pop();
                            // add the selected item
                            terms.push( ui.item.value );
                            // add placeholder to get the comma-and-space at the end
                            terms.push( "" );
                            this.value = terms.join( ", " );
                            return false;
                        }'),
                    'focus' => new JsExpression('function() {
                            // prevent value inserted on focus
                            return false;
                        }'),
                ],
            ])->textInput() ?>

            <?= $form->field($model, 'duration')->textInput() ?>

            <?= $form->field($model, 'ageRating')->textInput() ?>

            <?= $form->field($model, 'plot')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'poster')->fileInput() ?>

            <?= $form->field($model, 'imdbRating')->textInput() ?>

        </div>

        <div class="tab-pane fade mb-3" id="actors" role="tabpanel" aria-labelledby="profile-tab">
            <?php
            if (empty($model->actors)) {
                echo Html::textInput("{$model->formName()}[actors][0]", '', [
                    'class' => 'form-control mb-2',
                ]);
            } else {
                foreach ($model->actors as $num => $actor) {
                    echo Html::textInput("{$model->formName()}[actors][$num]", "$actor", [
                        'class' => 'form-control mb-2',
                    ]);
                }
            }
            ?>

            <?= Html::button('+', ['class' => 'btn btn-success my-2', 'id' => 'add-actor-btn']) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


