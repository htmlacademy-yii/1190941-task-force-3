<?php

use yii\db\Migration;

//qstn что такое behaviors()?

/**
 * Class m220118_161124_base
 */
class m220118_161124_create_schema extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('cities', [
            'id' => $this->primaryKey()->unsigned(),
            'title' => $this->char(255)->notNull()->unique(), //qstn нет varchar()?
            'lat' => $this->decimal(13, 10)->notNull(),
            'long' => $this->decimal(13, 10)->notNull(),
        ]);

        $this->createTable('categories', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->char(255)->notNull()->unique(),
            'icon' => $this->char(255)->notNull()->unique(),
        ]);

        $this->createTable('users', [
            'id' => $this->primaryKey()->unsigned(),
            'status' => $this->char(45)->notNull(),
            'name' => $this->char(255)->notNull(),
            'email' => $this->char(255)->notNull()->unique(),
            'password' => $this->char(255)->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('NOW()')->notNull(),
            'last_action_time' => $this->dateTime()->defaultExpression('NOW()')->notNull(),
            'avatar_name' => $this->char(255)->unique(),
            'date_of_birth' => $this->dateTime(),
            'phone' => $this->char(11)->unique(),
            'telegram' => $this->char(64)->unique(),
            'about' => $this->text(),
            'city_id' => $this->integer()->unsigned()->notNull(),
            'role_id' => $this->tinyInteger()->unsigned()->notNull(),
        ]);

        $this->addForeignKey(
            'users_fk_city_id', // qstn есть стандарт того как именуют ключи FK?
            'users',
            'city_id',
            'cities',
            'id',
            'CASCADE'
        );

        $this->createTable('tasks', [
            'id' => $this->primaryKey()->unsigned(),
            'status' => $this->tinyInteger()->unsigned()->defaultValue(1)->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('NOW()')->notNull(), // qstn корректно? текущая дата по умолчанию
            'title' => $this->char(255)->notNull(),
            'description' => $this->text()->notNull(),
            'lat' => $this->decimal(13, 10),
            'long' => $this->decimal(13, 10),
            'price' => $this->integer()->unsigned(),
            'deadline' => $this->dateTime(),
            'category_id' => $this->integer()->unsigned()->notNull(),
            'customer_id' => $this->integer()->unsigned()->notNull(),
            'city_id' => $this->integer()->unsigned(),
            'performer_id' => $this->integer()->unsigned(),
        ]);

        $this->addForeignKey(
            'tasks_fk_category_id',
            'tasks',
            'category_id',
            'categories',
            'id'
        );

        $this->addForeignKey(
            'tasks_fk_customer_id',
            'tasks',
            'customer_id',
            'users',
            'id'
        );

        $this->addForeignKey(
            'tasks_fk_city_id',
            'tasks',
            'city_id',
            'cities',
            'id'

        );

        $this->addForeignKey(
            'tasks_fk_performer_id',
            'tasks',
            'performer_id',
            'users',
            'id'
        );

        $this->createTable('refusal_reasons', [
            'id' => $this->primaryKey()->unsigned(),
            'comment' => $this->text(),
            'task_id' => $this->integer()->unsigned()->notNull(),
            'user_id' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->addForeignKey(
            'refusal_reasons_fk_task_id',
            'refusal_reasons',
            'task_id',
            'tasks',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'refusal_reasons_fk_user_id',
            'refusal_reasons',
            'user_id',
            'users',
            'id'
        );

        $this->createTable('user_reviews', [
            'id' => $this->primaryKey()->unsigned(),
            'review' => $this->text()->notNull(),
            'rating' => $this->tinyInteger()->unsigned()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('NOW()')->notNull(),
            'reviewer_id' => $this->integer()->unsigned()->notNull(),
            'task_id' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->addForeignKey(
            'user_reviews_fk_reviewer_id',
            'user_reviews',
            'reviewer_id',
            'users',
            'id'
        );

        $this->addForeignKey(
            'user_reviews_fk_user_id',
            'user_reviews',
            'task_id',
            'tasks',
            'id',
            'CASCADE'
        );

        $this->createTable('user_specializations', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'category_id' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->addForeignKey(
            'user_specializations_fk_user_id',
            'user_specializations',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'user_specializations_fk_category_id',
            'user_specializations',
            'category_id',
            'categories',
            'id',
            'CASCADE'
        );

        $this->createTable('task_attachments', [
            'id' => $this->primaryKey()->unsigned(),
            'attachment_path' => $this->char(255)->notNull()->unique(),
            'type' => $this->tinyInteger()->unsigned()->notNull(),
            'task_id' => $this->integer()->unsigned()->notNull()
        ]);

        $this->addForeignKey(
            'task_attachments_fk_task_id',
            'task_attachments',
            'task_id',
            'tasks',
            'id',
            'CASCADE'
        );

        $this->createTable('task_responses', [
            'id' => $this->primaryKey()->unsigned(),
            'comment' => $this->text(),
            'created_at' => $this->dateTime()->defaultExpression('NOW()')->notNull(),
            'price' => $this->integer()->unsigned()->notNull(),
            'task_id' => $this->integer()->unsigned()->notNull(),
            'user_id' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->addForeignKey(
            'task_responses_fk_task_id',
            'task_responses',
            'task_id',
            'tasks',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'task_responses_fk_user_id',
            'task_responses',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->createTable('user_settings', [
            'id' => $this->primaryKey()->unsigned(),
            'contacts_to_customer_only' => $this->tinyInteger(1)->unsigned()->defaultValue(0),
            'user_id' => $this->integer()->unsigned()->notNull()
        ]);

        $this->addForeignKey(
            'user_settings_fk_user_id',
            'user_settings',
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
        echo "m220118_161124_create_schema cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220118_161124_base cannot be reverted.\n";

        return false;
    }
    */
}
