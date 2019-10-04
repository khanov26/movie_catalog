<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%movie}}`.
 */
class m191004_183022_create_movie_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%movie}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(200),
            'original_name' => $this->string(200),
            'year' => $this->integer(4),
            'producer_id' => $this->integer(),
            'duration' => $this->integer(),
            'age_rating' => $this->integer(),
            'plot' => $this->text(),
            'poster' => $this->string(),
            'poster_small' => $this->string(),
            'imdb_rating' => $this->float(),
        ]);

        // creates index for column `producer_id`
        $this->createIndex(
            'idx-movie-producer_id',
            '{{%movie}}',
            'producer_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-movie-producer_id',
            '{{%movie}}',
            'producer_id',
            'producer',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-movie-producer_id', '{{%movie}}');

        $this->dropIndex('idx-movie-producer_id', '{{%movie}}');

        $this->dropTable('{{%movie}}');
    }
}
