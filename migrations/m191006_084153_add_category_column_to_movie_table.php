<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%movie}}`.
 */
class m191006_084153_add_category_column_to_movie_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%movie}}',
            'category',
            $this->smallInteger()->notNull()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%movie}}','category');
    }
}
