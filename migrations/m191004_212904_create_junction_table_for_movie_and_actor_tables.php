<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%movie_actor}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%movie}}`
 * - `{{%actor}}`
 */
class m191004_212904_create_junction_table_for_movie_and_actor_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%movie_actor}}', [
            'movie_id' => $this->integer(),
            'actor_id' => $this->integer(),
            'PRIMARY KEY(movie_id, actor_id)',
        ]);

        // creates index for column `movie_id`
        $this->createIndex(
            '{{%idx-movie_actor-movie_id}}',
            '{{%movie_actor}}',
            'movie_id'
        );

        // add foreign key for table `{{%movie}}`
        $this->addForeignKey(
            '{{%fk-movie_actor-movie_id}}',
            '{{%movie_actor}}',
            'movie_id',
            '{{%movie}}',
            'id',
            'CASCADE'
        );

        // creates index for column `actor_id`
        $this->createIndex(
            '{{%idx-movie_actor-actor_id}}',
            '{{%movie_actor}}',
            'actor_id'
        );

        // add foreign key for table `{{%actor}}`
        $this->addForeignKey(
            '{{%fk-movie_actor-actor_id}}',
            '{{%movie_actor}}',
            'actor_id',
            '{{%actor}}',
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
            '{{%fk-movie_actor-movie_id}}',
            '{{%movie_actor}}'
        );

        // drops index for column `movie_id`
        $this->dropIndex(
            '{{%idx-movie_actor-movie_id}}',
            '{{%movie_actor}}'
        );

        // drops foreign key for table `{{%actor}}`
        $this->dropForeignKey(
            '{{%fk-movie_actor-actor_id}}',
            '{{%movie_actor}}'
        );

        // drops index for column `actor_id`
        $this->dropIndex(
            '{{%idx-movie_actor-actor_id}}',
            '{{%movie_actor}}'
        );

        $this->dropTable('{{%movie_actor}}');
    }
}
