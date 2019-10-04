<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%actor}}`.
 */
class m191004_162753_create_actor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%actor}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100),
        ]);

        // creates index for column `name`
        $this->createIndex(
            'idx-actor-name',
            '{{%actor}}',
            'name'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-actor-name',
            '{{%actor}}'
        );

        $this->dropTable('{{%actor}}');
    }
}
