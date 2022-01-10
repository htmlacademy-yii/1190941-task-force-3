<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "user_reviews".
 *
 * @property int $id
 * @property string $review
 * @property int $rating
 * @property string $created_at
 * @property int $reviewer_id
 * @property int $task_id
 *
 * @property User $reviewer
 * @property Task $task
 */
class UserReview extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user_reviews';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['review', 'rating', 'reviewer_id', 'task_id'], 'required'],
            [['review'], 'string'],
            [['rating', 'reviewer_id', 'task_id'], 'integer'],
            [['created_at'], 'safe'],
            [['reviewer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['reviewer_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'review' => 'Review',
            'rating' => 'Rating',
            'created_at' => 'Created At',
            'reviewer_id' => 'Reviewer ID',
            'task_id' => 'Task ID',
        ];
    }

    /**
     * Gets query for [[Reviewer]].
     *
     * @return ActiveQuery
     */
    public function getReviewer(): ActiveQuery
    {
        return $this->hasOne(User::className(), ['id' => 'reviewer_id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return ActiveQuery
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }
}
