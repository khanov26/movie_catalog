<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%country}}`.
 */
class m191004_171858_create_country_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%country}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100),
        ]);

        // creates index for column `name`
        $this->createIndex(
            'idx-country-name',
            '{{%country}}',
            'name'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-country-name',
            '{{%country}}'
        );

        $this->dropTable('{{%country}}');
    }
}
