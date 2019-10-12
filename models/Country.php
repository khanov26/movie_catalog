<?php

namespace app\models;

use app\traits\ActiveRecordToStringTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "country".
 *
 * @property int $id
 * @property string $name
 *
 * @property Movie[] $movies
 */
class Country extends ActiveRecord
{
    use ActiveRecordToStringTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'country';
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
        return $this->hasMany(Movie::className(), ['id' => 'movie_id'])->viaTable('movie_country', ['country_id' => 'id']);
    }
}
