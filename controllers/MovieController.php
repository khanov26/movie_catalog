<?php

namespace app\controllers;

use app\models\AdvancedSearchMovie;
use app\models\Country;
use app\models\Genre;
use app\models\Movie;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
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

    public function actionIndex()
    {
        $categories = array_keys(Movie::CATEGORY_LABELS);
        $moviesArray = [];

        foreach ($categories as $category) {
            $moviesArray[$category] = Movie::find()
                ->with('countries')
                ->where(['category' => $category])
                ->orderBy(['year' => SORT_DESC])
                ->limit(20)
                ->all();
        }

        return $this->render('index', [
            'moviesArray' => $moviesArray,
        ]);
    }

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

    public function actionSearch()
    {
        $name = Yii::$app->request->post('headerSearchName');
        $category = (int) Yii::$app->request->post('headerSearchCategory');

        $query = Movie::find()
            ->where(['like', 'name', $name])
            ->orWhere(['like', 'original_name', $name]);

        if (array_key_exists($category, Movie::CATEGORY_LABELS)) {
            $query->andWhere(['category' => $category]);
        } elseif ($category !== 0) {
            throw new NotFoundHttpException('Указана не существующая категория');
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

        return $this->render('list', [
            'movies' => $movies,
            'categoryLabel' => 'Результаты поиска',
            'pages' => $pages,
        ]);
    }

    public function actionAdvancedSearch()
    {
        $model = new AdvancedSearchMovie();
        $dataProvider = $model->search(Yii::$app->request->get());
        $countries = Country::find()->asArray()->all();
        $ageRatings = Movie::find()
            ->select('age_rating')
            ->distinct()
            ->orderBy(['age_rating' => SORT_ASC])
            ->asArray()
            ->all();
        $genres = Genre::find()->asArray()->orderBy(['name' => SORT_ASC])->all();
        $minYear = (int) Movie::find()->min('year');

        return $this->render('advanced-search', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'countries'  => $countries,
            'ageRatings' => $ageRatings,
            'genres' => $genres,
            'minYear' => $minYear,
        ]);
    }
}
