<?php

namespace app\controllers;

use app\models\Movie;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class MovieController extends Controller
{
    public function actionView($id)
    {
        $movie = Movie::find()->where(['id' => $id])->one();

        if ($movie === null) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', [
            'movie' => $movie,
        ]);
    }
}