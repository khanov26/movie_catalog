<?php

namespace app\controllers;

use app\models\Actor;
use yii\web\Controller;

class ActorController extends Controller
{
    /**
     * Поиск актера по имени
     *
     * @param string $term
     * @return \yii\web\Response
     */
    public function actionSearch(string $term)
    {
        $actors = Actor::find()->select('name')->where(['like', 'name', $term])->column();

        return $this->asJson($actors);
    }
}