<?php

namespace app\controllers;

use app\models\Producer;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ProducerController extends Controller
{
    /**
     * Поиск режиссера по имени
     *
     * @param string $term
     * @return \yii\web\Response
     */
    public function actionSearch(string $term)
    {
        $producers = Producer::find()->select('name')->where(['like', 'name', $term])->column();

        return $this->asJson($producers);
    }

    /**
     * Фильмы данного режиссера
     *
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionMovieList(int $id)
    {
        $model = Producer::findOne(['id' => $id]);
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