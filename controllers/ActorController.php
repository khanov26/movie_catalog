<?php

namespace app\controllers;

use app\models\Actor;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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

    /**
     * Фильмы с участием данного актера
     *
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionMovieList(int $id)
    {
        $model = Actor::findOne(['id' => $id]);
        if ($model === null) {
            throw new NotFoundHttpException();
        }

        $query = $model->getMovies();
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => Yii::$app->params['moviesPerPage'],
            'defaultPageSize' => Yii::$app->params['moviesPerPage'], // чтобы убрать per-page параметр из url
        ]);

        $movies = $query
            ->with('genres', 'countries', 'producer')
            ->orderBy(['year' => SORT_DESC])
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('/movie/list', [
            'movies' => $movies,
            'categoryLabel' => $model->name,
            'pages' => $pages,
        ]);
    }
}