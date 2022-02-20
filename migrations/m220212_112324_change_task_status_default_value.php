<?php

use yii\db\Migration;

/**
 * Class m220212_112324_change_task_status_default_value
 */
class m220212_112324_change_task_status_default_value extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tasks', 'status', $this->tinyInteger()->unsigned()->defaultValue(0)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220212_112324_change_task_status_default_value cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220212_112324_change_task_status_default_value cannot be reverted.\n";

        return false;
    }
    */
}
