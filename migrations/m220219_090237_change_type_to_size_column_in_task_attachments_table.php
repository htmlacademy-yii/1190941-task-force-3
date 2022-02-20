<?php

use yii\db\Migration;

/**
 * Class m220219_090237_change_type_to_weight_column_in_task_attachments_table
 */
class m220219_090237_change_type_to_size_column_in_task_attachments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('task_attachments', 'type');
        $this->addColumn('task_attachments', 'size', $this->integer()->unsigned()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220219_090237_change_type_to_weight_column_in_task_attachments_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220219_090237_change_type_to_weight_column_in_task_attachments_table cannot be reverted.\n";

        return false;
    }
    */
}
