<?php

namespace app\models;

use Throwable;

use Yii;
use yii\base\Model;

use tf\models\Task as TaskHelper;

class UserReviewAddForm extends Model
{
    public $review;
    public $rating;

    public function attributeLabels()
    {
        return [
            'review' => 'Комментарий',
            'rating' => 'Оцените работу',
        ];
    }

    public function rules()
    {
        return [
            ['review', 'string'],
            ['rating', 'required'],
            ['rating', 'integer', 'min' => 1, 'max' => 5],
        ];
    }

    public function save(int $taskID)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $response = new UserReview();
            $task = Task::findOne($taskID);

            $response->review = $this->review;
            $response->rating = $this->rating;
            $response->task_id = $taskID;
            $response->reviewer_id = Yii::$app->user->getId();
            $response->user_id = $task->performer_id;
            $response->save();

            $task->status = TaskHelper::STATUS_DONE;
            $task->save(false);

            $transaction->commit();

        } catch(Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
