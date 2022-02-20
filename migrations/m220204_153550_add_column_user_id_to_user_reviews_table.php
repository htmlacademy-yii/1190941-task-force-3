<?php

use yii\db\Migration;

/**
 * Class m220204_153550_add_column_user_id_to_user_reviews_table
 */
class m220204_153550_add_column_user_id_to_user_reviews_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_reviews', 'user_id', 'INT UNSIGNED NOT NULL');

        $this->dropForeignKey('user_reviews_fk_user_id', 'user_reviews');

        $this->addForeignKey(
            'user_reviews_fk_task_id',
            'user_reviews',
            'task_id',
            'tasks',
            'id',
            'CASCADE'
        );

        $this->execute('SET FOREIGN_KEY_CHECKS=0;');

        $this->addForeignKey(
            'user_reviews_fk_user_id',
            'user_reviews',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user_reviews', 'user_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220204_153550_add_column_user_id_to_user_reviews_table cannot be reverted.\n";

        return false;
    }
    */
}
