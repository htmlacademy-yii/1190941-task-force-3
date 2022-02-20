<?php

use yii\db\Migration;

/**
 * Class m220206_114630_add_default_value_to_status_field_from_users_table
 */
class m220206_114630_add_default_value_to_status_field_from_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('users', 'status', $this->char(45)->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220206_114630_add_default_value_to_status_field_from_users_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220206_114630_add_default_value_to_status_field_from_users_table cannot be reverted.\n";

        return false;
    }
    */
}
