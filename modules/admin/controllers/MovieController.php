<?php

namespace app\modules\admin\controllers;

use app\models\Country;
use app\models\Genre;
use app\modules\admin\models\CreateMovieForm;
use app\modules\admin\models\SearchMovie;
use app\modules\admin\models\UpdateMovieForm;
use Yii;
use app\models\Movie;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * MovieController implements the CRUD actions for Movie model.
 */
class MovieController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Movie models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchMovie();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Movie model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Movie model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CreateMovieForm();

        if ($model->load(Yii::$app->request->post())) {
            $model->poster = UploadedFile::getInstance($model, 'poster');
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        $genres = Genre::find()->select('name')->asArray()->column();
        $countries = Country::find()->select('name')->asArray()->column();

        return $this->render('create', [
            'model' => $model,
            'genres' => $genres,
            'countries' => $countries,
        ]);
    }

    /**
     * Updates an existing Movie model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $movie = $this->findModel($id);
        $model = new UpdateMovieForm(['movie' => $movie]);

        if ($model->load(Yii::$app->request->post())) {
            $model->poster = UploadedFile::getInstance($model, 'poster');
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $movie->id]);
            }
        }

        $model->loadData();

        $genres = Genre::find()->select('name')->asArray()->column();
        $countries = Country::find()->select('name')->asArray()->column();

        return $this->render('update', [
            'model' => $model,
            'genres' => $genres,
            'countries' => $countries,
        ]);
    }

    /**
     * Deletes an existing Movie model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Movie model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Movie the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Movie
    {
        if (($model = Movie::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
