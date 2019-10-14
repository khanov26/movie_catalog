<?php

namespace app\controllers;

use app\models\Movie;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
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

    public function actionList(int $category)
    {
        $categoryLabel = ArrayHelper::getValue(Movie::CATEGORY_LABELS, [$category, 'plural']);
        if ($categoryLabel === null) {
            throw new NotFoundHttpException();
        }

        $query = Movie::find()
            ->with('genres', 'countries', 'producer')
            ->where(['category' => $category])
            ->orderBy(['year' => SORT_DESC]);

        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => Yii::$app->params['moviesPerPage'],
            'defaultPageSize' => Yii::$app->params['moviesPerPage'], // чтобы убрать per-page параметр из url
        ]);

        $movies = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('list', [
            'movies' => $movies,
            'categoryLabel' => $categoryLabel,
            'pages' => $pages,
        ]);
    }
}