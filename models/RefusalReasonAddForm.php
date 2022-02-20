<?php

namespace app\models;

use tf\models\Task as TaskHelper;
use Throwable;
use Yii;
use yii\base\Model;

class RefusalReasonAddForm extends Model
{
    public $comment;

    public function attributeLabels()
    {
        return [
            'comment' => 'Комментарий'
        ];
    }

    public function rules()
    {
        return [
            ['comment', 'string'],
        ];
    }

    public function save(int $taskID)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $reason = new RefusalReason();
            $task = Task::findOne($taskID);

            $reason->comment = $this->comment;
            $reason->task_id = $taskID;
            $reason->user_id = Yii::$app->user->getId();
            $reason->save();

            $task->status = TaskHelper::STATUS_FAILED;
            $task->save(false);

            $transaction->commit();

        } catch(Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
