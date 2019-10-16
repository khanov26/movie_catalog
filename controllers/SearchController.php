<?php

namespace app\controllers;

use app\models\Movie;
use Yii;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class SearchController extends Controller
{
    public function actionSearchByName(string $term)
    {
        $movies = Movie::find()
            ->where(['like', 'name', $term])
            ->orWhere(['like', 'original_name', $term])
            ->limit(10)
            ->all();

        $result = [];
        /** @var Movie $movie */
        foreach ($movies as $movie) {
            $result[] = [
                'name' => $movie->name,
                'originalName' => $movie->original_name,
                'poster' => $movie->getPoster(Movie::POSTER_SIZE_EXTRA_SMALL),
                'year' => $movie->year,
                'imdb' => $movie->imdb_rating,
                'genre' => $movie->getGenres()->select('name')->limit(1)->scalar(),
                'link' => Url::to(['/movie/view', 'id' => $movie->id]),
            ];
        }

        return $this->asJson($result);
    }

    public function actionIndex()
    {
        $name = Yii::$app->request->post('headerSearchName');
        $category = (int) Yii::$app->request->post('headerSearchCategory');

        $query = Movie::find()
            ->where(['like', 'name', $name])
            ->orWhere(['like', 'original_name', $name]);

        if (array_key_exists($category, Movie::CATEGORY_LABELS)) {
            $query->andWhere(['category' => $category]);
        } elseif ($category !== 0) {
            throw new BadRequestHttpException('Указана не существующая категория');
        }

        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => Yii::$app->params['moviesPerPage'],
            'defaultPageSize' => Yii::$app->params['moviesPerPage'],
        ]);

        $movies = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('/movie/list', [
            'movies' => $movies,
            'categoryLabel' => 'Результаты поиска',
            'pages' => $pages,
        ]);
    }
}
