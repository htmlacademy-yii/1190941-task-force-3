<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%users}}`.
 */
class m220220_131329_drop_role_id_column_from_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('users', 'role_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
