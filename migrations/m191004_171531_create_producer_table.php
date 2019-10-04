<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%producer}}`.
 */
class m191004_171531_create_producer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%producer}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100),
        ]);

        // creates index for column `name`
        $this->createIndex(
            'idx-producer-name',
            '{{%producer}}',
            'name'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-producer-name',
            '{{%producer}}'
        );

        $this->dropTable('{{%producer}}');
    }
}
