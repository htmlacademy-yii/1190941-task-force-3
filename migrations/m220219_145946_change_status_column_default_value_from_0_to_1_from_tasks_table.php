<?php

use yii\db\Migration;

/**
 * Class m220219_145946_change_status_column_default_value_from_0_to_1_from_tasks_table
 */
class m220219_145946_change_status_column_default_value_from_0_to_1_from_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tasks', 'status', $this->tinyInteger()->unsigned()->defaultValue(1)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220219_145946_change_status_column_default_value_from_0_to_1_from_tasks_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220219_145946_change_status_column_default_value_from_0_to_1_from_tasks_table cannot be reverted.\n";

        return false;
    }
    */
}
