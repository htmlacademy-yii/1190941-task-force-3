<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%task_responses}}`.
 */
class m220212_143420_add_status_column_to_task_responses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'task_responses',
            'status',
            $this->tinyInteger()->unsigned()->defaultValue(1)->notNull()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('task_responses', 'status');
    }
}
