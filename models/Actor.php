<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "actor".
 *
 * @property int $id
 * @property string $name
 *
 * @property Movie[] $movies
 */
class Actor extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'actor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovies()
    {
        return $this->hasMany(Movie::className(), ['id' => 'movie_id'])->viaTable('movie_actor', ['actor_id' => 'id']);
    }
}
