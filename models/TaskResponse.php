<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "task_responses".
 *
 * @property int $id
 * @property string|null $comment
 * @property string $created_at
 * @property int $price
 * @property int $status
 * @property int $task_id
 * @property int $user_id
 *
 * @property Task $task
 * @property User $user
 */
class TaskResponse extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'task_responses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['comment'], 'string'],
            [['created_at'], 'safe'],
            [['price', 'task_id', 'user_id'], 'required'],
            [['price', 'task_id', 'user_id'], 'integer'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'comment' => 'Comment',
            'created_at' => 'Created At',
            'price' => 'Price',
            'task_id' => 'Task ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return ActiveQuery
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
