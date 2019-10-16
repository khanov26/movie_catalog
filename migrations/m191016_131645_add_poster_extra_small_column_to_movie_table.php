<?php

use app\models\Movie;
use yii\db\Migration;
use yii\imagine\Image;

/**
 * Handles adding columns to table `{{%movie}}`.
 */
class m191016_131645_add_poster_extra_small_column_to_movie_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%movie}}',
            'poster_extra_small',
            $this->string()
        );

        $movies = Movie::find()->all();
        /** @var Movie $movie */
        foreach ($movies as $movie) {
            $tmpName = sys_get_temp_dir() . '/' . uniqid('', true);
            $ext = pathinfo($movie->poster, PATHINFO_EXTENSION);
            Image::resize(Yii::$app->storage->getFileFullPath($movie->poster), null, 45)->save($tmpName);
            $movie->poster_extra_small = Yii::$app->storage->saveFile($tmpName, $ext);
            $movie->save(false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $movies = Movie::find()->all();
        /** @var Movie $movie */
        foreach ($movies as $movie) {
            Yii::$app->storage->deleteFile($movie->poster_extra_small);
        }

        $this->dropColumn('{{%movie}}', 'poster_extra_small');
    }
}
