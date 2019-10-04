<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%movie_genre}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%movie}}`
 * - `{{%genre}}`
 */
class m191004_183445_create_junction_table_for_movie_and_genre_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%movie_genre}}', [
            'movie_id' => $this->integer(),
            'genre_id' => $this->integer(),
            'PRIMARY KEY(movie_id, genre_id)',
        ]);

        // creates index for column `movie_id`
        $this->createIndex(
            '{{%idx-movie_genre-movie_id}}',
            '{{%movie_genre}}',
            'movie_id'
        );

        // add foreign key for table `{{%movie}}`
        $this->addForeignKey(
            '{{%fk-movie_genre-movie_id}}',
            '{{%movie_genre}}',
            'movie_id',
            '{{%movie}}',
            'id',
            'CASCADE'
        );

        // creates index for column `genre_id`
        $this->createIndex(
            '{{%idx-movie_genre-genre_id}}',
            '{{%movie_genre}}',
            'genre_id'
        );

        // add foreign key for table `{{%genre}}`
        $this->addForeignKey(
            '{{%fk-movie_genre-genre_id}}',
            '{{%movie_genre}}',
            'genre_id',
            '{{%genre}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%movie}}`
        $this->dropForeignKey(
            '{{%fk-movie_genre-movie_id}}',
            '{{%movie_genre}}'
        );

        // drops index for column `movie_id`
        $this->dropIndex(
            '{{%idx-movie_genre-movie_id}}',
            '{{%movie_genre}}'
        );

        // drops foreign key for table `{{%genre}}`
        $this->dropForeignKey(
            '{{%fk-movie_genre-genre_id}}',
            '{{%movie_genre}}'
        );

        // drops index for column `genre_id`
        $this->dropIndex(
            '{{%idx-movie_genre-genre_id}}',
            '{{%movie_genre}}'
        );

        $this->dropTable('{{%movie_genre}}');
    }
}
