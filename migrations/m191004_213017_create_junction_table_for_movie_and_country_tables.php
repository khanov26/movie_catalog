<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%movie_country}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%movie}}`
 * - `{{%country}}`
 */
class m191004_213017_create_junction_table_for_movie_and_country_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%movie_country}}', [
            'movie_id' => $this->integer(),
            'country_id' => $this->integer(),
            'PRIMARY KEY(movie_id, country_id)',
        ]);

        // creates index for column `movie_id`
        $this->createIndex(
            '{{%idx-movie_country-movie_id}}',
            '{{%movie_country}}',
            'movie_id'
        );

        // add foreign key for table `{{%movie}}`
        $this->addForeignKey(
            '{{%fk-movie_country-movie_id}}',
            '{{%movie_country}}',
            'movie_id',
            '{{%movie}}',
            'id',
            'CASCADE'
        );

        // creates index for column `country_id`
        $this->createIndex(
            '{{%idx-movie_country-country_id}}',
            '{{%movie_country}}',
            'country_id'
        );

        // add foreign key for table `{{%country}}`
        $this->addForeignKey(
            '{{%fk-movie_country-country_id}}',
            '{{%movie_country}}',
            'country_id',
            '{{%country}}',
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
            '{{%fk-movie_country-movie_id}}',
            '{{%movie_country}}'
        );

        // drops index for column `movie_id`
        $this->dropIndex(
            '{{%idx-movie_country-movie_id}}',
            '{{%movie_country}}'
        );

        // drops foreign key for table `{{%country}}`
        $this->dropForeignKey(
            '{{%fk-movie_country-country_id}}',
            '{{%movie_country}}'
        );

        // drops index for column `country_id`
        $this->dropIndex(
            '{{%idx-movie_country-country_id}}',
            '{{%movie_country}}'
        );

        $this->dropTable('{{%movie_country}}');
    }
}
